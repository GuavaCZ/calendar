<?php

namespace Workbench\App\Filament\Widgets;

use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;
use Workbench\App\Models\Meeting;
use Workbench\App\Models\Room;

/**
 * Covers the resource-backed views, which the plain widget above never renders. Two calendars
 * on one page is also the reproduction for issue #37 (second calendar not refreshing).
 */
class ResourceCalendarWidget extends CalendarWidget
{
    protected bool $eventClickEnabled = true;

    protected bool $eventDragEnabled = true;

    protected CalendarViewType $calendarView = CalendarViewType::ResourceTimelineWeek;

    public function getHeading(): string
    {
        return 'Rooms (resource view)';
    }

    protected function getEvents(FetchInfo $info): Collection | array
    {
        return Meeting::query()
            ->whereBetween('starts_at', [$info->start, $info->end])
            ->get()
        ;
    }

    protected function getResources(): Collection | array
    {
        return Room::query()->get();
    }
}
