<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Guava\Calendar\Contracts\Eventable;
use Illuminate\Support\Collection;

trait HasEvents
{
    protected Collection | array | Closure $events = [];

    public function events(Closure | array | Collection $events): static
    {
        $this->events = $events;

        return $this;
    }

    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return $this->evaluate($this->events, [
            'fetchInfo' => $fetchInfo,
        ]);
    }

    public function getEventsJs(array $fetchInfo = []): array
    {
        return collect($this->getEvents($fetchInfo))
            ->map(function (array | Eventable $event) {
                return match (true) {
                    $event instanceof Eventable => $event->toEvent(),
                    default => $event,
                };
            })
            ->toArray()
        ;
    }
}
