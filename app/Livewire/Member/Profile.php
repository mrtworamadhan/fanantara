<?php

namespace App\Livewire\Member;

use App\Models\Wilayah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class Profile extends Component
{
    use WithFileUploads;

    public $member;
    public $user;
    public $activeTab = 'basic'; 

    public $name, $email, $phone;
    public $address, $province_code, $city_code, $district_code, $village_code;
    
    public $nik, $gender, $place_of_birth, $birth_date, $mother_name;
    
    public $nib, $legal_number, $npwp, $establishment_date;
    public $pic_name, $pic_phone, $pic_position;

    public $job_type, $main_commodity;
    public $prod_lahan, $prod_panen, $prod_siklus;
    public $cons_beras, $cons_minyak, $cons_gula, $cons_pupuk;

    public $supply_chain_role, $total_members, $annual_turnover;
    public $logistics = []; 

    public $existing_ktp, $new_ktp;
    public $existing_npwp_ind, $new_npwp_ind;
    
    public $existing_nib, $new_nib;
    public $existing_ahu, $new_ahu;
    public $existing_npwp_inst, $new_npwp_inst;

    public $full_address;
    public $new_profile_photo;

    #[Layout('components.layouts.app')] 

    public function mount()
    {
        $this->user = Auth::user();
        $this->member = $this->user->member;

        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->address = $this->member->street_address;

        $desa = Wilayah::where('kode', $this->member->village_code)->value('nama');
        $kecamatan = Wilayah::where('kode', $this->member->district_code)->value('nama');
        $kota = Wilayah::where('kode', $this->member->city_code)->value('nama');
        $provinsi = Wilayah::where('kode', $this->member->province_code)->value('nama');
        
        $parts = [];
        if ($this->member->street_address) $parts[] = $this->member->street_address;
        
        $regionParts = [];
        if ($desa) $regionParts[] = Str::title($desa);
        if ($kecamatan) $regionParts[] = Str::title($kecamatan);
        
        $cityParts = [];
        if ($kota) $cityParts[] = Str::title($kota);
        if ($provinsi) $cityParts[] = Str::title($provinsi);

        $addressString = implode(', ', $parts);
        if (!empty($regionParts)) {
            $addressString .= ', ' . implode(' - ', $regionParts);
        }
        if (!empty($cityParts)) {
            $addressString .= ' | ' . implode(' - ', $cityParts);
        }

        $this->full_address = $addressString;

        if ($this->member->type == 'individual') {
            $p = $this->member->individualProfile;

            if (!$p) return; 
            
            $this->nik = $p->nik;
            $this->phone = $p->phone;
            $this->gender = $p->gender;
            $this->place_of_birth = $p->place_of_birth;
            $this->birth_date = $p->birth_date;
            $this->mother_name = $p->mother_name;
            
            $ktpRaw = $p->ktp_image;
            $this->existing_ktp = is_array($ktpRaw) ? ($ktpRaw[0] ?? null) : $ktpRaw;

            $npwpRaw = $p->npwp_image;
            $this->existing_npwp_ind = is_array($npwpRaw) ? ($npwpRaw[0] ?? null) : $npwpRaw;

            $this->job_type = $p->job_type;
            $this->main_commodity = $p->main_commodity;
            
            $prod = $p->production_profile ? (array) $p->production_profile : [];
            $this->prod_lahan = $prod['luas_lahan'] ?? '';
            $this->prod_panen = $prod['estimasi_panen'] ?? '';
            $this->prod_siklus = $prod['siklus_panen'] ?? '';

            $cons = $p->consumption_profile ? (array) $p->consumption_profile : [];
            $this->cons_beras = $cons['beras'] ?? '';
            $this->cons_minyak = $cons['minyak'] ?? '';
            $this->cons_gula = $cons['gula'] ?? '';
            $this->cons_pupuk = $cons['pupuk'] ?? '';

        } else {
            $p = $this->member->institutionProfile;
            
            if (!$p) return;

            $this->nib = $p->nib;
            $this->legal_number = $p->legal_number;
            $this->npwp = $p->npwp;
            $this->establishment_date = $p->establishment_date;
            
            $this->pic_name = $p->pic_name;
            $this->pic_phone = $p->pic_phone;
            $this->pic_position = $p->pic_position;

            $nibRaw = $p->nib_image;
            $this->existing_nib = is_array($nibRaw) ? ($nibRaw[0] ?? null) : $nibRaw;

            $ahuRaw = $p->ahu_image;
            $this->existing_ahu = is_array($ahuRaw) ? ($ahuRaw[0] ?? null) : $ahuRaw;

            $npwpInstRaw = $p->npwp_image;
            $this->existing_npwp_inst = is_array($npwpInstRaw) ? ($npwpInstRaw[0] ?? null) : $npwpInstRaw;

            $this->supply_chain_role = $p->supply_chain_role;
            $this->total_members = $p->total_members;
            $this->annual_turnover = number_format($p->annual_turnover, 0, '', ''); 
            
            $savedLogistics = $p->logistics_capacity ? (array) $p->logistics_capacity : [];
            $formattedLogistics = [];
            
            foreach ($savedLogistics as $key => $val) {
                $formattedLogistics[] = ['key' => $key, 'value' => $val];
            }

            $this->logistics = count($formattedLogistics) > 0 
                ? $formattedLogistics 
                : [['key' => '', 'value' => '']];
        }
    }

    public function addLogistic()
    {
        $this->logistics[] = ['key' => '', 'value' => ''];
    }

    public function removeLogistic($index)
    {
        unset($this->logistics[$index]);
        $this->logistics = array_values($this->logistics);
    }

    public function save()
    {
        $this->validate([
            'address' => 'required|string',
            'new_profile_photo' => 'nullable|image|max:1024',
        ]);

        if ($this->member->type == 'individual') {
            $p = $this->member->individualProfile()->firstOrCreate([]);
            
            $ktpPath = $this->existing_ktp;
            if ($this->new_ktp) $ktpPath = $this->new_ktp->store('members/individuals/ktps', 'public');

            $npwpPath = $this->existing_npwp_ind;
            if ($this->new_npwp_ind) $npwpPath = $this->new_npwp_ind->store('members/individuals/npwps', 'public');

            $p->update([
                'phone' => $this->phone,
                'gender' => $this->gender,
                'place_of_birth' => $this->place_of_birth,
                'birth_date' => $this->birth_date,
                'mother_name' => $this->mother_name,
                'ktp_image' => $ktpPath,
                'npwp_image' => $npwpPath,
                'job_type' => $this->job_type,
                'main_commodity' => $this->main_commodity,
                'production_profile' => [
                    'luas_lahan' => $this->prod_lahan,
                    'estimasi_panen' => $this->prod_panen,
                    'siklus_panen' => $this->prod_siklus,
                ],
                'consumption_profile' => [
                    'beras' => $this->cons_beras,
                    'minyak' => $this->cons_minyak,
                    'gula' => $this->cons_gula,
                    'pupuk' => $this->cons_pupuk,
                ],
            ]);

        } else {
            $p = $this->member->institutionProfile()->firstOrCreate([]);

            $nibPath = $this->existing_nib;
            if ($this->new_nib) $nibPath = $this->new_nib->store('members/institutions/nibs', 'public');

            $ahuPath = $this->existing_ahu;
            if ($this->new_ahu) $ahuPath = $this->new_ahu->store('members/institutions/ahus', 'public');

            $npwpPath = $this->existing_npwp_inst;
            if ($this->new_npwp_inst) $npwpPath = $this->new_npwp_inst->store('members/institutions/npwps', 'public');

            $cleanLogistics = [];
            foreach($this->logistics as $item) {
                if(!empty($item['key'])) {
                    $cleanLogistics[$item['key']] = $item['value'];
                }
            }

            $p->update([
                'nib' => $this->nib,
                'legal_number' => $this->legal_number,
                'npwp' => $this->npwp,
                'establishment_date' => $this->establishment_date,
                'pic_name' => $this->pic_name,
                'pic_phone' => $this->pic_phone,
                'pic_position' => $this->pic_position,
                'nib_image' => $nibPath,
                'ahu_image' => $ahuPath,
                'npwp_image' => $npwpPath,
                'supply_chain_role' => $this->supply_chain_role,
                'total_members' => $this->total_members,
                'annual_turnover' => str_replace(',', '', $this->annual_turnover),
                'logistics_capacity' => $cleanLogistics, 
            ]);
        }
        if ($this->new_profile_photo) {
    
            if ($this->member->type == 'individual') {
                $profile = $this->member->individualProfile()->firstOrCreate([]);
                $column = 'photo'; // Sesuai request kamu: kolom 'photo'
                $folder = 'members/individuals/photos';
            } else {
                $profile = $this->member->institutionProfile()->firstOrCreate([]);
                $column = 'logo'; 
                $folder = 'members/institutions/logos';
            }

            $oldPhoto = $profile->{$column}; // Mengakses property secara dinamis ($profile->photo atau $profile->logo)
            
            if ($oldPhoto) {
                $oldPath = is_array($oldPhoto) ? ($oldPhoto[0] ?? null) : $oldPhoto;
                
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // 3. Simpan Foto Baru
            $newPath = $this->new_profile_photo->store($folder, 'public');

            // 4. Update Kolom Dinamis
            $profile->update([
                $column => $newPath // Update kolom 'photo' atau 'logo'
            ]);
            
            // Reset input upload biar bersih
            $this->reset('new_profile_photo');
        }
        $this->user->update(['name' => $this->name]);
        $this->member->update(['street_address' => $this->address]);

        session()->flash('success', 'Profil berhasil disimpan.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.member.profile');
    }
}