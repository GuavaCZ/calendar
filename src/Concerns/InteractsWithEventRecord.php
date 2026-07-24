<?php

namespace Guava\Calendar\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Locked;

use function Guava\Calendar\calendar_event_signature;

trait InteractsWithEventRecord
{
    #[Locked]
    public ?Model $eventRecord = null;

    public function getEventRecord(): ?Model
    {
        return $this->eventRecord;
    }

    public function setEventRecord(?Model $record): static
    {
        $this->eventRecord = $record;

        return $this;
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

        // The model, key and action round-trip through the browser and can be tampered with, so we
        // must never blindly hand them to app()/query() or mountAction(). Verify the HMAC signature
        // that toCalendarObject() attached: it proves the triple was emitted by the server (i.e. is
        // one of the events this calendar actually rendered) rather than forged by the client.
        $action = $this->getRawCalendarContextData('event.extendedProps.action');
        $signature = $this->getRawCalendarContextData('event.extendedProps.signature');
        $dragLocked = (bool) $this->getRawCalendarContextData('event.extendedProps.dragLocked');
        $resizeLocked = (bool) $this->getRawCalendarContextData('event.extendedProps.resizeLocked');

        if (! is_string($model) || ! is_string($key) || ! is_string($signature)
            || ! hash_equals(calendar_event_signature($model, $key, is_scalar($action) ? (string) $action : '', $dragLocked, $resizeLocked), $signature)) {
            throw new Exception('Could not resolve event record. The event signature is missing or invalid, which means the [model], [key], [action] or editable state in the [extendedProps] was tampered with.');
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
