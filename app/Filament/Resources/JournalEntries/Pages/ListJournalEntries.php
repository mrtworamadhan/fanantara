<?php

namespace App\Filament\Resources\JournalEntries\Pages;

use App\Filament\Resources\JournalEntries\JournalEntryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJournalEntries extends ListRecords
{
    protected static string $resource = JournalEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('financial_report')
            //     ->label('Laporan Keuangan (SAK EP)')
            //     ->icon('heroicon-o-presentation-chart-line')
            //     ->url(FinancialReport::getUrl()) // Arahkan ke URL page custom tadi
            //     ->color('success'),
            CreateAction::make(),
        ];
    }
}
