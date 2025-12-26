<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\SavingAccount;
use App\Services\ShuService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ShuAllocation; // Pastikan model ini dibuat nanti

class Dashboard extends Component
{
    public $member_name;
    public $member_number;
    public $member_type;

    public $member_photo;

    public $total_asset = 0;
    public $saldo_sukarela = 0;
    public $accounts_list = [];

    public $total_contribution = 0;
    public $poin_rewards = 0;
    public $asset_growth = 0;

    public $shu_data = [];

    public $profile_completion = 0;
    public $show_completion_modal = false;
    public $allocations = [];

    #[Layout('components.layouts.app')]
    public function mount(ShuService $shuCalculator)
    {
        $user = Auth::user();
        
        if (!$user->member) {
            return redirect()->route('filament.admin.pages.dashboard'); 
        }

        $member = $user->member;
        $shuService = app(ShuService::class);

        $this->member_photo = $member->image_url;
        $this->member_name = $user->name;
        $this->member_number = $member->member_number ?? 'REG-' . $member->id;
        $this->member_type = match ($member->type) {
            'individual' => 'Anggota Perorangan',
            'institution' => 'Anggota Institusi',
            default => 'Anggota',
        };

        $this->allocations = $shuService->getActiveAllocations();

        $this->shu_data = $shuService->getEstimatedShu($member->id);        

        $this->total_contribution = $member->orders()
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $this->poin_rewards = floor($this->total_contribution / 100000);

        $depositBulanIni = $member->savingAccounts()
            ->whereHas('transactions', function($q) {
                $q->whereMonth('transaction_date', now()->month)
                  ->where('type', 'deposit');
            })->count();
            
        $this->asset_growth = ($depositBulanIni > 0) ? "+5.2%" : "0%"; 
        
        $filledFields = 0;
        $totalFields = 0;

        $baseFields = [
            'street_address', 
            'province_code', 
            'city_code', 
            'district_code', 
            'postal_code',
            'digital_signature' 
        ];

        foreach ($baseFields as $field) {
            $totalFields++;
            if (!empty($member->$field)) {
                $filledFields++;
            }
        }

        if ($member->type === 'individual') {
            $profile = $member->individualProfile;
            
            if ($profile) {
                $indivFields = [
                    'nik', 
                    'gender', 
                    'place_of_birth', 
                    'date_of_birth', 
                    'job_type', 
                    'ktp_image'
                ];

                foreach ($indivFields as $field) {
                    $totalFields++;
                    if (!empty($profile->$field)) $filledFields++;
                }
                $totalFields++;
                if ($this->isJsonFilled($profile->consumption_profile)) $filledFields++;

                $totalFields++;
                if ($this->isJsonFilled($profile->production_profile)) $filledFields++;
            }

        } else {
            $profile = $member->institutionProfile;

            if ($profile) {
                $instFields = [
                    'nib', 
                    'npwp', 
                    'pic_name', 
                    'pic_phone', 
                    'supply_chain_role', 
                    'nib_image'
                ];

                foreach ($instFields as $field) {
                    $totalFields++;
                    if (!empty($profile->$field)) $filledFields++;
                }

                $totalFields++;
                if ($this->isJsonFilled($profile->logistics_capacity)) $filledFields++;
                
                $totalFields++;
                if ($this->isJsonFilled($profile->production_capacity)) $filledFields++;
            }
        }

        if ($totalFields > 0) {
            $this->profile_completion = round(($filledFields / $totalFields) * 100);
        } else {
            $this->profile_completion = 0;
        }

        if ($this->profile_completion < 100) {
            $this->show_completion_modal = true;
        }
    }

    private function isJsonFilled($data)
    {
        if (is_null($data)) return false;

        if (is_array($data)) return count($data) > 0;

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return !empty($decoded);
        }

        return false;
    }

    public function render()
    {
        return view('livewire.member.dashboard');
    }
}