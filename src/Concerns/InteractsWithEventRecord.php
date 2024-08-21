<?php

namespace Guava\Calendar\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Locked;

trait InteractsWithEventRecord
{
    #[Locked]
    public ?Model $eventRecord = null;

    /**
     * @deprecated Use getEventRecord() instead.
     */
    public function getRecord(): ?Model
    {
        return $this->getEventRecord();
    }

    public function getEventRecord(): ?Model
    {
        return $this->eventRecord;
    }

    /**
     * @deprecated Use getEventModel() instead.
     */
    public function getModel(): ?string
    {
        return $this->getEventModel();
    }

    public function getEventModel(): ?string
    {
        if ($record = $this->getEventRecord()) {
            return $record::class;
        }

        return null;
    }

    protected function resolveEventRecord(string $model, mixed $key): ?Model
    {
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
