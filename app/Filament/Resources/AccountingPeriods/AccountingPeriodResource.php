<?php

namespace App\Filament\Resources\AccountingPeriods;

use App\Filament\Resources\AccountingPeriods\Pages\ManageAccountingPeriods;
use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\JournalEntry;
use App\Models\Member;
use App\Models\MemberShuSnapshot;
use App\Models\ShuAllocation;
use App\Models\ShuDistribution;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\DB;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountingPeriodResource extends Resource
{
    protected static ?string $model = AccountingPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Periode Keuangan';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Periode Buku')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Periode')
                            ->placeholder('Contoh: Tahun Buku 2024')
                            ->required()
                            ->maxLength(255),
                        
                        Grid::make(2)->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->required(),
                        ]),

                        Toggle::make('is_closed')
                            ->label('Tutup Buku (Closed)')
                            ->helperText('Jika aktif, jurnal tidak bisa ditambahkan ke periode ini.')
                            ->default(false),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                    
                TextColumn::make('start_date')
                    ->label('Start')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('End')
                    ->date(),
                
                IconColumn::make('is_closed')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success'),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('download_report')
                    ->label('Download Laporan')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->visible(fn ($record) => $record->is_closed)
                    ->url(fn ($record) => route('reports.finance-bundle', $record))
                    ->openUrlInNewTab(),
                Action::make('tutup_buku')
                    ->label('Tutup Buku & Distribusi')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->visible(fn ($record) => !$record->is_closed)
                    ->steps([
                        Step::make('Konfirmasi SHU')
                            ->description('Validasi angka SHU sebelum diposting')
                            ->schema([
                                Placeholder::make('summary')
                                    ->columnSpanFull()
                                    ->label('Ringkasan Distribusi')
                                    ->content(function ($record) {
                                        $shu = ShuDistribution::where('accounting_period_id', $record->id)->first();
                                        if (!$shu) return '⚠️ Data SHU belum dihitung di menu Distribusi SHU!';
                                        
                                        return "Total SHU (Gross): Rp " . number_format($shu->total_shu, 0, ',', '.') . 
                                            " | Pajak: Rp " . number_format($shu->tax_amount, 0, ',', '.') . 
                                            " | Net SHU: Rp " . number_format($shu->net_shu_to_distribute, 0, ',', '.');
                                    }),
                            ]),
                        Step::make('Periode Baru')
                            ->description('Buka buku untuk tahun berikutnya')
                            ->schema([
                                TextInput::make('next_name')->label('Nama Periode Baru')->placeholder('Tahun Buku 2026')->required(),
                                DatePicker::make('next_start')->label('Tanggal Mulai')->required(),
                                DatePicker::make('next_end')->label('Tanggal Selesai')->required(),
                            ])->columnSpanFull(),
                    ])
                    ->action(function ($record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $shu = ShuDistribution::where('accounting_period_id', $record->id)->first();
                            if (!$shu) return;

                            // --- 1. HITUNG PARAMETER GLOBAL UNTUK PRORATA ---
                            $totalSimpananKoperasi = \App\Models\SavingAccount::whereHas('savingType', fn($q) => $q->where('category', 'equity'))->sum('balance') ?: 1;
                            $totalOmsetAnggota = \App\Models\Order::where('status', 'completed')
                                ->whereBetween('created_at', [$record->start_date, $record->end_date])
                                ->whereNotNull('member_id')
                                ->sum('total_amount') ?: 1;

                            $members = Member::with('savingAccounts.savingType')->get();
                            $shu->details()->delete();

                            // --- 2. LOOPING DISTRIBUSI PER MEMBER (RISET SALDO & DETAIL) ---
                            foreach ($members as $member) {
                                $memberSimpanan = $member->savingAccounts->where('savingType.category', 'equity')->sum('balance');
                                $memberBelanja = $member->orders()->where('status', 'completed')
                                    ->whereBetween('created_at', [$record->start_date, $record->end_date])->sum('total_amount');

                                $breakdown = [];
                                $totalMemberReceived = 0;

                                foreach ($shu->allocation_results as $item) {
                                    $allocation = ShuAllocation::find($item['shu_allocation_id']);
                                    if (!$allocation) continue;

                                    // Hitung nominal alokasi bersih (setelah pajak)
                                    // Rumus: (Amount Alokasi / Total Gross) * Net yang dibagikan
                                    $nominalBersihKategori = ($item['amount'] / $shu->total_shu) * $shu->net_shu_to_distribute;
                                    
                                    $jatahMember = 0;
                                    if ($allocation->code === 'JM') {
                                        $jatahMember = ($memberSimpanan / $totalSimpananKoperasi) * $nominalBersihKategori;
                                    } elseif ($allocation->code === 'JK' || $allocation->code === 'JU') {
                                        $jatahMember = ($memberBelanja / $totalOmsetAnggota) * $nominalBersihKategori;
                                    }

                                    if ($jatahMember > 0) {
                                        $breakdown[$allocation->name] = $jatahMember;
                                        $totalMemberReceived += $jatahMember;
                                    }
                                }

                                if ($totalMemberReceived > 0) {
                                    // Simpan Detail SHU
                                    $shu->details()->create([
                                        'member_id' => $member->id,
                                        'total_savings' => $memberSimpanan,
                                        'total_purchases' => $memberBelanja,
                                        'distribution_breakdown' => $breakdown,
                                        'total_received' => $totalMemberReceived,
                                    ]);

                                    // Update Saldo Riil (Simpanan Sukarela)
                                    $accSukarela = $member->savingAccounts->where('savingType.code', 'SS')->first();
                                    if ($accSukarela) {
                                        $accSukarela->transactions()->create([
                                            'transaction_date' => now(),
                                            'type' => 'deposit',
                                            'amount' => $totalMemberReceived,
                                            'reference_number' => "SHU-" . $record->id . "-" . $member->id,
                                            'notes' => "SHU " . $record->name,
                                            'created_by' => auth()->id(),
                                        ]);
                                    }
                                }
                            }

                            // --- 3. JURNAL GLOBAL (AKUNTANSI) ---
                            $entry = JournalEntry::create([
                                'accounting_period_id' => $record->id,
                                'transaction_date' => $record->end_date,
                                'reference_number' => "CLS-" . $record->id,
                                'description' => "Jurnal Penutup & Distribusi SHU " . $record->name,
                                'total_amount' => $shu->total_shu,
                                'created_by' => auth()->id(),
                            ]);

                            // DEBIT: 3301 (Laba Berjalan)
                            $entry->items()->create(['account_id' => Account::where('code', '3301')->first()->id, 'debit' => $shu->total_shu, 'credit' => 0]);

                            // KREDIT: Pajak (2103)
                            if ($shu->tax_amount > 0) {
                                $entry->items()->create(['account_id' => Account::where('code', '2103')->first()->id, 'debit' => 0, 'credit' => $shu->tax_amount]);
                            }

                            // KREDIT: Alokasi Lainnya
                            foreach ($shu->allocation_results as $res) {
                                $alloc = ShuAllocation::find($res['shu_allocation_id']);
                                $target = in_array($alloc->code, ['JM', 'JU', 'JK']) ? Account::where('code', '2102')->first()->id : $alloc->account_id;
                                if ($target) {
                                    $entry->items()->create(['account_id' => $target, 'debit' => 0, 'credit' => $res['amount']]);
                                }
                            }

                            // --- 4. TUTUP BUKU & BUKA PERIODE BARU ---
                            $record->update(['is_closed' => true, 'closed_at' => now(), 'closed_by' => auth()->id()]);
                            $nextPeriod = AccountingPeriod::create(['name' => $data['next_name'], 'start_date' => $data['next_start'], 'end_date' => $data['next_end'], 'is_closed' => false]);

                            // --- 5. SNAPSHOT UNTUK TAHUN DEPAN (INIT WEIGHT) ---
                            foreach ($members as $member) {
                                MemberShuSnapshot::create([
                                    'member_id' => $member->id,
                                    'accounting_period_id' => $nextPeriod->id,
                                    'accumulated_modal_weight' => $member->savingAccounts->sum('balance') * 365,
                                    'total_transaction_volume' => 0,
                                    'last_updated_at' => now(),
                                ]);
                            }

                            $shu->update(['status' => 'completed']);
                        });

                        Notification::make()->title('Buku Berhasil Ditutup & SHU Telah Masuk ke Saldo Anggota!')->success()->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAccountingPeriods::route('/'),
        ];
    }
}
