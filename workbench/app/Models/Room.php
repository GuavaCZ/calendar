<?php

namespace Workbench\App\Models;

use Guava\Calendar\Contracts\Resourceable;
use Guava\Calendar\ValueObjects\CalendarResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Workbench\Database\Factories\RoomFactory;

/**
 * Backs the resource-based views (resourceTimeGrid*, resourceTimeline*).
 */
class Room extends Model implements Resourceable
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    protected $guarded = [];

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function toCalendarResource(): CalendarResource
    {
        return CalendarResource::make($this)
            ->title($this->name)
            ->eventBackgroundColor($this->color)
        ;
    }

    protected static function newFactory(): RoomFactory
    {
        return RoomFactory::new();
    }
}
