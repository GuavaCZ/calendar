<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Support\Concerns\EvaluatesClosures;

trait InteractsWithCalendar
{
    use CanRefreshCalendar;
    use CanUseFilamentTimezone;
    use EvaluatesClosures;
    use HandlesDateClick;
    use HandlesDateSelect;
    use HandlesDatesSet;
    use HandlesEventAllUpdated;
    use HandlesEventClick;
    use HandlesEventDragAndDrop;
    use HandlesEventResize;
    use HandlesNoEventsClick;
    use HandlesViewDidMount;
    use HasCalendarContextData;
    use HasCalendarView;
    use HasContextMenu;
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
    use HasTheme;
    use InteractsWithActions {
        InteractsWithActions::mountAction as baseMountAction;
    }
    use InteractsWithEventRecord;
    use InteractsWithSchemas;

    public function mountAction(string $name, array $arguments = [], array $context = []): mixed
    {
        return $this->baseMountAction($name, [
            ...$arguments,
            ...$this->getRawCalendarContextData() ?? [],
        ], $context);
    }
}
