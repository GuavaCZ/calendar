@php
    use Filament\Support\Facades\FilamentAsset;
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        :after-header="$this->getCachedHeaderActionsComponent()"
        :footer="$this->getCachedFooterActionsComponent()"
    >
        <x-slot name="heading">
            {{ 'soemthing ' ??$this->getHeading() }}
        </x-slot>

        <div
            wire:ignore
            x-load
            x-load-src="{{ FilamentAsset::getAlpineComponentSrc('calendar', 'guava/calendar') }}"
            x-data="calendar({
                eventAssetUrl: @js(FilamentAsset::getAlpineComponentSrc('calendar-event', 'guava/calendar')),
                eventClickEnabled: @js($this->isEventClickEnabled()),
                eventContent: @js($this->getEventContent()),
            })"
        >
{{--            <div data-calendar></div>--}}
        </div>
    </x-filament::section>
        <x-filament-actions::modals/>
</x-filament-widgets::widget>
