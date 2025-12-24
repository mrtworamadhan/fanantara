<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Models\IndividualProfile;
use App\Models\InstitutionProfile;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            
            $name = $data['type'] === 'individual' ? ($data['full_name'] ?? 'Member') : ($data['company_name'] ?? 'Member');
            
            $user = User::create([
                'name' => $name,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $profile = null;
            
            if ($data['type'] === 'individual') {
                $profile = IndividualProfile::create([
                    'nik' => $data['nik'],
                    'full_name' => $data['full_name'],
                    'phone' => $data['phone'] ?? null,
                ]);
            } else {
                $profile = InstitutionProfile::create([
                    'company_name' => $data['company_name'],
                    'legal_number' => $data['legal_number'],
                    'pic_name' => $data['pic_name'],
                    'pic_phone' => $data['pic_phone'] ?? null,
                ]);
            }

            return static::getModel()::create([
                'user_id' => $user->id,
                'type' => $data['type'],
                'status' => $data['status'],
                'member_number' => 'FNTR-' . time(),
                'profileable_id' => $profile->id,
                'profileable_type' => get_class($profile),
            ]);
        });
    }
}
