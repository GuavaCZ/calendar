<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div>
        <div
            x-ignore
            ax-load
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('calendar-component', 'guava/calendar') }}"
            x-data="calendarField({
                state: $wire.$entangle('{{ $getStatePath() }}'),
            })"
            class="text-center"
        >
            <div id="calendar"></div>
        </div>
    </div>
</x-dynamic-component>

