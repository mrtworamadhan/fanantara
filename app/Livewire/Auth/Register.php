<?php

namespace App\Livewire\Auth;

use App\Models\IndividualProfile;
use App\Models\InstitutionProfile;
use App\Models\Member;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class Register extends Component
{
    use WithFileUploads;

    public $currentStep = 1; 
    public $account_type = 'individual'; 

    // Individu
    public $gender = 'm', $phone_individual, $place_of_birth, $birth_date, $mother_name, $job_type;
    
    // Institusi
    public $company_name, $legal_number, $npwp;
    public $pic_name, $pic_phone, $pic_position, $establishment_date;

    // Shared Data
    public $name, $email, $password, $password_confirmation, $identity_no;

    // Alamat
    public $province_code, $city_code, $district_code, $village_code, $street_address;
    
    // Tanda Tangan
    public $digital_signature; 

    // Files
    public $file_ktp, $file_npwp_ind;
    public $file_nib, $file_ahu, $file_npwp_inst;

    // Data Dropdown
    public $provinces = [], $cities = [], $districts = [], $villages = [];
    
    #[Layout('components.layouts.app')] 

    public function mount()
    {
        $this->provinces = Wilayah::provinsi()->pluck('nama', 'kode');
    }

    public function updatedProvinceCode($value)
    {
        $this->cities = Wilayah::kota()->where('kode', 'like', $value . '.%')->pluck('nama', 'kode');
        $this->city_code = null; $this->district_code = null; $this->village_code = null;
    }

    public function updatedCityCode($value)
    {
        $this->districts = Wilayah::kecamatan()->where('kode', 'like', $value . '.%')->pluck('nama', 'kode');
        $this->district_code = null; $this->village_code = null;
    }

    public function updatedDistrictCode($value)
    {
        $this->villages = Wilayah::desa()->where('kode', 'like', $value . '.%')->pluck('nama', 'kode');
        $this->village_code = null;
    }

    public function validationAttributes() 
    {
        return [
            'name' => $this->account_type == 'individual' ? 'Nama Lengkap' : 'Nama Institusi',
            'identity_no' => $this->account_type == 'individual' ? 'NIK' : 'NIB',
            'phone_individual' => 'No HP',
            'pic_name' => 'Nama PIC',
            'pic_phone' => 'No HP PIC',
            'digital_signature' => 'Tanda Tangan',
            'province_code' => 'Provinsi',
            'city_code' => 'Kota/Kab',
            'district_code' => 'Kecamatan',
            'village_code' => 'Kelurahan/Desa',
            'file_ktp' => 'Foto KTP',
            'file_nib' => 'Dokumen NIB',
        ];
    }

    public function rules()
    {
        if ($this->currentStep == 1) {
            return [
                'account_type' => 'required|in:individual,institution',
            ];
        }

        if ($this->currentStep == 2) {
            $rules = [
                // Identitas Dasar
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|same:password_confirmation',
                'identity_no' => $this->account_type == 'individual' ? 'required|digits:16' : 'required',
                
                // Alamat & TTD (Langsung wajib di sini)
                'province_code' => 'required',
                'city_code' => 'required',
                'district_code' => 'required',
                'village_code' => 'required',
                'street_address' => 'required|string|min:10',
                'digital_signature' => 'required', 
            ];

            if ($this->account_type == 'individual') {
                $rules['phone_individual'] = 'required|numeric|min_digits:10';
                $rules['birth_date'] = 'required|date';
                $rules['gender'] = 'required';
                
                $rules['file_ktp'] = 'required|image|max:3072'; 
                $rules['file_npwp_ind'] = 'nullable|image|max:3072'; 
            } else {
                $rules['pic_name'] = 'required|string';
                $rules['pic_phone'] = 'required|numeric';
                $rules['pic_position'] = 'required|string';

                $rules['file_nib'] = 'required|image|max:3072'; 
                $rules['file_ahu'] = 'nullable|image|max:3072'; 
                $rules['file_npwp_inst'] = 'nullable|image|max:3072'; 
            }
            return $rules;
        }

        return [];
    }

    public function nextStep()
    {
        $this->validate();
        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    public function register()
    {
        $this->validate();

        DB::beginTransaction();
        try {

            $image_parts = explode(";base64,", $this->digital_signature);
            $image_base64 = base64_decode($image_parts[1]);
            $signaturePath = 'signatures/' . uniqid() . '.png';
            Storage::disk('public')->put($signaturePath, $image_base64);

            $paths = [];
            if ($this->account_type == 'individual') {
                $paths['ktp'] = $this->file_ktp->store('members/individuals/ktps', 'public');
                $paths['npwp'] = $this->file_npwp_ind ? $this->file_npwp_ind->store('members/individuals/npwps', 'public') : null;
            } else {
                $paths['nib'] = $this->file_nib->store('members/institutions/nibs', 'public');
                $paths['ahu'] = $this->file_ahu ? $this->file_ahu->store('members/institutions/ahus', 'public') : null;
                $paths['npwp_inst'] = $this->file_npwp_inst ? $this->file_npwp_inst->store('members/institutions/npwps', 'public') : null;
            }

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $profile = null;
            $profileType = null;

            if ($this->account_type == 'individual') {
                $profile = IndividualProfile::create([
                    'nik' => $this->identity_no,
                    'full_name' => $this->name,
                    'gender' => $this->gender,
                    'place_of_birth' => $this->place_of_birth,
                    'birth_date' => $this->birth_date,
                    'phone' => $this->phone_individual,
                    'mother_name' => $this->mother_name,
                    'job_type' => $this->job_type,
                    'address_ktp' => $this->street_address, 
                    'ktp_image' => $paths['ktp'],
                    'npwp_image' => $paths['npwp'],
                ]);
                $profileType = IndividualProfile::class;
            } else {
                $profile = InstitutionProfile::create([
                    'company_name' => $this->name,
                    'nib' => $this->identity_no,
                    'legal_number' => $this->legal_number, 
                    'npwp' => $this->npwp,
                    'pic_name' => $this->pic_name,
                    'pic_phone' => $this->pic_phone,
                    'pic_position' => $this->pic_position,
                    'establishment_date' => $this->establishment_date,
                    'address_office' => $this->street_address,
                    'nib_image' => $paths['nib'],
                    'ahu_image' => $paths['ahu'],
                    'npwp_image' => $paths['npwp_inst'],
                ]);
                $profileType = InstitutionProfile::class;
            }

            Member::create([
                'user_id' => $user->id,
                'profileable_id' => $profile->id,
                'profileable_type' => $profileType,
                'type' => $this->account_type,
                'status' => 'pending',
                'join_date' => now(),
                
                'province_code' => $this->province_code,
                'city_code' => $this->city_code,
                'district_code' => $this->district_code,
                'village_code' => $this->village_code,
                'street_address' => $this->street_address,
                
                'digital_signature' => $signaturePath, 
            ]);

            DB::commit();

            Auth::login($user);
            return redirect()->route('member.activation');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('email', 'System Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}