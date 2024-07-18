@php
    use Guava\Calendar\Enums\Context;

    $clickContextMenuActions = $this->getCachedClickContextMenuActions();
    $selectContextMenuActions = $this->getCachedSelectContextMenuActions();
    $hasContextMenu = $this->hasContextMenu();
@endphp

@if($hasContextMenu)

    <div x-ignore
         ax-load
         calendar-context-menu
         ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('calendar-context-menu', 'guava/calendar') }}"
         x-data="calendarContextMenu()"
         class="absolute top-0 left-0 z-30"
    >
        <div x-bind="menu"
             x-bind:class="
            if(context == @js(Context::Click) && @js(empty($clickContextMenuActions))
            || context == @js(Context::Select) && @js(empty($selectContextMenuActions))) {
                return 'hidden';
            } else {
                return '';
            }
         "
            @class([
               "fi-dropdown-panel absolute w-screen max-w-xs divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10",
           ])
        >
            <div>
                    <x-filament::dropdown.list x-show="context == '{{Context::Click}}'">
                        @foreach ($clickContextMenuActions as $action)
                            {{ $action }}
                        @endforeach
                    </x-filament::dropdown.list>
                    <x-filament::dropdown.list  x-show="context == '{{Context::Select}}'">
                        @foreach ($selectContextMenuActions as $action)
                            {{ $action }}
                        @endforeach
                    </x-filament::dropdown.list>
            </div>
        </div>
    </div>
@endif
