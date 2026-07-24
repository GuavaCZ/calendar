<?php

namespace Workbench\App\Filament\Widgets;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\DateSelectInfo;
use Guava\Calendar\ValueObjects\EventDropInfo;
use Guava\Calendar\ValueObjects\EventResizeInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Workbench\App\Models\Meeting;
use Workbench\App\Models\Room;

/**
 * The main sandbox widget. Every interactive feature is switched on so that clicking around
 * the panel exercises the full JS <-> PHP round trip: context menus, actions, drag, resize,
 * date click and date select.
 */
class DemoCalendarWidget extends CalendarWidget
{
    protected bool $eventClickEnabled = true;

    protected bool $eventDragEnabled = true;

    protected bool $eventResizeEnabled = true;

    protected bool $dateClickEnabled = true;

    protected bool $dateSelectEnabled = true;

    protected bool $noEventsClickEnabled = true;

    protected ?string $defaultEventClickAction = 'edit';

    protected CalendarViewType $calendarView = CalendarViewType::TimeGridWeek;

    public function getHeading(): string
    {
        return 'Meetings';
    }

    protected function getEvents(FetchInfo $info): Collection | array
    {
        return Meeting::query()
            ->where('starts_at', '>=', $info->start)
            ->where('starts_at', '<=', $info->end)
            ->get()
        ;
    }

    public function getDateClickContextMenuActions(): array
    {
        return [
            $this->createAction(Meeting::class)
                ->mountUsing(fn (Schema $schema) => $schema->fill([
                    'starts_at' => $this->getCalendarContextInfo()?->date,
                    'ends_at' => $this->getCalendarContextInfo()?->date?->copy()->addHour(),
                ])),
        ];
    }

    public function getDateSelectContextMenuActions(): array
    {
        // Mirrors issue #140: the context info must survive into mountUsing().
        return [
            $this->createAction(Meeting::class, 'createFromSelection')
                ->label('Create in selection')
                ->mountUsing(function (Schema $schema) {
                    $info = $this->getCalendarContextInfo();

                    $schema->fill([
                        'starts_at' => $info instanceof DateSelectInfo ? $info->start : null,
                        'ends_at' => $info instanceof DateSelectInfo ? $info->end : null,
                    ]);
                }),
        ];
    }

    public function getEventClickContextMenuActions(): array
    {
        return [
            $this->viewAction(),
            $this->editAction(),
            $this->deleteAction(),
        ];
    }

    public function meetingSchema(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required(),
            Textarea::make('description'),
            Select::make('room_id')
                ->label('Room')
                ->options(fn () => Room::query()->pluck('name', 'id')),
            DateTimePicker::make('starts_at')->required()->seconds(false),
            DateTimePicker::make('ends_at')->required()->seconds(false),
            Toggle::make('all_day'),
            Toggle::make('locked')->helperText('Locked events must revert on drag/resize.'),
        ]);
    }

    protected function onEventDrop(EventDropInfo $info, Model $event): bool
    {
        $event->update([
            'starts_at' => $info->event->getStart(),
            'ends_at' => $info->event->getEnd(),
        ]);

        Notification::make()->title("Moved: {$event->title}")->success()->send();

        return true;
    }

    protected function onEventResize(EventResizeInfo $info, Model $event): bool
    {
        $event->update(['ends_at' => $info->event->getEnd()]);

        Notification::make()->title("Resized: {$event->title}")->success()->send();

        return true;
    }
}
