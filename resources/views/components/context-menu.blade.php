@php
    use Guava\Calendar\Enums\Context;

    $dateClickContextMenuActions = $this->getCachedDateClickContextMenuActions();
    $dateSelectContextMenuActions = $this->getCachedDateSelectContextMenuActions();
    $eventClickContextMenuActions = $this->getCachedEventClickContextMenuActions();
    $noEventsClickContextMenuActions = $this->getCachedNoEventsClickContextMenuActions();

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
            if(context == @js(Context::DateClick) && @js(empty($dateClickContextMenuActions))
            || context == @js(Context::DateSelect) && @js(empty($dateSelectContextMenuActions))
            || context == @js(Context::EventClick) && @js(empty($eventClickContextMenuActions))
            || context == @js(Context::NoEventsClick) && @js(empty($noEventsClickContextMenuActions))) {
                return 'hidden';
            } else {
                return '';
            }
         "
            @class([
               "fi-dropdown-panel absolute w-screen max-w-xs divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-800 dark:ring-white/10",
           ])
        >
            <div>
                <x-filament::dropdown.list x-show="context == '{{Context::DateClick}}'">
                    @foreach ($dateClickContextMenuActions as $action)
                        {{ $action }}
                    @endforeach
                </x-filament::dropdown.list>
                <x-filament::dropdown.list  x-show="context == '{{Context::DateSelect}}'">
                    @foreach ($dateSelectContextMenuActions as $action)
                        {{ $action }}
                    @endforeach
                </x-filament::dropdown.list>
                <x-filament::dropdown.list  x-show="context == '{{Context::EventClick}}'">
                    @foreach ($eventClickContextMenuActions as $action)
                        {{ $action }}
                    @endforeach
                </x-filament::dropdown.list>
                <x-filament::dropdown.list  x-show="context == '{{Context::NoEventsClick}}'">
                    @foreach ($noEventsClickContextMenuActions as $action)
                        {{ $action }}
                    @endforeach
                </x-filament::dropdown.list>
            </div>
        </div>
    </div>
@endif
