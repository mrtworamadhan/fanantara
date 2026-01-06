<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string | UnitEnum | null $navigationGroup = 'CMS & Website';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'App Setting';

    protected static ?string $recordTitleAttribute = 'name';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        match ($data['type']) {
            'text' => $data['value_text'] = $data['value'],
            'color' => $data['value_color'] = $data['value'],
            'image' => $data['value_image'] = $data['value'],
            'file' => $data['value_file'] = $data['value'],
            default => null,
        };

        return $data;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    TextInput::make('label')
                        ->label('Nama Setting')
                        ->readOnly(),

                    TextInput::make('key')
                        ->label('Key (System)')
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('value_text')
                        ->label('Isi Nilai')
                        ->dehydrated(false)
                        ->visible(fn (Get $get) => $get('type') === 'text'),

                    ColorPicker::make('value_color')
                        ->label('Pilih Warna')
                        ->format('hex')
                        ->dehydrated(false)
                        ->visible(fn (Get $get) => $get('type') === 'color'),

                    FileUpload::make('value_image')
                        ->label('Upload Gambar')
                        ->image()
                        ->directory('settings')
                        ->disk('public')
                        ->dehydrated(false)
                        ->visible(fn (Get $get) => $get('type') === 'image'),

                    FileUpload::make('value_file')
                        ->label('Upload File PDF Baru')
                        ->acceptedFileTypes(['application/pdf'])
                        ->directory('documents')
                        ->disk('public')
                        ->dehydrated(true)
                        ->helperText(fn ($record) => $record && $record->value 
                            ? new \Illuminate\Support\HtmlString('File saat ini: <a href="' . asset('storage/' . $record->value) . '" target="_blank" class="text-primary-600 hover:underline">' . basename($record->value) . '</a>')
                            : 'Belum ada file yang diupload')
                        ->visible(fn (Get $get) => $get('type') === 'file'),

                    Hidden::make('type'),
                ])->columnSpanFull()
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('label')
                    ->searchable()
                    ->weight('bold'),
                
                TextColumn::make('value')
                    ->formatStateUsing(fn ($state, $record) => match ($record->type) {
                        'image' => '🖼 Gambar',
                        'file' => '📄 File PDF',
                        default => (string) $state,
                    })
                    ->limit(50),


                TextColumn::make('key')
                    ->fontFamily('mono')
                    ->size('sm')
                    ->color('gray')
                    ->copyable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        // Load existing value into appropriate field based on type
                        match ($data['type'] ?? null) {
                            'text', 'textarea' => $data['value_text'] = $data['value'] ?? null,
                            'color' => $data['value_color'] = $data['value'] ?? null,
                            // FileUpload expects array format for existing files
                            'image' => $data['value_image'] = !empty($data['value']) ? [$data['value']] : [],
                            'file' => $data['value_file'] = !empty($data['value']) ? [$data['value']] : [],
                            default => null,
                        };
                        return $data;
                    })
                    ->using(function ($record, array $data) {
                        // Store old value for cleanup
                        $oldValue = $record->value;
                        
                        // Handle different field types
                        if ($record->type === 'file' && isset($data['value_file'])) {
                            // File upload - FileUpload returns an array, get the first element
                            $filePath = is_array($data['value_file']) 
                                ? (reset($data['value_file']) ?: null) 
                                : $data['value_file'];
                            
                            // Only update if a new file was uploaded
                            if ($filePath) {
                                $newPath = 'documents/' . basename($filePath);
                                
                                // Delete old file if it exists and is different from new file
                                if ($oldValue && $oldValue !== $newPath && Storage::disk('public')->exists($oldValue)) {
                                    Storage::disk('public')->delete($oldValue);
                                }
                                
                                $record->value = $newPath;
                            }
                        } elseif ($record->type === 'image' && isset($data['value_image'])) {
                            $imagePath = is_array($data['value_image']) 
                                ? (reset($data['value_image']) ?: null) 
                                : $data['value_image'];
                            
                            if ($imagePath) {
                                $newPath = 'settings/' . basename($imagePath);
                                
                                // Delete old image if it exists and is different from new image
                                if ($oldValue && $oldValue !== $newPath && Storage::disk('public')->exists($oldValue)) {
                                    Storage::disk('public')->delete($oldValue);
                                }
                                
                                $record->value = $newPath;
                            }
                        } elseif ($record->type === 'color' && isset($data['value_color'])) {
                            $record->value = $data['value_color'];
                        } elseif (isset($data['value_text'])) {
                            $record->value = $data['value_text'];
                        }
                        
                        // Save only the value field
                        $record->save();
                        
                        return $record;
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
