<?php

namespace App\Livewire\Auth;

use App\Models\RegistrationFee;
use App\Models\BankAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class ActivationPayment extends Component
{
    use WithFileUploads;

    public $payment_proof;
    public $base_amount = 0;
    public $unique_code = 0;
    public $total_amount = 0;
    public $is_submitted = false;
    public $is_rejected = false;
    public $rejection_note = '';
    
    public $fees = [];
    public $banks = [];

    #[Layout('components.layouts.app')] 

    public function mount()
    {
        $member = Auth::user()->member;

        if ($member->status === 'active') {
            return redirect()->route('dashboard');
        }

        $this->banks = BankAccount::where('is_active', true)->get();

        if ($member->status === 'rejected') {
            $this->is_rejected = true;
            $this->is_submitted = false; 
            $this->rejection_note = $member->activation_payment_data['rejection_note'] ?? 'Mohon periksa kembali bukti pembayaran Anda.';
        } elseif (!empty($member->activation_payment_data)) {
            $this->is_submitted = true;
        }

        $this->loadPaymentDetails($member);
    }

    private function loadPaymentDetails($member)
    {
        if (!empty($member->activation_payment_data)) {
            $data = $member->activation_payment_data;
            $this->unique_code = $data['unique_code'] ?? 0;
            $this->total_amount = $data['total_amount'] ?? 0;
            $this->base_amount = $data['base_amount'] ?? 0;
            $this->fees = $data['fees_breakdown'] ?? []; 
        } else {
            $feeRecords = RegistrationFee::where('is_active', true)
                ->whereIn('member_type', [$member->type, 'all'])
                ->get();

            $this->fees = $feeRecords->toArray();
            $this->base_amount = $feeRecords->sum('amount');
            
            $this->unique_code = Auth::id() % 1000 ?: rand(100, 999);
            $this->total_amount = $this->base_amount + $this->unique_code;
        }
    }

    public function submitPayment()
    {
        $this->validate([
            'payment_proof' => 'required|image|max:3072',
        ]);

        $member = Auth::user()->member;

        if (isset($member->activation_payment_data['proof_path'])) {
            Storage::disk('public')->delete($member->activation_payment_data['proof_path']);
        }

        $path = $this->payment_proof->store('members/activation-proofs', 'public');
        
        $data = [
            'base_amount' => $this->base_amount,
            'unique_code' => $this->unique_code,
            'total_amount' => $this->total_amount,
            'fees_breakdown' => $this->fees,
            'proof_path' => $path,
            'submitted_at' => now()->toDateTimeString(),
            'status' => 'pending'
        ];
        
        $member->update([
            'activation_payment_data' => $data,
            'status' => 'pending',
        ]);

        $this->is_submitted = true;
        $this->is_rejected = false;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Bukti pembayaran telah terkirim. Mohon tunggu verifikasi admin.'
        ]);
    }

    public function render()
    {
        return view('livewire.auth.activation-payment');
    }
}