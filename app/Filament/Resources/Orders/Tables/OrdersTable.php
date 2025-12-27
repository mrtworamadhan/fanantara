<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('member.id')
                    ->searchable(),
                TextColumn::make('warehouse.name')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->badge(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make()->label(''),
                Action::make('print')
                    ->label('Cetak Invoice')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Order $record) => route('print.invoice', $record))
                    ->openUrlInNewTab(),
                Action::make('confirm_payment')
                    ->label('Terima Pembayaran')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => $record->status === 'pending')
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'processing',
                            'payment_status' => 'paid'
                        ]);
                        
                        Notification::make()->title('Pembayaran Dikonfirmasi')->success()->send();
                    }),

                // ACTION BUTTON: SELESAIKAN ORDER (Untuk yg Processing -> Completed)
                // INI TOMBOL SAKTI PEMICU JURNAL & SHU
                Action::make('mark_completed')
                    ->label('Selesai / Diambil')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Order?')
                    ->modalDescription('Pastikan barang sudah diserahkan/dikirim. Sistem akan mencatat Jurnal & SHU otomatis.')
                    ->visible(fn (Order $record) => $record->status === 'processing' && $record->payment_status === 'paid')
                    ->action(function (Order $record) {
                        // Update status ke completed
                        // Ini akan memicu OrderObserver::updated()
                        $record->update(['status' => 'completed']);
                        
                        Notification::make()->title('Order Selesai. Jurnal Tercatat.')->success()->send();
                    }),
                Action::make('pay')
                    ->label('Bayar / Cicil')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->payment_status !== 'paid' && $record->status === 'completed') 
                    ->mountUsing(function (Schema $form, $record) {
                            $form->fill([
                                'amount' => $record->remaining_balance,
                                'payment_date' => now(),
                            ]);
                        })
                    ->form([
                        DatePicker::make('payment_date')
                            ->label('Tanggal Bayar')
                            ->required(),
                        
                        Select::make('account_id')
                            ->label('Masuk ke Akun Kas/Bank?')
                            ->options(fn () =>
                                    \App\Models\Account::where('type', 'asset')
                                        ->where('code', 'like', '110%')
                                        ->pluck('name', 'id')
                                )
                            ->searchable()
                            ->required(),

                        TextInput::make('amount')
                            ->label('Nominal Pembayaran')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->rule(fn ($record) => 'max:' . $record->remaining_balance)
,
                            
                        TextInput::make('reference_number')
                            ->label('No. Ref / Bukti Transfer'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->payments()->create([
                            'amount' => $data['amount'],
                            'payment_date' => $data['payment_date'],
                            'account_id' => $data['account_id'],
                            'reference_number' => $data['reference_number'],
                            'created_by' => auth()->id(),
                        ]);
                        $totalPaid = $record->payments()->sum('amount');
                        if ($totalPaid >= $record->total_amount) {
                            $status = 'paid';
                        } elseif ($totalPaid > 0) {
                            $status = 'partial';
                        } else {
                            $status = 'unpaid';
                        }
                        $record->update(['payment_status' => $status]);

                        Notification::make()
                            ->title('Pembayaran Berhasil Disimpan')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
