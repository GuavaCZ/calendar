<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasEvents
{
    abstract protected function getEvents(FetchInfo $info): Collection | array | Builder;

    public function getEventsJs(array $info): array
    {
        $events = $this->getEvents(new FetchInfo($info));

        if ($events instanceof Builder) {
            $events = $events->get();
        }

        if (is_array($events)) {
            $events = collect($events);
        }

        return $events
            ->map(function (Builder | Collection | Eventable | CalendarEvent $event) use ($info): array {
                if ($event instanceof Eventable) {
                    $event = $event->toCalendarEvent();
                }

                return $event->toCalendarObject(
                    data_get($info, 'tzOffset'),
                    $this->shouldUseFilamentTimezone()
                );
            })
            ->all()
        ;
    }
}
