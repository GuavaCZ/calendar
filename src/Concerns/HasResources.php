<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Contracts\Resourceable;
use Guava\Calendar\ValueObjects\CalendarResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasResources
{
    protected function getResources(): Collection | array | Builder
    {
        return [];
    }

    public function getResourcesJs(): array
    {
        $resources = $this->getResources();

        if ($resources instanceof Builder) {
            $resources = $resources->get();
        }

        if (is_array($resources)) {
            $resources = collect($resources);
        }

        return $resources
            ->map(static function (Resourceable | CalendarResource $resource): array {
                if ($resource instanceof Resourceable) {
                    $resource = $resource->toCalendarResource();
                }

                return $resource->toCalendarObject();
            })
            ->toArray()
        ;
    }
}
