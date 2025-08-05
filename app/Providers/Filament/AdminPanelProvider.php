<?php

namespace App\Providers\Filament;

use Config;
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

class AdminPanelProvider extends PanelProvider
{
    private array $dynamicWidgets = [];

    public function panel(Panel $panel): Panel
    {
        $this->renderAnalyticsIfCredentialsExist();

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css', 'build/filament')
            ->colors([
                'primary' => Color::Violet,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,

                //                StatsOverview::class,
                //                LatestUsers::class,

                ...$this->dynamicWidgets,

                // Google Analytics Widgets that are available here: https://filamentphp.com/plugins/bezhansalleh-google-analytics
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\PageViewsWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\VisitorsWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersOneDayWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersSevenDayWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersTwentyEightDayWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsDurationWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsByCountryWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsByDeviceWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\MostVisitedPagesWidget::class,
                //                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\TopReferrersListWidget::class,

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

    // This function will render if user has account crenditals file
    // located at storage/app/analytics/service-account-credentials.json
    // Find More details here: https://github.com/spatie/laravel-analytics
    private function renderAnalyticsIfCredentialsExist(): void
    {
        if (file_exists(storage_path('app/analytics/service-account-credentials.json'))) {
            Config::set('filament-google-analytics.page_views.filament_dashboard', true);
            Config::set('filament-google-analytics.active_users_one_day.filament_dashboard', true);
            Config::set('filament-google-analytics.active_users_seven_day.filament_dashboard', true);
            Config::set('filament-google-analytics.active_users_twenty_eight_day.filament_dashboard', true);
            Config::set('filament-google-analytics.most_visited_pages.filament_dashboard', true);
            Config::set('filament-google-analytics.top_referrers_list.filament_dashboard', true);
        }

        //        else {
        //            $this->dynamicWidgets = [Analytics::class];
        //        }
    }
}
