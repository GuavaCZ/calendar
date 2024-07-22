<?php

namespace Guava\Calendar\ValueObjects;

use Guava\Calendar\Contracts\Resourceable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class Resource implements Arrayable, Resourceable
{
    protected int | string $id;

    protected string $title;

    protected ?string $eventBackgroundColor = null;

    protected ?string $eventTextColor = null;

    private function __construct(Model | int | string $id)
    {
        if ($id instanceof Model) {
            $this->id = $id->getKey();
        } else {
            $this->id = $id;
        }
    }

    public function getId(): int | string
    {
        return $this->id;
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    // TODO: Support arrays (such as Color::Rose from Filament) and shade selection (default 400 or 600)
    // TODO: also support filament color names, such as 'primary' or 'danger'
    public function eventBackgroundColor(string $color): static
    {
        $this->eventBackgroundColor = $color;

        return $this;
    }

    public function getEventBackgroundColor(): ?string
    {
        return $this->eventBackgroundColor;
    }

    public function eventTextColor(string $color): static
    {
        $this->eventTextColor = $color;

        return $this;
    }

    public function getEventTextColor(): ?string
    {
        return $this->eventTextColor;
    }

    public static function make(Model | int | string $id): static
    {
        return new static($id);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTitle(),
            'eventBackgroundColor' => $this->getEventBackgroundColor(),
            'eventTextColor' => $this->getEventTextColor(),
        ];
    }

    public function toResource(): array
    {
        return $this->toArray();
    }
}
