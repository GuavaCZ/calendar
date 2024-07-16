<div x-ignore
     ax-load
     calendar-context-menu
     ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('calendar-context-menu', 'guava/calendar') }}"
     x-data="calendarContextMenu({
            })"
     class="absolute top-0 left-0 z-30"
>
    <div x-bind="menu"
         @class([
            "fi-dropdown-panel absolute w-screen max-w-xs divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10",
        ])
    >
            <x-filament::dropdown.list>
                @foreach ($this->getCachedContextMenuActions() as $action)
                    {{ $action }}
                @endforeach
            </x-filament::dropdown.list>

{{--        @foreach($this->getCachedContextMenuActions() as $action)--}}
{{--            <x-filament::dropdown.list.item>--}}

{{--            <div class="flex gap-x-4 select-none group justify-between rounded px-2 py-1.5 hover:bg-neutral-100 outline-none pl-8 data-[disabled]:opacity-50 data-[disabled]:pointer-events-none dark:hover:bg-white/5">--}}
{{--                {{ $action }}--}}
{{--            </x-filament::dropdown.list.item>--}}
{{--            </div>--}}
{{--        @endforeach--}}
    </div>
</div>
