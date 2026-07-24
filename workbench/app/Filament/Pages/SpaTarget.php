<?php

namespace Workbench\App\Filament\Pages;

use Filament\Pages\Page;

/**
 * A second, calendar-free page. Navigating Dashboard -> here -> back is the reproduction for
 * issues #17 (calendar duplicates) and #75 (calendar fails to load) when SPA mode is on.
 */
class SpaTarget extends Page
{
    protected static ?string $navigationLabel = 'SPA target';

    protected static ?string $title = 'SPA target';

    protected static ?string $slug = 'spa-target';

    protected string $view = 'workbench::filament.pages.spa-target';
}
