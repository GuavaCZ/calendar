@php
    $onEventClick = method_exists($this, 'onEventClick');
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
        }

        .dark .ec {
            --ec-event-bg-color: rgb(var(--primary-500));
            --ec-border-color: rgba(255, 255, 255, 0.10);
            --ec-button-border-color: rgba(var(--gray-600), 1.0);
            --ec-button-bg-color: rgba(255, 255, 255, 0.05);
            --ec-button-active-bg-color: rgba(255, 255, 255, 0.1);
            --ec-button-active-border-color: var(--ec-button-border-color);
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
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('calendar-component', 'guava/calendar') }}"
            x-data="calendarComponent({
                view: @js($this->getCalendarView()),
                locale: @js($this->getLocale()),
                firstDay: @js($this->getFirstDay()),
                eventContent: @js($this->getEventContentJs()),
                onEventClick: @js($onEventClick),
                dayMaxEvents: @js($this->dayMaxEvents()),
                moreLinkContent: @js($this->getMoreLinkContentJs()),
                resources: @js($this->getResourcesJs()),
                options: @js($this->getOptions()),
            })"
        >
            <div id="calendar"></div>
        </div>
    </x-filament::section>
    <x-filament-actions::modals/>
</x-filament-widgets::widget>
