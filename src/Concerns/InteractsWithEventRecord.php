<?php

namespace Guava\Calendar\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Locked;

trait InteractsWithEventRecord
{
    #[Locked]
    public ?Model $eventRecord = null;

    public function getEventRecord(): ?Model
    {
        return $this->eventRecord;
    }

    public function getEventModel(): ?string
    {
        if ($record = $this->getEventRecord()) {
            return $record::class;
        }

        return null;
    }

    protected function resolveEventRecord(): ?Model
    {
        $model = $this->getRawCalendarContextData('event.extendedProps.model');
        $key = $this->getRawCalendarContextData('event.extendedProps.key');

        // Cannot resolve event record
        if (! $model || ! $key) {
            throw new Exception('Could not resolve event record. A [model] or [key] property set in the [extendedProps] of the mounted event was missing.');
        }

        if ($record = $this->resolveEventRecordRouteBinding($model, $key)) {
            return $this->eventRecord = $record;
        }

        throw (new ModelNotFoundException)->setModel($model, [$key]);
    }

    protected function resolveEventRecordRouteBinding(string $model, mixed $key): ?Model
    {
        return app($model)
            ->resolveRouteBindingQuery($this->getEloquentQuery($model), $key, $this->getEventRecordRouteKeyName($model))
            ->first()
        ;
    }

    protected function getEloquentQuery(string $model): Builder
    {
        return app($model)::query();
    }

    protected function getEventRecordRouteKeyName(?string $model = null): ?string
    {
        return null;
    }
}
