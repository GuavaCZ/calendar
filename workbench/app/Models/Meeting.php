<?php

namespace Workbench\App\Models;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Workbench\Database\Factories\MeetingFactory;

class Meeting extends Model implements Eventable
{
    /** @use HasFactory<MeetingFactory> */
    use HasFactory;

    protected $guarded = [];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->title($this->title)
            ->start($this->starts_at)
            ->end($this->ends_at)
            ->allDay($this->all_day)
            ->resourceId($this->room_id)
            // `locked` exercises the per-event drag/resize gating covered by
            // tests/Feature/EventEditableGatingTest.php.
            ->editable(! $this->locked)
        ;
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
            'locked' => 'boolean',
        ];
    }

    protected static function newFactory(): MeetingFactory
    {
        return MeetingFactory::new();
    }
}
