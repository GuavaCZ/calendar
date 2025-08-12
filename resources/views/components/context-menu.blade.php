@php
    use Filament\Support\Facades\FilamentAsset;
    use Guava\Calendar\Enums\Context;

    use function Filament\Support\generate_loading_indicator_html;
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
     {{--     x-teleport="body"--}}
     class="absolute top-0 left-0 z-30"
>
    <div x-bind="menu"
         x-transition:enter-start="fi-opacity-0" x-transition:leave-end="fi-opacity-0"
        @class([
           "fi-dropdown-panel absolute w-screen max-w-xs divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-800 dark:ring-white/10",
       ])
    >
        {{--        <div class="w-full flex items-center justify-center p-2">{{generate_loading_indicator_html()}}</div>--}}
        <div wire:loading.flex wire:target="getContextMenuActionsUsing" class="w-full flex items-center justify-center p-2">{{generate_loading_indicator_html()}}</div>
        <x-filament::dropdown.list>
            {{--                    <div x-html="renderedActions"></div>--}}
            <template x-for="action in actions">
                <div x-html="action"></div>
            </template>
            {{--                    @foreach ($dateClickContextMenuActions as $action)--}}
            {{--                        {{ $action }}--}}
            {{--                    @endforeach--}}
        </x-filament::dropdown.list>
        <div>
            {{--                <x-filament::dropdown.list x-show="context == '{{Context::DateClick}}'">--}}
            {{--                    @foreach ($dateClickContextMenuActions as $action)--}}
            {{--                        {{ $action }}--}}
            {{--                    @endforeach--}}
            {{--                </x-filament::dropdown.list>--}}
            {{--                <x-filament::dropdown.list x-show="context == '{{Context::DateSelect}}'">--}}
            {{--                    @foreach ($dateSelectContextMenuActions as $action)--}}
            {{--                        {{ $action }}--}}
            {{--                    @endforeach--}}
            {{--                </x-filament::dropdown.list>--}}
            {{--            <x-filament::dropdown.list>--}}
            {{--                @foreach ($eventClickContextMenuActions as $action)--}}
            {{--                    {{ $action }}--}}
            {{--                @endforeach--}}
            {{--            </x-filament::dropdown.list>--}}
            {{--                <x-filament::dropdown.list x-show="context == '{{Context::NoEventsClick}}'">--}}
            {{--                    @foreach ($noEventsClickContextMenuActions as $action)--}}
            {{--                        {{ $action }}--}}
            {{--                    @endforeach--}}
            {{--                </x-filament::dropdown.list>--}}
        </div>
    </div>
</div>
