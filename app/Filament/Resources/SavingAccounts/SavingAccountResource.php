<?php

namespace App\Filament\Resources\SavingAccounts;

use App\Filament\Resources\SavingAccounts\Pages\ManageSavingAccounts;
use App\Models\SavingAccount;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SavingAccountResource extends Resource
{
    protected static ?string $model = SavingAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string | UnitEnum | null $navigationGroup = 'Simpanan';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Data Rekening';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->label('Anggota')
                    ->disabled(),
                        
                Select::make('saving_type_id')
                    ->relationship('savingType', 'name')
                    ->label('Jenis Simpanan')
                    ->disabled(), 
                            
                TextInput::make('account_number')
                    ->label('No. Rekening')
                    ->disabled(),
                            
                TextInput::make('balance')
                    ->label('Saldo Saat Ini')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(),                           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('account_number')
                    ->label('No. Rekening')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('member.name')
                    ->label('Nama Anggota')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('savingType.name')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Simpanan Pokok' => 'danger',
                        'Simpanan Wajib' => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                TextColumn::make('balance')
                    ->label('Saldo Akhir')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
            ])
            ->filters([
                SelectFilter::make('saving_type_id')
                    ->label('Filter Jenis Simpanan')
                    ->relationship('savingType', 'name'),
            ])
            ->recordActions([
                Action::make('print_statement')
                    ->label('Mutasi')
                    ->icon('heroicon-o-printer')
                    ->button()
                    ->color('gray')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->default(now()->startOfMonth())
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (SavingAccount $record, array $data) {
                        $transactions = $record->transactions() 
                            ->whereBetween('transaction_date', [$data['start_date'], $data['end_date']])
                            ->orderBy('transaction_date', 'asc')
                            ->get();

                        $pdf = Pdf::loadView('pdf.statement', [
                            'account' => $record,
                            'transactions' => $transactions,
                            'periode' => $data
                        ]);
                        
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'Mutasi_' . $record->account_number . '.pdf');
                    }),
            ])
            ->toolbarActions([
                // 
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSavingAccounts::route('/'),
        ];
    }
}
