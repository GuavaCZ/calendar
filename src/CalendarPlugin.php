<?php

namespace Guava\Calendar;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Guava\Calendar\Widgets\CalendarWidget;
use Livewire\Livewire;

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
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
