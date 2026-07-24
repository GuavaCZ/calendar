<?php

use Guava\Calendar\Concerns\HandlesEventDragAndDrop;
use Guava\Calendar\Concerns\HandlesEventResize;
use Guava\Calendar\Concerns\HasCalendarContextData;
use Guava\Calendar\Concerns\InteractsWithEventRecord;
use Guava\Calendar\Contracts\ContextualInfo;
use Illuminate\Database\Eloquent\Model;

use function Guava\Calendar\calendar_event_signature;

class GatedEventModel extends Model
{
    protected $guarded = [];
}

function makeGatedWidget(bool $dragEnabled, bool $resizeEnabled): object
{
    return new class($dragEnabled, $resizeEnabled)
    {
        use HandlesEventDragAndDrop;
        use HandlesEventResize;
        use HasCalendarContextData;
        use InteractsWithEventRecord;

        public bool $dropHandled = false;

        public bool $resizeHandled = false;

        public function __construct(public bool $dragEnabled, public bool $resizeEnabled) {}

        public function isEventDragEnabled(): bool
        {
            return $this->dragEnabled;
        }

        public function isEventResizeEnabled(): bool
        {
            return $this->resizeEnabled;
        }

        public function shouldUseFilamentTimezone(): bool
        {
            return false;
        }

        public function getCalendarContextInfo(): ?ContextualInfo
        {
            return null;
        }

        protected function resolveEventRecordRouteBinding(string $model, mixed $key): ?Model
        {
            return (new GatedEventModel)->forceFill(['id' => $key]);
        }

        protected function onEventDrop($info = null, $event = null): bool
        {
            $this->dropHandled = true;

            return true;
        }

        protected function onEventResize($info = null, $event = null): bool
        {
            $this->resizeHandled = true;

            return true;
        }
    };
}

function gatedEventData(bool $dragLocked = false, bool $resizeLocked = false): array
{
    $props = [
        'model' => GatedEventModel::class,
        'key' => '1',
        'action' => '',
        'dragLocked' => $dragLocked,
        'resizeLocked' => $resizeLocked,
        'signature' => calendar_event_signature(GatedEventModel::class, '1', '', $dragLocked, $resizeLocked),
    ];

    return ['event' => ['extendedProps' => $props]];
}

it('processes a drag when drag is globally enabled and the event is not locked', function () {
    $widget = makeGatedWidget(dragEnabled: true, resizeEnabled: false);

    expect($widget->onEventDropJs(gatedEventData(dragLocked: false)))->toBeTrue()
        ->and($widget->dropHandled)->toBeTrue()
    ;
});

it('reverts a drag when drag is globally disabled, ignoring the payload', function () {
    $widget = makeGatedWidget(dragEnabled: false, resizeEnabled: false);

    expect($widget->onEventDropJs(gatedEventData(dragLocked: false)))->toBeFalse()
        ->and($widget->dropHandled)->toBeFalse()
    ;
});

it('reverts a drag for a locked event even with a valid signature', function () {
    $widget = makeGatedWidget(dragEnabled: true, resizeEnabled: false);

    expect($widget->onEventDropJs(gatedEventData(dragLocked: true)))->toBeFalse()
        ->and($widget->dropHandled)->toBeFalse()
    ;
});

it('rejects a drag when the lock flag is stripped from the payload', function () {
    $widget = makeGatedWidget(dragEnabled: true, resizeEnabled: false);

    $data = gatedEventData(dragLocked: true);
    $data['event']['extendedProps']['dragLocked'] = false; // tamper: strip the lock, keep the old signature

    expect(fn () => $widget->onEventDropJs($data))
        ->toThrow(Exception::class, 'signature')
    ;
});

it('processes a resize when resize is globally enabled and the event is not locked', function () {
    $widget = makeGatedWidget(dragEnabled: false, resizeEnabled: true);

    expect($widget->onEventResizeJs(gatedEventData(resizeLocked: false)))->toBeTrue()
        ->and($widget->resizeHandled)->toBeTrue()
    ;
});

it('reverts a resize for a locked event even with a valid signature', function () {
    $widget = makeGatedWidget(dragEnabled: false, resizeEnabled: true);

    expect($widget->onEventResizeJs(gatedEventData(resizeLocked: true)))->toBeFalse()
        ->and($widget->resizeHandled)->toBeFalse();
});
