<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\Event;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

trait HasEvents
{
    protected Collection | array | Closure $events = [];

    public function events(Closure | array | Collection $events): static
    {
        $this->events = $events;

        return $this;
    }

    public function getEvents(): Collection | array
    {
        return $this->evaluate($this->events);
    }

    public function getEventsJs(): array
    {
        return collect($this->getEvents())
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
