<?php

namespace Guava\Calendar;

use Filament\Contracts\Plugin;
use Filament\Panel;

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

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
