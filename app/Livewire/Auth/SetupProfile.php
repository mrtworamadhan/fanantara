<?php

namespace App\Livewire\Auth;

use App\Models\IndividualProfile;
use App\Models\Member;
use App\Models\Wilayah; // Pastikan model Wilayah ada (atau sesuaikan)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SetupProfile extends Component
{
    use WithFileUploads;

    public $nik;
    public $phone;
    public $address;
    public $ktp_image;

    public $province_code; 
    public $city_code;
    public $district_code;
    public $village_code;

    #[Layout('components.layouts.app')] 

    protected $rules = [
        'nik' => 'required|numeric|digits:16|unique:individual_profiles,nik',
        'phone' => 'required|numeric|min_digits:10',
        'address' => 'required|string|max:255',
        'ktp_image' => 'required|image|max:2048', // Max 2MB
    ];

    public function mount()
    {
        // Cek kalau user sudah punya member aktif, tendang ke dashboard
        if (Auth::user()->member && Auth::user()->member->status === 'active') {
            return redirect()->intended('/dashboard');
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $user = Auth::user();
            
            // 1. Upload KTP
            $ktpPath = $this->ktp_image->store('ktp-images', 'public');

            // 2. Create Individual Profile
            $profile = IndividualProfile::create([
                'nik' => $this->nik,
                'full_name' => $user->name, // Ambil nama dari User/Google
                'ktp_image' => $ktpPath,
            ]);

            // 3. Create Member Record
            $member = Member::create([
                'user_id' => $user->id,
                'profileable_id' => $profile->id,
                'profileable_type' => IndividualProfile::class,
                'type' => 'individual',
                'status' => 'pending', // Status awal Pending
                
                // Address Data
                'street_address' => $this->address,
                'phone' => $this->phone,
                
                // Default Wilayah (Opsional: Nanti diupdate)
                'province_code' => $this->province_code ?? null,
                'city_code' => $this->city_code ?? null,
                
                // Setoran Awal (Nanti diupdate di Payment)
                'is_active' => false,
            ]);

            DB::commit();

            // Redirect ke Step Berikutnya: Pembayaran Simpanan Pokok
            return redirect()->route('member.activation');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('nik', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.setup-profile', ['title' => 'Lengkapi Profil']);
    }
}