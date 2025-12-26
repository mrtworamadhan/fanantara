<?php

namespace App\Filament\Resources\ShuDistributions\Pages;

use App\Filament\Resources\ShuDistributions\ShuDistributionResource;
use App\Models\Member;
use App\Models\Order;
use App\Models\Account;
use App\Models\SavingAccount;
use App\Models\ShuAllocation;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

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
                ->modalDescription('Sistem akan memindahkan laba ke simpanan sukarela anggota berdasarkan porsi alokasi yang telah ditentukan.')
                ->action(function () {
                    $shu = $this->getRecord();
                    
                    if ($shu->status === 'completed') {
                        Notification::make()->title('SHU sudah diproses sebelumnya.')->danger()->send();
                        return;
                    }

                    DB::transaction(function () use ($shu) {
                        $start = $shu->period->start_date;
                        $end = $shu->period->end_date;
                        $allocationItems = $shu->allocation_results; // Ambil data JSON hasil input di Form
                        
                        // 1. HITUNG PARAMETER GLOBAL (Untuk Prorata Anggota)
                        $totalSimpananKoperasi = SavingAccount::whereHas('savingType', fn($q) => $q->where('category', 'equity'))->sum('balance') ?: 1;
                        $totalOmsetAnggota = Order::where('status', 'completed')
                            ->whereBetween('created_at', [$start, $end])
                            ->whereNotNull('member_id')
                            ->sum('total_amount') ?: 1;

                        $totalMemberSHUCollectively = 0; // Untuk menampung total JM + JK yang dibagikan ke anggota
                        $members = Member::all();

                        $shu->details()->delete();

                        // --- 2. LOOPING DISTRIBUSI PER MEMBER ---
                        foreach ($members as $member) {
                            $memberSimpanan = $member->savingAccounts()
                                ->whereHas('savingType', fn($q) => $q->where('category', 'equity'))
                                ->sum('balance');

                            $memberBelanja = $member->orders()
                                ->where('status', 'completed')
                                ->whereBetween('created_at', [$start, $end])
                                ->sum('total_amount');

                            $breakdown = [];
                            $totalMemberReceived = 0;

                            foreach ($allocationItems as $item) {
                                $allocation = ShuAllocation::find($item['shu_allocation_id']);
                                if (!$allocation) continue;

                                $nominalKategori = (float) $item['amount'];
                                $jatahMember = 0;

                                // Hitung Prorata sesuai kode alokasi
                                if ($allocation->code === 'JM') { // Jasa Modal
                                    $jatahMember = ($memberSimpanan / $totalSimpananKoperasi) * $nominalKategori;
                                } elseif ($allocation->code === 'JK') { // Jasa Kontribusi/Usaha
                                    $jatahMember = ($memberBelanja / $totalOmsetAnggota) * $nominalKategori;
                                }

                                if ($jatahMember > 0) {
                                    $breakdown[$allocation->name] = $jatahMember;
                                    $totalMemberReceived += $jatahMember;
                                }
                            }

                            if ($totalMemberReceived > 0) {
                                // Simpan rincian detail member
                                $shu->details()->create([
                                    'member_id' => $member->id,
                                    'total_savings' => $memberSimpanan,
                                    'total_purchases' => $memberBelanja,
                                    'distribution_breakdown' => $breakdown,
                                    'total_received' => $totalMemberReceived,
                                ]);

                                // Update Simpanan Sukarela Anggota (Trigger Observer untuk Jurnal per Member)
                                $accSukarela = $member->savingAccounts()
                                    ->whereHas('savingType', fn($q) => $q->where('code', 'SS'))
                                    ->first();

                                if ($accSukarela) {
                                    $accSukarela->transactions()->create([
                                        'transaction_date' => now(),
                                        'type'             => 'deposit',
                                        'amount'           => $totalMemberReceived,
                                        'reference_number' => "SHU-" . $shu->id . "-" . $member->id,
                                        'notes'            => "SHU " . $shu->period->name,
                                        'created_by'       => auth()->id(),
                                    ]);
                                }
                                $totalMemberSHUCollectively += $totalMemberReceived;
                            }
                        }

                        // --- 3. JURNAL GLOBAL (PENUTUPAN SHU KE SEMUA POS ALOKASI) ---
                        // Kita buat satu entri jurnal besar untuk mendistribusikan Total SHU
                        $entry = \App\Models\JournalEntry::create([
                            'accounting_period_id' => $shu->accounting_period_id,
                            'transaction_date'     => $shu->period->end_date,
                            'reference_number'     => "SHU-DIST-" . $shu->id,
                            'description'          => "Distribusi SHU Periode " . $shu->period->name,
                            'total_amount'         => $shu->total_shu,
                            'created_by'           => auth()->id(),
                        ]);

                        // A. DEBIT: 3301 (SHU Tahun Berjalan) -> SALDO JADI NOL
                        $entry->items()->create([
                            'account_id' => Account::where('code', '3301')->first()->id,
                            'debit'      => $shu->total_shu,
                            'credit'     => 0,
                        ]);

                        // B. KREDIT: KE SEMUA AKUN TUJUAN ALOKASI
                        foreach ($allocationItems as $item) {
                            $allocation = ShuAllocation::find($item['shu_allocation_id']);
                            $nominal = (float) $item['amount'];

                            if ($allocation && $nominal > 0) {
                                // Jika JM atau JU, Kredit ke 2102 (Simpanan Sukarela) secara kolektif
                                if (in_array($allocation->code, ['JM', 'JU'])) {
                                    $targetAccountId = Account::where('code', '2102')->first()->id;
                                } else {
                                    // Jika alokasi lain (Cadangan, Pengurus, Sosial), gunakan mapping account_id di master
                                    $targetAccountId = $allocation->account_id; 
                                }

                                if ($targetAccountId) {
                                    $entry->items()->create([
                                        'account_id' => $targetAccountId,
                                        'debit'      => 0,
                                        'credit'     => $nominal,
                                    ]);
                                }
                            }
                        }

                        // --- 4. FINALIZE ---
                        $shu->update(['status' => 'completed']);
                        $shu->period->update(['is_closed' => true]);

                        Notification::make()->title('SHU Berhasil Didistribusikan secara Akuntansi')->success()->send();
                    });
                }),
        ];
    }
}