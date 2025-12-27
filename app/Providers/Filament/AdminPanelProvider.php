<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->favicon(asset('images/favicon.png'))
            ->brandName('Fanantara')
            // ->brandLogo(asset('images/logo3d.png'))
            // ->brandLogoHeight('10rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // 
            ])
            ->navigationItems([
                NavigationItem::make('Aplikasi Kasir (POS)')
                    ->url('/pos', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-computer-desktop')
                    ->group('Operasional')
                    ->visible(fn () => auth()->user()->hasRole('super_admin')), 
            ])
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Keanggotaan')
                    ->collapsible(false),
                NavigationGroup::make()
                    ->label('Simpanan')
                    ->collapsible(false),
                NavigationGroup::make()
                    ->label('Transaksi')
                    ->collapsible(false),
                
                NavigationGroup::make()
                    ->label('Inventory')
                    ->collapsible(false),    
                NavigationGroup::make()
                    ->label('Keuangan')
                    ->collapsible(false),
                NavigationGroup::make()
                    ->label('Organisasi')
                    ->collapsible(false),    
                NavigationGroup::make()
                    ->label('Settings')
                    ->collapsible(false),
                NavigationGroup::make()
                    ->label('CMS & Website')
                    ->collapsible(false)
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationLabel('Roles Setting')
                    ->navigationGroup('Settings'),  
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
