<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Guava\Calendar\Contracts\Resourceable;
use Illuminate\Support\Collection;

trait HasResources
{
    protected Collection | array | Closure $resources = [];

    public function resources(Closure | array | Collection $resources): static
    {
        $this->resources = $resources;

        return $this;
    }

    public function getResources(): Collection | array
    {
        return $this->evaluate($this->resources);
    }

    public function getResourcesJs(): array
    {
        return collect($this->getResources())
            ->map(function (array | Resourceable $resource) {
                return match (true) {
                    $resource instanceof Resourceable => $resource->toResource(),
                    default => $resource,
                };
            })
            ->toArray()
        ;
    }
}
