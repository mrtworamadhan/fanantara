<?php

namespace App\Filament\Resources\Registrations;

use App\Filament\Resources\Registrations\Pages\ManageRegistrations;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\Member;
use App\Models\Registration;
use App\Models\SavingAccount;
use App\Models\SavingTransaction;
use App\Models\SavingType;
use App\Notifications\MemberNotification;
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
use Illuminate\Support\Facades\DB;
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
                            ['App\Models\IndividualProfile', 'App\Models\InstitutionProfile'], 
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
                                'member_number' => 'MBR-' . date('Ym') . str_pad($record->id, 4, '0', STR_PAD_LEFT),
                            ]);

                            $paymentData['status'] = 'approved';
                            $paymentData['approved_at'] = now()->toDateTimeString();
                            $paymentData['approved_by'] = auth()->id();
                            $record->update(['activation_payment_data' => $paymentData]);

                            $fees = $paymentData['fees_breakdown'] ?? [];

                            $period = \App\Models\AccountingPeriod::where('is_closed', false)->latest()->first();

                            foreach ($fees as $fee) {
                                $typeCode = match($fee['name']) {
                                    'Simpanan Pokok' => 'SP',
                                    'Simpanan Wajib' => 'SW',
                                    'Modal Koperasi' => 'SJP',
                                    'Hibah'          => 'HIBAH_MANUAL',
                                    default          => 'SS',
                                };

                                if ($typeCode === 'HIBAH_MANUAL') {
                                    static::createManualHibahJournal($record, $fee['amount'], $period);
                                } else {
                                    $savingType = SavingType::where('code', $typeCode)->first();
                                    if ($savingType) {
                                        $account = SavingAccount::where('member_id', $record->id)
                                            ->where('saving_type_id', $savingType->id)
                                            ->first();

                                        if ($account) {
                                            SavingTransaction::create([
                                                'saving_account_id' => $account->id,
                                                'type'              => 'deposit',
                                                'amount'            => $fee['amount'],
                                                'transaction_date'  => now(),
                                                'reference_number'  => 'REG-' . $record->id . '-' . $typeCode,
                                                'notes'             => 'Setoran Awal ' . $fee['name'] . ' (Auto Approval)',
                                                'created_by'        => auth()->id(),
                                            ]);
                                        }
                                    }
                                }
                                if ($record->user) {
                                    $record->user->notify(new MemberNotification([
                                        'title'   => 'Akun Aktif!',
                                        'message' => 'Selamat ' . $record->name . ', pendaftaran Anda telah disetujui.',
                                        'type'    => 'success',
                                        'url'     => route('dashboard'),
                                    ]));
                                }

                                if ($record->user && $record->user->email) {
                                    $record->load('savingAccounts.savingType');
                                    
                                    try {
                                        \Illuminate\Support\Facades\Mail::to($record->user->email)
                                            ->send(new \App\Mail\WelcomeMemberMail($record));
                                    } catch (\Exception $e) {
                                        \Illuminate\Support\Facades\Log::error("Gagal kirim Welcome Email: " . $e->getMessage());
                                    }
                                }
                            }

                            Notification::make()->title('Member Aktif & Saldo Terdistribusi Otomatis')->success()->send();

                        } else {
                            
                            $paymentData['status'] = 'rejected';
                            $paymentData['rejected_at'] = now()->toDateTimeString();
                            $paymentData['rejected_by'] = auth()->id();
                            $paymentData['rejection_note'] = $data['rejection_note'];
                            
                            $record->update([
                                'status' => 'rejected', 
                                'activation_payment_data' => $paymentData
                            ]);
                            if ($record->user) {
                                $record->user->notify(new MemberNotification([
                                    'title'   => 'Pendaftaran Ditolak',
                                    'message' => 'Maaf, pendaftaran Anda ditolak karena: ' . $data['rejection_note'],
                                    'type'    => 'error',
                                    'url'     => route('member.activation'),
                                ]));

                                try {
                                    \Illuminate\Support\Facades\Mail::to($record->user->email)
                                        ->send(new \App\Mail\RegistrationRejectedMail($record, $data['rejection_note']));
                                } catch (\Exception $e) {
                                    \Illuminate\Support\Facades\Log::error("Gagal kirim Email Penolakan: " . $e->getMessage());
                                }
                            }

                            Notification::make()->title('Pendaftaran Ditolak')->warning()->send();
                        }
                    })
            ]);
    }
    protected static function createManualHibahJournal($member, $amount, $period)
    {
        $accKas = Account::where('code', '1101')->first()?->id;
        $accHibah = Account::where('code', '3103')->first()?->id;

        if (!$accKas || !$accHibah || !$period) return;

        DB::transaction(function () use ($member, $amount, $period, $accKas, $accHibah) {
            $journal = JournalEntry::create([
                'accounting_period_id' => $period->id,
                'transaction_date'     => now(),
                'reference_number'     => 'HB-' . $member->member_number,
                'description'          => 'Penerimaan Dana Hibah - ' . $member->name . ' (' . $member->member_number . ')',
                'sourceable_id'        => $member->id,
                'sourceable_type'      => Member::class,
                'total_amount'         => $amount,
                'created_by'           => auth()->id() ?? 1,
            ]);

            $journal->items()->create(['account_id' => $accKas, 'debit' => $amount, 'credit' => 0]);
            $journal->items()->create(['account_id' => $accHibah, 'debit' => 0, 'credit' => $amount]);
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRegistrations::route('/'),
        ];
    }
}
