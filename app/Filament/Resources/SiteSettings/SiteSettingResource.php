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
                EditAction::make(),
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
