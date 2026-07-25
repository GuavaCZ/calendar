@php
    use Filament\Support\Facades\FilamentAsset;
    use Guava\Calendar\Enums\Context;
@endphp

<div x-ignore
     x-load
     x-load-src="{{ FilamentAsset::getAlpineComponentSrc('calendar-context-menu', 'guava/calendar') }}"
     x-data="calendarContextMenu({
            getContextMenuActionsUsing: async (context, data) => {
                return await $wire.getContextMenuActionsUsing(context, data)
            },
         })"
     calendar-context-menu
     class="absolute top-0 left-0 z-30"
>
    <div x-bind="menu"
         x-transition:enter-start="fi-opacity-0" x-transition:leave-end="fi-opacity-0"
        @class([
           "fi-dropdown-panel absolute w-screen max-w-xs divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-800 dark:ring-white/10",
       ])
    >
        <x-filament::dropdown.list>
            <template x-for="action in actions">
                <div x-html="action"></div>
            </template>
        </x-filament::dropdown.list>
    </div>
</div>
