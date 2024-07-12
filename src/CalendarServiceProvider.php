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
use Guava\Calendar\Commands\CalendarCommand;

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
//            ->hasConfigFile()
            ->hasViews()
//            ->hasMigration('create_calendar_table')
//            ->hasCommand(CalendarCommand::class)
        ;
    }

    public function packageBooted()
    {
        Livewire::component('calendar-widget', CalendarWidget::class);

        FilamentAsset::register([
            Css::make('guava-calendar-styles', __DIR__ . '/../node_modules/@event-calendar/core/index.css')
        ]);
        FilamentAsset::register(
            assets: [
                AlpineComponent::make(
                    'calendar-component',
                    __DIR__ . '/../dist/js/calendar-component.js',
                ),
                AlpineComponent::make(
                    'calendar-field',
                    __DIR__ . '/../dist/js/calendar-field.js',
                ),
//                Css::make('calendar-styles', __DIR__ . '/../resources/css/calendar.css'),
                Css::make('calendar-styles', 'https://cdn.jsdelivr.net/npm/@event-calendar/build@3.1.0/event-calendar.min.css'),
                Js::make('calendar-script', 'https://cdn.jsdelivr.net/npm/@event-calendar/build@3.1.0/event-calendar.min.js'),
//                Css::make('calendar-styles', __DIR__ . '/../node_modules/@event-calendar/core/index.css'),
//                Js::make('calendar-script', __DIR__ . '/../node_modules/@event-calendar/core/index.js'),
//                Js::make('calendar-script', __DIR__ . '/../node_modules/@event-calendar/day-grid/index.js'),
//                Js::make('calendar-script', __DIR__ . '/../node_modules/@event-calendar/time-grid/index.js'),
            ],
            package: 'guava/calendar'
        );
    }
}
