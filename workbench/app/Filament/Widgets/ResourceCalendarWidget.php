<?php

namespace Workbench\App\Filament\Widgets;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
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

    public function getEventClickContextMenuActions(): array
    {
        return [
            $this->editAction(),
            $this->deleteAction(),
        ];
    }

    public function meetingSchema(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required(),
            DateTimePicker::make('starts_at')->required()->seconds(false),
            DateTimePicker::make('ends_at')->required()->seconds(false),
        ]);
    }
}
