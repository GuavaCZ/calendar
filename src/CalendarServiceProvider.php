<?php

namespace Guava\Calendar;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Guava\Calendar\Widgets\CalendarWidget;
use Livewire\Livewire;
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

    public function packageBooted()
    {
        Livewire::component('calendar-widget', CalendarWidget::class);

        FilamentAsset::register(
            assets: [
                AlpineComponent::make(
                    'calendar-widget',
                    __DIR__ . '/../dist/js/calendar-widget.js',
                ),
                AlpineComponent::make(
                    'calendar-context-menu',
                    __DIR__ . '/../dist/js/calendar-context-menu.js',
                ),
                Css::make('calendar-styles', 'https://cdn.jsdelivr.net/npm/@event-calendar/build@3.10.0/event-calendar.min.css'),
                Js::make('calendar-script', 'https://cdn.jsdelivr.net/npm/@event-calendar/build@3.10.0/event-calendar.min.js'),
            ],
            package: 'guava/calendar'
        );
    }
}
