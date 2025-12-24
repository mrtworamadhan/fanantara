<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopIndividualTable extends BaseWidget
{

    protected static ?int $sort = 5; 

    protected int | string | array $columnSpan = 'md'; 

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->where('type', 'individual')
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
                    ->label('Nama Anggota')
                    ->description(fn (Member $record) => ucfirst($record->individualProfile->job_type ?? '-'))
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('individualProfile.production_profile')
                    ->state(function (Member $record) {
                        $profile = $record->individualProfile;

                        if (! $profile) return '-';

                        $data = $profile->production_profile;

                        $luas = $data['luas_lahan'] ?? 0;

                        return $luas ? $luas . ' Ha' : '-';
                    })
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
}