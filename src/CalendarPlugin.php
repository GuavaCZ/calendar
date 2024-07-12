<?php

namespace Guava\Calendar;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class CalendarPlugin implements Plugin
{
    public function getId(): string
    {
        return 'guava-calendar';
    }

    public function register(Panel $panel): void
    {
        // TODO: Implement register() method.
    }

    public function boot(Panel $panel): void
    {
        FilamentAsset::register(
            assets: [
                AlpineComponent::make(
                    'calendar-component',
                    __DIR__ . '/../dist/js/calendar-component.js',
                ),
                Css::make('calendar-styles', 'https://cdn.jsdelivr.net/npm/@event-calendar/build@3.1.0/event-calendar.min.css'),
                Js::make('calendar-script', 'https://cdn.jsdelivr.net/npm/@event-calendar/build@3.1.0/event-calendar.min.js'),
            ],
            package: 'guava/calendar'
        );
    }


    public static function make(): static
    {
        return app(static::class);
    }
}
