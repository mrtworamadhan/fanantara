<?php

namespace App\Filament\Resources\Registrations;

use App\Filament\Resources\Registrations\Pages\ManageRegistrations;
use App\Models\Member;
use App\Models\Registration;
use App\Models\SavingAccount;
use App\Models\SavingTransaction;
use App\Models\SavingType;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistrationResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;
    protected static string | UnitEnum | null $navigationGroup = 'Keanggotaan';

    protected static ?string $navigationLabel = 'Pendaftaran Baru';
    protected static ?string $slug = 'registrations';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', 'pending');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime()
                    ->sortable(),
                
                TextColumn::make('name')
                    ->label('Nama Calon Anggota')
                    ->getStateUsing(function ($record) {
                        return $record->name; 
                    })
                    ->searchable(query: function ($query, string $search) {
                        return $query->whereHasMorph(
                            'profileable',
                            ['App\Models\IndividualProfile', 'App\Models\InstitutionProfile'], // Model target
                            function ($q, $type) use ($search) {
                                if ($type === 'App\Models\IndividualProfile') {
                                    $q->where('full_name', 'like', "%{$search}%");
                                } else {
                                    $q->where('company_name', 'like', "%{$search}%");
                                }
                            }
                        );
                    })
                    ->weight(FontWeight::Bold),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors(['primary' => 'individual', 'warning' => 'institution']),

                TextColumn::make('referral_code')
                    ->label('Kode Referral')
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('activation_payment_data.total_amount')
                    ->label('Nominal Transfer')
                    ->money('IDR')
                    ->placeholder('Belum Upload'),
            ])
            ->actions([
                Action::make('review')
                    ->label('Review & Validasi')
                    ->icon('heroicon-m-eye')
                    ->color('primary')
                    ->modalHeading('Validasi Pembayaran Simpanan Pokok')
                    ->modalSubmitActionLabel('Proses Keputusan')
                    ->form([
                        ViewField::make('proof_preview')
                            ->view('filament.forms.components.payment-proof-viewer'), 

                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount_display')
                                    ->label('Jumlah Transfer')
                                    ->default(fn ($record) => 'Rp ' . number_format($record->activation_payment_data['total_amount'] ?? 0, 0, ',', '.'))
                                    ->disabled(),
                                TextInput::make('bank_display')
                                    ->label('Bank Pengirim')
                                    ->default(fn ($record) => ($record->activation_payment_data['bank_name'] ?? '-') . ' - ' . ($record->activation_payment_data['bank_account'] ?? '-'))
                                    ->disabled(),
                            ]),

                        // 3. Pilihan Keputusan
                        Radio::make('decision')
                            ->label('Keputusan Admin')
                            ->options([
                                'approve' => 'TERIMA (Approve)',
                                'reject' => 'TOLAK (Reject)',
                            ])
                            ->required()
                            ->live(),

                        Textarea::make('rejection_note')
                            ->label('Alasan Penolakan')
                            ->placeholder('Contoh: Bukti transfer buram / Nominal tidak sesuai.')
                            ->visible(fn (Get $get) => $get('decision') === 'reject')
                            ->required(fn (Get $get) => $get('decision') === 'reject'),
                    ])
                    ->action(function (Member $record, array $data) {
                        $paymentData = $record->activation_payment_data ?? [];

                        if ($data['decision'] === 'approve') {
                            
                            $record->update([
                                'status' => 'active',
                                'join_date' => now(),
                                'member_number' => 'MBR-' . date('Ym') . str_pad($record->id, 4, '0', STR_PAD_LEFT), // Generate No Anggota
                            ]);

                            $paymentData['status'] = 'approved';
                            $paymentData['approved_at'] = now()->toDateTimeString();
                            $paymentData['approved_by'] = auth()->id();
                            $record->update(['activation_payment_data' => $paymentData]);

                            $spType = SavingType::where('code', 'SP')->first();
                            
                            if ($spType) {
                                $account = SavingAccount::firstOrCreate([
                                    'member_id' => $record->id,
                                    'saving_type_id' => $spType->id,
                                ], [
                                    'account_number' => 'SP-' . $record->member_number,
                                    'balance' => 0, // Nanti nambah via transaksi
                                ]);

                                SavingTransaction::create([
                                    'saving_account_id' => $account->id,
                                    'type' => 'deposit',
                                    'amount' => $paymentData['base_amount'] ?? 0, 
                                    'transaction_date' => now(),
                                    'reference_number' => 'REG-' . $record->id,
                                    'notes' => 'Setoran Awal Simpanan Pokok (Auto Register)',
                                    'created_by' => auth()->id(),
                                ]);
                            }

                            Notification::make()->title('Member Diterima & Aktif')->success()->send();

                        } else {
                            
                            // 1. Update JSON Data (Masukan alasan ke kolom json)
                            $paymentData['status'] = 'rejected';
                            $paymentData['rejected_at'] = now()->toDateTimeString();
                            $paymentData['rejected_by'] = auth()->id();
                            $paymentData['rejection_note'] = $data['rejection_note'];
                            
                            $record->update([
                                'status' => 'rejected', 
                                'activation_payment_data' => $paymentData
                            ]);

                            Notification::make()->title('Pendaftaran Ditolak')->warning()->send();
                        }
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRegistrations::route('/'),
        ];
    }
}
