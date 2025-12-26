<?php

namespace App\Livewire\Auth;

use App\Models\SavingType;
use Illuminate\Support\Facades\Auth;
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
    public $bank_account = '883012345678';
    public $bank_name = 'BCA - KOP FANANTARA';
    public $is_submitted = false;
    public $is_rejected = false;
    public $rejection_note = '';

    #[Layout('components.layouts.app')] 

    public function mount()
    {
        $member = Auth::user()->member;

        if ($member->status === 'active') {
            return redirect()->route('dashboard');
        }
        if ($member->status === 'rejected') {
            $this->is_rejected = true;
            $this->is_submitted = false; 
            
            $this->rejection_note = $member->activation_payment_data['rejection_note'] ?? 'Mohon periksa kembali bukti pembayaran Anda.';
            
            $this->loadPaymentDetails($member);

        } elseif (!empty($member->activation_payment_data)) {
            $this->is_submitted = true;
            $this->loadPaymentDetails($member);
        
        } else {
            $this->loadPaymentDetails($member);
        }

        
    }

    private function loadPaymentDetails($member)
    {
        if (!empty($member->activation_payment_data)) {
            $this->unique_code = $member->activation_payment_data['unique_code'] ?? 0;
            $this->total_amount = $member->activation_payment_data['total_amount'] ?? 0;
            $this->base_amount = $member->activation_payment_data['base_amount'] ?? 0;
        } else {
            $savingType = SavingType::where('code', 'SP')->first();
            $defaultAmount = 100000;
            
            if ($savingType) {
                $defaultAmount = ($member->type === 'institution') 
                    ? ($savingType->amount_institution ?? $savingType->amount_individual) 
                    : $savingType->amount_individual;
            }

            $this->base_amount = $defaultAmount;
            
            $this->unique_code = Auth::id() % 1000;
            if($this->unique_code == 0) $this->unique_code = rand(100, 999);

            $this->total_amount = $this->base_amount + $this->unique_code;
        }
    }

    public function submitPayment()
    {
        $this->validate([
            'payment_proof' => 'required|image|max:3072',
        ]);

        $path = $this->payment_proof->store('payment-proofs', 'public');
        $member = Auth::user()->member;
        
        $data = [
            'base_amount' => $this->base_amount,
            'unique_code' => $this->unique_code,
            'total_amount' => $this->total_amount,
            'proof_path' => $path,
            'bank_name' => $this->bank_name,
            'bank_account' => $this->bank_account,
            'submitted_at' => now()->toDateTimeString(),
            'status' => 'pending'
        ];
        
        $member->update([
            'activation_payment_data' => $data,
            'status' => 'pending',
        ]);

        $this->is_submitted = true;
        $this->is_rejected = false;
        session()->flash('message', 'Bukti berhasil dikirim! Mohon tunggu verifikasi admin.');
    }

    public function render()
    {
        return view('livewire.auth.activation-payment');
    }
}