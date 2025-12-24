<?php

namespace App\Filament\Resources\Members\Tables;

use App\Models\Member;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Foto')
                    ->circular() 
                    ->state(function ($record) {
                        return $record->image_url; 
                    })
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('member_number')
                    ->label('No Anggota')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Anggota')
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
                    }),
                BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'individual',
                        'warning' => 'institution',
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'suspended',
                        'gray' => 'pending',
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                
                Action::make('print_card')
                    ->label('ID Card')
                    ->icon('heroicon-o-identification')
                    ->color('info')
                    ->url(fn (Member $record) => route('print.card', $record))
                    ->openUrlInNewTab(),
                EditAction::make()->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
