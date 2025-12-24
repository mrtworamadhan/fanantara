<?php

namespace App\Filament\Resources\ShuDistributions\Pages;

use App\Filament\Resources\ShuDistributions\ShuDistributionResource;
use App\Models\Member;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShuDistribution extends EditRecord
{
    protected static string $resource = ShuDistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('process')
                ->label('Hitung & Distribusikan')
                ->icon('heroicon-o-calculator')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Mulai Perhitungan SHU?')
                ->modalDescription('Sistem akan menghitung jatah SHU untuk setiap anggota berdasarkan Simpanan dan Transaksi mereka pada periode ini. Proses ini mungkin memakan waktu.')
                ->action(function () {
                    $shu = $this->getRecord();
                    
                    // ==========================================
                    // 1. HITUNG DENOMINATOR (PEMBAGI) GLOBAL
                    // ==========================================

                    // A. Total Simpanan Modal Koperasi (Pokok + Wajib seluruh anggota)
                    // Kita ambil dari tabel saving_accounts yang tipe-nya 'equity'
                    $totalSimpananKoperasi = \App\Models\SavingAccount::whereHas('savingType', function ($q) {
                            $q->where('category', 'equity');
                        })->sum('balance');

                    // B. Total Omset Anggota (Belanjaan)
                    // Ambil dari Order yang 'completed' dalam periode tahun buku ini
                    $start = $shu->period->start_date;
                    $end = $shu->period->end_date;
                    
                    $totalOmsetAnggota = Order::where('status', 'completed')
                        ->whereBetween('created_at', [$start, $end])
                        ->whereNotNull('member_id') // Hanya order punya member
                        ->sum('total_amount');

                    // Validasi Anti-Error (Division by Zero)
                    if ($totalSimpananKoperasi <= 0) $totalSimpananKoperasi = 1;
                    if ($totalOmsetAnggota <= 0) $totalOmsetAnggota = 1;

                    // ==========================================
                    // 2. LOOPING PERHITUNGAN PER ANGGOTA
                    // ==========================================
                    
                    // Ambil semua member
                    $members = Member::all();
                    
                    // Reset detail lama biar gak duplikat kalau diklik 2x
                    $shu->details()->delete(); 

                    foreach ($members as $member) {
                        // --- A. DATA SIMPANAN (JASA MODAL) ---
                        // Ambil total saldo dari akun simpanan kategori 'equity' milik member ini
                        $memberSimpanan = $member->savingAccounts()
                            ->whereHas('savingType', fn($q) => $q->where('category', 'equity'))
                            ->sum('balance');

                        // --- B. DATA BELANJA (JASA USAHA) ---
                        // Ambil total belanja dia di periode ini
                        $memberBelanja = $member->orders()
                            ->where('status', 'completed')
                            ->whereBetween('created_at', [$start, $end])
                            ->sum('total_amount');

                        // --- C. RUMUS SHU ---
                        $jasaModal = 0;
                        $jasaUsaha = 0;

                        // Hitung Jasa Modal
                        if ($memberSimpanan > 0) {
                            $jasaModal = ($memberSimpanan / $totalSimpananKoperasi) * $shu->amount_modal;
                        }

                        // Hitung Jasa Usaha
                        if ($memberBelanja > 0) {
                            $jasaUsaha = ($memberBelanja / $totalOmsetAnggota) * $shu->amount_services;
                        }

                        $totalDiterima = $jasaModal + $jasaUsaha;

                        // --- D. SIMPAN HASIL ---
                        if ($totalDiterima > 0) {
                            $shu->details()->create([
                                'member_id'       => $member->id,
                                'total_savings'   => $memberSimpanan,
                                'total_purchases' => $memberBelanja,
                                'shu_modal'       => $jasaModal,
                                'shu_services'    => $jasaUsaha,
                                'total_received'  => $totalDiterima,
                            ]);
                        }
                    }

                    // Update status jadi processed
                    $shu->update(['status' => 'processed']);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Perhitungan SHU Selesai')
                        ->body("Total Modal Koperasi: Rp " . number_format($totalSimpananKoperasi) . "\nTotal Omset Anggota: Rp " . number_format($totalOmsetAnggota))
                        ->success()
                        ->send();
                }),
        ];
    }
}
