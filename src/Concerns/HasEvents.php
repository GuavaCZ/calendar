<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasEvents
{
    abstract public function getEvents(FetchInfo $fetchInfo): Collection | array | Builder;

    public function getEventsJs(array $fetchInfo): array
    {
        $events = $this->getEvents(new FetchInfo($fetchInfo));

        if ($events instanceof Builder) {
            $events = $events->get();
        }

        if (is_array($events)) {
            $events = collect($events);
        }

        return $events
            ->map(function (Eventable $event) {
                return match (true) {
                    $event instanceof Eventable => $event->toCalendarEvent(),
                    default => $event,
                };
            })
            ->toArray()
        ;
    }
}
