<?php

namespace Workbench\App\Providers;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Workbench\App\Filament\Pages\SpaTarget;
use Workbench\App\Filament\Widgets\DemoCalendarWidget;
use Workbench\App\Filament\Widgets\ResourceCalendarWidget;
use Workbench\App\Http\Middleware\AutoLogin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors(['primary' => Color::Amber])
            ->pages([
                Dashboard::class,
                SpaTarget::class,
            ])
            ->widgets([
                DemoCalendarWidget::class,
                ResourceCalendarWidget::class,
            ])
            // SPA mode is the trigger for issues #17 and #75, so it's a flag rather than a
            // fixed setting: WORKBENCH_SPA=1 herd composer start
            ->spa((bool) env('WORKBENCH_SPA', false))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                // AuthenticateSession is deliberately omitted: it expects a real login flow to
                // have recorded the password hash in the session, and logs the AutoLogin user
                // straight back out (redirecting to an undefined `login` route).
                ShareErrorsFromSession::class,
                ConvertEmptyStringsToNull::class,
                DispatchServingFilamentEvent::class,
                DisableBladeIconComponents::class,
                SubstituteBindings::class,
                AutoLogin::class,
            ], isPersistent: true)
        ;
    }
}
