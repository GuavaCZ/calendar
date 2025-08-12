<?php

namespace Guava\Calendar\Concerns;

use Filament\Support\Facades\FilamentColor;
use Filament\Support\View\Components\ButtonComponent;
use Guava\Calendar\Filament\Theme\EventThemeComponent;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasTheme
{
    public function getTheme(): ?array
    {
        return [];
    }
}
