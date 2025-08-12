<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Support\Concerns\EvaluatesClosures;
use Guava\Calendar\Contracts\ContextualInfo;

trait InteractsWithCalendar
{
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
    use HasAuthorization;
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
    use HasMountedActionContextData;
    use HasNotifications;
    use HasOptions;
    use HasSchema;
    use InteractsWithActions;
    use InteractsWithEventRecord;
    use InteractsWithSchemas;
    use CanRefreshCalendar;
    use HasTheme;
    use HasResources;

    public function mountCalendarAction(string $name, ContextualInfo $info): void
    {
        $this->mountAction($name, [
            'context' => $info->getContext()->value,
            'data' => $info->getOriginalData(),
            'useFilamentTimezone' => $this->shouldUseFilamentTimezone(),
        ]);
    }

    //    public function getDefaultActionSchemaResolver(Action $action): ?Closure
    //    {
    //        return match (true) {
    //            $action instanceof CreateAction => fn (Schema $schema, $action): Schema => $this->getFormSchemaForModel($schema, $action->getModel()),
    //            $action instanceof EditAction => fn (Schema $schema, $action): Schema => $this->getFormSchemaForModel($schema, $action->getModel())
    //                ->record($this->getEventRecord()),
    //            $action instanceof ViewAction => fn (Schema $schema, $action): Schema => $this->getInfolistSchemaForModel($schema, $action->getModel())
    //                ->record($this->getEventRecord()),
    //            default => null,
    //        };
    //    }

}
