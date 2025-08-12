@php
    use Filament\Support\Facades\FilamentAsset;

    $hasDateClickContextMenu = !empty($this->getCachedDateClickContextMenuActions());
    $hasDateSelectContextMenu = !empty($this->getCachedDateSelectContextMenuActions());
    $hasEventClickContextMenu = !empty($this->getCachedEventClickContextMenuActions());
    $hasNoEventsClickContextMenu = !empty($this->getCachedNoEventsClickContextMenuActions());
    $hasContextMenu = $this->hasContextMenu();
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        :after-header="$this->getCachedHeaderActionsComponent()"
        :footer="$this->getCachedFooterActionsComponent()"
    >

        <style>
            .ec-event.ec-preview,
            .ec-now-indicator {
                z-index: 30;
            }
        </style>

        @if($heading = $this->getHeading())
            <x-slot name="heading">
                {{ $this->getHeading() }}
            </x-slot>
        @endif

        <div
            wire:ignore
            x-load
            x-load-src="{{ FilamentAsset::getAlpineComponentSrc('calendar', 'guava/calendar') }}"
            x-data="calendar({
                view: @js($this->getCalendarView()),
                locale: @js($this->getLocale()),
                firstDay: @js($this->getFirstDay()),
                dayMaxEvents: @js($this->getDayMaxEvents()),
                eventContent: @js($this->getEventContent()),
                eventClickEnabled: @js($this->isEventClickEnabled()),
                eventDragEnabled: @js($this->isEventDragEnabled()),
                eventResizeEnabled: @js($this->isEventResizeEnabled()),
                noEventsClickEnabled: @js($this->isNoEventsClickEnabled()),
                dateClickEnabled: @js($this->isDateClickEnabled()),
                dateSelectEnabled: @js($this->isDateSelectEnabled()),
                datesSetEnabled: @js($this->isDatesSetEnabled()),
                viewDidMountEnabled: @js($this->isViewDidMountEnabled()),
                eventAllUpdatedEnabled: @js($this->isEventAllUpdatedEnabled()),
                hasDateClickContextMenu: @js($hasDateClickContextMenu),
                hasDateSelectContextMenu: @js($hasDateSelectContextMenu),
                hasEventClickContextMenu: @js($hasEventClickContextMenu),
                hasNoEventsClickContextMenu: @js($hasNoEventsClickContextMenu),
                resources: @js($this->getResourcesJs()),
                theme: @js($this->getTheme()),
                options: @js($this->getOptions()),
                eventAssetUrl: @js(FilamentAsset::getAlpineComponentSrc('calendar-event', 'guava/calendar')),
            })"
            @class(\Filament\Support\Facades\FilamentColor::getComponentClasses(\Filament\Support\View\Components\ButtonComponent::class, 'primary'))
        >
            <div data-calendar></div>
            @if($hasContextMenu)
                <x-guava-calendar::context-menu/>
            @endif
        </div>
    </x-filament::section>
        <x-filament-actions::modals/>
</x-filament-widgets::widget>
