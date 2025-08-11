<?php

namespace Guava\Calendar\Widgets;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Widgets\Widget;
use Guava\Calendar\Concerns\HandlesDateClick;
use Guava\Calendar\Concerns\HandlesDateSelect;
use Guava\Calendar\Concerns\HandlesDatesSet;
use Guava\Calendar\Concerns\HandlesEventAllUpdated;
use Guava\Calendar\Concerns\HandlesEventClick;
use Guava\Calendar\Concerns\HandlesEventDragAndDrop;
use Guava\Calendar\Concerns\HandlesEventResize;
use Guava\Calendar\Concerns\HandlesNoEventsClick;
use Guava\Calendar\Concerns\HandlesViewMount;
use Guava\Calendar\Concerns\HasCalendarView;
use Guava\Calendar\Concerns\HasContextMenuActions;
use Guava\Calendar\Concerns\HasDayHeaderFormat;
use Guava\Calendar\Concerns\HasDayMaxEvents;
use Guava\Calendar\Concerns\HasDefaultActions;
use Guava\Calendar\Concerns\HasEventContent;
use Guava\Calendar\Concerns\HasEvents;
use Guava\Calendar\Concerns\HasFirstDay;
use Guava\Calendar\Concerns\HasFooterActions;
use Guava\Calendar\Concerns\HasHeaderActions;
use Guava\Calendar\Concerns\HasHeading;
use Guava\Calendar\Concerns\HasLocale;
use Guava\Calendar\Concerns\HasMoreLinkContent;
use Guava\Calendar\Concerns\HasOptions;
use Guava\Calendar\Concerns\HasResourceLabelContent;
use Guava\Calendar\Concerns\HasResources;
use Guava\Calendar\Concerns\HasSchema;
use Guava\Calendar\Concerns\HasSlotLabelFormat;
use Guava\Calendar\Concerns\InteractsWithEventRecord;
use Guava\Calendar\Enums\Context;
use Illuminate\Support\Collection;

class CalendarWidget extends Widget implements HasActions, HasSchemas
{
    use EvaluatesClosures;
    use HandlesDateClick;
    use HandlesDateSelect;
    use HandlesDatesSet;
    use HandlesEventAllUpdated;
    use HandlesEventClick;
    use HandlesEventDragAndDrop;
    use HandlesEventResize;
    use HandlesNoEventsClick;
    use HandlesViewMount;
    use HasCalendarView;
    use HasContextMenuActions;
    use HasDayHeaderFormat;
    use HasDayMaxEvents;
    use HasDefaultActions;
    use HasEventContent;
    use HasEvents;
    use HasFirstDay;
    use HasFooterActions;
    use HasHeaderActions;
    use HasHeading;
    use HasLocale;
    use HasMoreLinkContent;
    use HasOptions;
    use HasResourceLabelContent;
    use HasResources;
    use HasSchema;
    use HasSlotLabelFormat;
    use InteractsWithActions;
    use InteractsWithEventRecord;
    use InteractsWithSchemas;

    protected string $view = 'guava-calendar::widgets.calendar';

    public array $actionContextData = [];

    protected int | string | array $columnSpan = 'full';

    public function refreshRecords(): static
    {
        $this->dispatch('calendar--refresh');

        return $this;
    }

    public function refreshResources(): static
    {
        $this->setOption('resources', $this->getResourcesJs());

        return $this;
    }

    public function setOption(string $key, mixed $value): static
    {
        $this->dispatch('calendar--set', key: $key, value: $value);

        return $this;
    }

    public function getActionsUsing(Context $context, array $data = []): Collection
    {
        $this->setActionContextData($data);

        $actions = match ($context) {
            Context::EventClick => $this->getCachedEventClickContextMenuActions(),
            Context::DateClick => $this->getCachedDateClickContextMenuActions(),
            Context::DateSelect => $this->getCachedDateSelectContextMenuActions(),
            Context::NoEventsClick => $this->getCachedNoEventsClickContextMenuActions()
        };

        return collect($actions)
            ->filter(fn (Action $action) => $action->isVisible())
            ->map(fn (Action $action) => $action->toHtml())
        ;
    }

    protected function setActionContextData(array $data): void
    {
        $this->actionContextData = $data;
    }

    protected function getActionContextData(): array
    {
        return $this->actionContextData;
    }

    public function testing()
    {
        dd('testing on wire!');
    }

    public function getDefaultActionSchemaResolver(Action $action): ?Closure
    {
        return match (true) {
            $action instanceof CreateAction => fn (Schema $schema): Schema => $this->getFormSchemaForModel($schema, $this->getEventModel()),
            $action instanceof EditAction => fn (Schema $schema): Schema => $this->getFormSchemaForModel($schema, $this->getEventModel())
                ->record($this->getEventRecord()),
            $action instanceof ViewAction => fn (Schema $schema): Schema => $this->getInfolistSchemaForModel($schema, $this->getEventModel())
                ->record($this->getEventRecord()),
            default => null,
        };
    }
}
