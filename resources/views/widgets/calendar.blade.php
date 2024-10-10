@php
    $eventClickEnabled = $this->isEventClickEnabled();
    $eventDragEnabled = $this->isEventDragEnabled();
    $eventResizeEnabled = $this->isEventResizeEnabled();
    $noEventsClickEnabled = $this->isNoEventsClickEnabled();
    $dateClickEnabled = $this->isDateClickEnabled();
    $dateSelectEnabled = $this->isDateSelectEnabled();
    $viewDidMountEnabled = $this->isViewDidMountEnabled();
    $eventAllUpdatedEnabled = $this->isEventAllUpdatedEnabled();
    $onEventResizeStart = method_exists($this, 'onEventResizeStart');
    $onEventResizeStop = method_exists($this, 'onEventResizeStop');
    $hasDateClickContextMenu = !empty($this->getCachedDateClickContextMenuActions());
    $hasDateSelectContextMenu = !empty($this->getCachedDateSelectContextMenuActions());
    $hasEventClickContextMenu = !empty($this->getCachedEventClickContextMenuActions());
    $hasNoEventsClickContextMenu = !empty($this->getCachedNoEventsClickContextMenuActions());

    $dayHeaderFormatJs = $this->getDayHeaderFormatJs();
    $slotLabelFormatJs = $this->getSlotLabelFormatJs();
@endphp

<x-filament-widgets::widget>

    {{--    @push('styles')--}}
    <style>
        .ec {
            --ec-event-bg-color: rgb(var(--primary-600));
            --ec-border-color: rgb(var(--gray-200));
            --ec-button-border-color: rgba(var(--gray-950), 0.1);
            --ec-button-bg-color: rgba(255, 255, 255, 1.0);
            --ec-button-active-bg-color: rgba(var(--gray-50), 1.0);
            --ec-button-active-border-color: var(--ec-button-border-color);

            & .ec-event.ec-preview {
                --ec-event-bg-color: rgb(var(--primary-400));
                z-index: 30;
            }

            & .ec-now-indicator {
                z-index:40;
            }
        }

        .dark .ec {
            --ec-event-bg-color: rgb(var(--primary-500));
            --ec-border-color: rgba(255, 255, 255, 0.10);
            --ec-button-border-color: rgba(var(--gray-600), 1.0);
            --ec-button-bg-color: rgba(255, 255, 255, 0.05);
            --ec-button-active-bg-color: rgba(255, 255, 255, 0.1);
            --ec-button-active-border-color: var(--ec-button-border-color);

            & .ec-event.ec-preview {
                --ec-event-bg-color: rgb(var(--primary-300));
            }
        }
    </style>
    {{--    @endpush--}}
    <x-filament::section
        :header-actions="$this->getCachedHeaderActions()"
        :footer-actions="$this->getCachedFooterActions()"
    >
        <x-slot name="heading">
            {{ $this->getHeading() }}
        </x-slot>

        <div
            wire:ignore
            x-ignore
            ax-load
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('calendar-widget', 'guava/calendar') }}"
            x-data="calendarWidget({
                view: @js($this->getCalendarView()),
                locale: @js($this->getLocale()),
                firstDay: @js($this->getFirstDay()),
                eventContent: @js($this->getEventContentJs()),
                resourceLabelContent: @js($this->getResourceLabelContentJs()),
                eventClickEnabled: @js($eventClickEnabled),
                eventDragEnabled: @js($eventDragEnabled),
                eventResizeEnabled: @js($eventResizeEnabled),
                noEventsClickEnabled: @js($noEventsClickEnabled),
                dateClickEnabled: @js($dateClickEnabled),
                dateSelectEnabled: @js($dateSelectEnabled),
                viewDidMountEnabled: @js($viewDidMountEnabled),
                eventAllUpdatedEnabled: @js($eventAllUpdatedEnabled),
                onEventResizeStart: @js($onEventResizeStart),
                onEventResizeStop: @js($onEventResizeStop),
                dayMaxEvents: @js($this->dayMaxEvents()),
                moreLinkContent: @js($this->getMoreLinkContentJs()),
                resources: @js($this->getResourcesJs()),
                hasDateClickContextMenu: @js($hasDateClickContextMenu),
                hasDateSelectContextMenu: @js($hasDateSelectContextMenu),
                hasEventClickContextMenu: @js($hasEventClickContextMenu),
                hasNoEventsClickContextMenu: @js($hasNoEventsClickContextMenu),
                options: @js($this->getOptions()),
                dayHeaderFormat: {{$dayHeaderFormatJs}},
                slotLabelFormat: {{$slotLabelFormatJs}},
            })"
        >
            <div id="calendar"></div>
            <x-guava-calendar::context-menu/>
        </div>
    </x-filament::section>
    <x-filament-actions::modals/>
</x-filament-widgets::widget>
