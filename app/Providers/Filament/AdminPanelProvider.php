<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('adminajah')
            ->login()
            ->brandName(new HtmlString('
                <div class="flex items-center space-x-1">
                    <div class="relative">
                        <img id="logo-light" src="' . asset('images/black.png') . '" 
                             alt="Logo" 
                             class="h-8 w-auto">
                        <img id="logo-dark" src="' . asset('images/white.png') . '" 
                             alt="Logo" 
                             class="h-8 w-auto hidden">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-extrabold text-primary-500 dark:text-white">SOCCER CLINIC</span>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Admin Dashboard</span>
                    </div>
                </div>
                <script>
                    // Function to update logo based on theme
                    function updateLogo() {
                        const isDark = document.documentElement.classList.contains("dark") || 
                                      document.body.classList.contains("dark") ||
                                      localStorage.getItem("theme") === "dark";
                        
                        const lightLogo = document.getElementById("logo-light");
                        const darkLogo = document.getElementById("logo-dark");
                        
                        if (lightLogo && darkLogo) {
                            if (isDark) {
                                lightLogo.classList.add("hidden");
                                darkLogo.classList.remove("hidden");
                            } else {
                                lightLogo.classList.remove("hidden");
                                darkLogo.classList.add("hidden");
                            }
                        }
                    }
                    
                    // Update logo on page load
                    document.addEventListener("DOMContentLoaded", updateLogo);
                    
                    // Watch for theme changes
                    const observer = new MutationObserver(updateLogo);
                    observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ["class"]
                    });
                    
                    // Also watch for localStorage changes (theme toggle)
                    window.addEventListener("storage", updateLogo);
                </script>
            '))
            ->colors([
                'primary' => Color::Orange,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}