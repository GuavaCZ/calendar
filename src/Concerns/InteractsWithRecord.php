<?php

namespace Guava\Calendar\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Locked;

trait InteractsWithRecord
{
    #[Locked]
    public ?Model $record = null;

    public function getRecord(): ?Model
    {
        return $this->record;
    }

    public function getModel(): ?string
    {
        if ($record = $this->getRecord()) {
            return $record::class;
        }

        return null;
    }

    protected function resolveRecord(string $model, mixed $key): ?Model
    {
        if ($record = $this->resolveRecordRouteBinding($model, $key)) {
            return $this->record = $record;
        }

        throw (new ModelNotFoundException())->setModel($model, [$key]);
    }

    protected function resolveRecordRouteBinding(string $model, mixed $key): ?Model
    {
        return app($model)
            ->resolveRouteBindingQuery($this->getEloquentQuery($model), $key, $this->getRecordRouteKeyName($model))
            ->first()
        ;
    }

    protected function getEloquentQuery(string $model): Builder
    {
        return app($model)::query();
    }

    protected function getRecordRouteKeyName(?string $model = null): ?string
    {
        return null;
    }
}
