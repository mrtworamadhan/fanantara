<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun Login')
                    ->description('Data ini digunakan untuk login ke aplikasi.')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->relationship('user', 'email')
                            ->schema([
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(table: 'users', ignoreRecord: true),
                                TextInput::make('password')
                                    ->password()
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create'),
                            ])
                        
                    ])->columnSpanFull(),
                Section::make('Klasifikasi Anggota')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'individual' => 'Perorangan (Individual)',
                                        'institution' => 'Lembaga / Koperasi (Institution)',
                                    ])
                                    ->required()
                                    ->live() 
                                    ->afterStateUpdated(fn (Set $set) => $set('profile_data', [])), 
                                
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending Review',
                                        'active' => 'Active',
                                        'suspended' => 'Suspended',
                                    ])
                                    ->default('pending')
                                    ->required(),
                            ])
                        
                    ])->columnSpanFull(),

                Section::make('Domisili & Lokasi')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('province_code')
                                    ->label('Provinsi')
                                    ->options(fn () => self::getWilayahOptions(2)) // Ambil 2 digit
                                    ->live()
                                    ->searchable()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('city_code', null);
                                        $set('district_code', null);
                                        $set('village_code', null);
                                    }),

                                Select::make('city_code')
                                    ->label('Kota/Kabupaten')
                                    ->options(fn (Get $get) => $get('province_code') ? self::getWilayahOptions(5, $get('province_code')) : [])
                                    ->live()
                                    ->searchable()
                                    ->disabled(fn (Get $get) => !$get('province_code'))
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('district_code', null);
                                        $set('village_code', null);
                                    }),

                                Select::make('district_code')
                                    ->label('Kecamatan')
                                    ->options(fn (Get $get) => $get('city_code') ? self::getWilayahOptions(8, $get('city_code')) : [])
                                    ->live()
                                    ->searchable()
                                    ->disabled(fn (Get $get) => !$get('city_code'))
                                    ->afterStateUpdated(fn (Set $set) => $set('village_code', null)),

                                Select::make('village_code')
                                    ->label('Desa/Kelurahan')
                                    ->options(fn (Get $get) => $get('district_code') ? self::getWilayahOptions(13, $get('district_code')) : [])
                                    ->searchable()
                                    ->disabled(fn (Get $get) => !$get('district_code')),

                                Textarea::make('street_address')
                                    ->label('Alamat Detail')
                                    ->placeholder('Nama Jalan, RT/RW, Patokan')
                                    ->columnSpanFull(),
                            ])
                        
                    ])->columnSpanFull(),

                Section::make('Data Diri (Perorangan)')
                    ->relationship('profileable')
                    ->schema([
                       FileUpload::make('photo') // Pastikan nama field sesuai accessor (photo)
                            ->label('Foto Profil')
                            ->avatar()
                            ->imageEditor()
                            ->alignCenter()
                            ->directory('members/individuals')
                            ->disk('public')
                            ->visibility('public')
                            ->circleCropper()
                            ->columnSpanFull(),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('nik')
                                    ->label('NIK KTP')
                                    ->required()
                                    ->maxLength(16),
                                TextInput::make('full_name')
                                    ->label('Nama Lengkap')
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('No. WhatsApp')
                                    ->tel(),
                                TextInput::make('place_of_birth')
                                    ->label('Tempat Lahir'),    
                                DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir'),
                                Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'm' => 'Laki-laki',
                                        'f' => 'Perempuan',
                                    ])
                                    ->required()
                                    ->native(false),

                                TextInput::make('address_ktp')
                                    ->label('Alamat KTP')
                                    ->required(),
                                TextInput::make('mother_name')
                                    ->label('Nama Ibu Kandung')
                                    ->required(),
                            ]),
                        Grid::make(2)->schema([
                            FileUpload::make('ktp_image')
                                ->label('Foto KTP')
                                ->image()
                                ->directory('members/individuals/ktps')
                                ->visibility('public')
                                ->disk('public')
                                ->formatStateUsing(fn ($state) => !empty($state) ? (is_array($state) ? $state : [$state]) : [])
                                ->dehydrateStateUsing(fn ($state) => (is_array($state) && count($state) > 0) ? array_values($state)[0] : $state),

                            FileUpload::make('npwp_image')
                                ->label('Foto NPWP')
                                ->image()
                                ->directory('members/individuals/npwps')
                                ->visibility('public')
                                ->disk('public')
                                ->formatStateUsing(fn ($state) => !empty($state) ? (is_array($state) ? $state : [$state]) : [])
                                ->dehydrateStateUsing(fn ($state) => (is_array($state) && count($state) > 0) ? array_values($state)[0] : $state),

                        ])
                        
                    ])
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('type') === 'individual'),
                
                Section::make('Profil Ekonomi & Supply Chain')
                    ->relationship('individualProfile')
                    ->schema([
                        Select::make('job_type')
                            ->label('Pekerjaan Utama')
                            ->options([
                                'petani' => 'Petani',
                                'nelayan' => 'Nelayan',
                                'peternak' => 'Peternak',
                                'pedagang' => 'Pedagang/UMKM',
                                'karyawan' => 'Karyawan Swasta/PNS',
                                'lainnya' => 'Lainnya',
                            ])
                            ->live(),

                        TextInput::make('main_commodity')
                            ->label('Komoditas Utama')
                            ->visible(fn (Get $get) => in_array($get('job_type'), ['petani', 'nelayan', 'peternak'])),

                        Fieldset::make('Kapasitas Produksi')
                            ->visible(fn (Get $get) => in_array($get('job_type'), ['petani', 'nelayan', 'peternak']))
                            ->schema([
                                TextInput::make('production_profile.luas_lahan'),
                                TextInput::make('production_profile.estimasi_panen'),
                                TextInput::make('production_profile.siklus_panen'),
                            ])
                            ->columns(3),

                        Fieldset::make('Potensi Belanja Rutin')
                            ->schema([
                                TextInput::make('consumption_profile.beras'),
                                TextInput::make('consumption_profile.minyak'),
                                TextInput::make('consumption_profile.gula'),
                                TextInput::make('consumption_profile.pupuk')
                                    ->visible(fn (Get $get) => $get('job_type') === 'petani'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('type') === 'individual'),

                
                Section::make('Data Lembaga / Koperasi')
                    ->relationship('profileable')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->avatar()
                            ->imageEditor()
                            ->image()
                            ->directory('members/institutions')
                            ->disk('public')
                            ->visibility('public')
                            ->circleCropper()
                            ->formatStateUsing(fn ($state) => $state ? (is_array($state) ? $state : [$state]) : [])
                            ->dehydrateStateUsing(fn ($state) => (is_array($state) && count($state) > 0) ? array_values($state)[0] : $state)
                            ->alignCenter()
                            ->columnSpanFull(),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('company_name')
                                    ->label('Nama Koperasi / PT')
                                    ->required(),
                                TextInput::make('pic_name')
                                    ->label('Nama Penanggung Jawab (PIC)')
                                    ->required(),
                                TextInput::make('pic_phone')
                                    ->label('No. HP PIC')
                                    ->tel(),
                                TextInput::make('address_office')
                                    ->label('Alamat Kantor')
                                    ->required(),
                            ]),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('legal_number')
                                    ->label('No. SK Kemenkumham / AHU')
                                    ->required(),

                                FileUpload::make('ahu_image')
                                    ->label('AHU')
                                    ->imageEditor()
                                    ->directory('members/institutions/ahus')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->formatStateUsing(fn ($state) => $state ? (is_array($state) ? $state : [$state]) : [])
                                    ->dehydrateStateUsing(fn ($state) => (is_array($state) && count($state) > 0) ? array_values($state)[0] : $state),

                                TextInput::make('nib')
                                    ->numeric()
                                    ->label('NIB')
                                    ->required(),
                                
                                FileUpload::make('nib_image')
                                    ->label('NIB')
                                    ->imageEditor()
                                    ->directory('members/institutions/nibs')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->formatStateUsing(fn ($state) => $state ? (is_array($state) ? $state : [$state]) : [])
                                    ->dehydrateStateUsing(fn ($state) => (is_array($state) && count($state) > 0) ? array_values($state)[0] : $state),
                                
                                TextInput::make('npwp')
                                    ->numeric()
                                    ->label('NPWP')
                                    ->required(),
                                
                                FileUpload::make('npwp_image')
                                    ->label('NPWP')
                                    ->imageEditor()
                                    ->directory('members/institutions/npwps')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->formatStateUsing(fn ($state) => $state ? (is_array($state) ? $state : [$state]) : [])
                                    ->dehydrateStateUsing(fn ($state) => (is_array($state) && count($state) > 0) ? array_values($state)[0] : $state),
                                
                            ])
                        
                    ])
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('type') === 'institution'),

                    Section::make('Kapasitas Bisnis & Logistik')
                        ->relationship('institutionProfile')
                        ->visible(fn (Get $get) => $get('type') === 'institution')
                        ->schema([
                            Select::make('supply_chain_role')
                                ->label('Peran dalam Rantai Pasok')
                                ->options([
                                    'produsen' => 'Produsen (Pabrik)',
                                    'distributor' => 'Distributor / Agen',
                                    'retailer' => 'Retailer / Toko',
                                    'logistik' => 'Jasa Logistik / Transporter',
                                ]),
                            
                            TextInput::make('total_members')
                                ->label('Jumlah Anggota Binaan')
                                ->numeric(),

                            TextInput::make('annual_turnover')
                                ->label('Omset Tahunan')
                                ->prefix('Rp')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(','),

                            // KeyValue untuk data dinamis JSON
                            KeyValue::make('logistics_capacity')
                                ->label('Aset Logistik')
                                ->keyLabel('Jenis Aset (Gudang/Truk)')
                                ->valueLabel('Kapasitas (Ton/Unit)')
                                ->addButtonLabel('Tambah Aset'),
                        ])
                    ->columnSpanFull()
                    ->visible(fn (Get $get) => $get('type') === 'institution'),
            ]);
    }

    public static function getWilayahOptions($length, $parentCode = null)
    {
        $query = \App\Models\Wilayah::query()
            ->whereRaw("CHAR_LENGTH(kode) = ?", [$length]);

        if ($parentCode) {
            $query->where('kode', 'like', $parentCode . '.%');
        }

        return $query->pluck('nama', 'kode');
    }
}
