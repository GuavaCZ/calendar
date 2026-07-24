<?php

namespace Guava\Calendar;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CalendarServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('guava-calendar')
            ->hasTranslations()
            ->hasViews()
        ;
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            assets: [
                AlpineComponent::make(
                    'calendar',
                    __DIR__ . '/../dist/js/calendar.js',
                ),
                AlpineComponent::make(
                    'calendar-context-menu',
                    __DIR__ . '/../dist/js/calendar-context-menu.js',
                ),
                AlpineComponent::make(
                    'calendar-event',
                    __DIR__ . '/../dist/js/calendar-event.js',
                ),
                // The library itself is bundled into the `calendar` Alpine component, so only its
                // stylesheet needs registering here.
                Css::make('calendar-styles', __DIR__ . '/../dist/css/calendar.css'),
            ],
            package: 'guava/calendar'
        );
    }
}
