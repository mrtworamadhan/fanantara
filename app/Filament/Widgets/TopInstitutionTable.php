<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopInstitutionTable extends BaseWidget
{

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'md'; 

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->where('type', 'institution')
                    ->whereHas('savingAccounts', function (Builder $query) {
                        $query->whereHas('savingType', fn ($q) => $q->where('code', 'SS'));
                    })
                    ->withSum(['savingAccounts as ss_balance' => function ($query) {
                        $query->whereHas('savingType', fn ($q) => $q->where('code', 'SS'));
                    }], 'balance')
                    ->orderByDesc('ss_balance') 
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Lembaga')
                    ->description(fn (Member $record) => $record->institutionProfile->supply_chain_role ?? 'Member Biasa')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('institutionProfile.annual_turnover')
                    ->label('Omset Bisnis')
                    ->money('IDR')
                    ->color('gray')
                    ->size('xs'),

                Tables\Columns\TextColumn::make('ss_balance')
                    ->label('Saldo Sukarela')
                    ->money('IDR')
                    ->color('success')
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
    protected function getPollingInterval(): ?string
    {
        return '15s';
    }
}