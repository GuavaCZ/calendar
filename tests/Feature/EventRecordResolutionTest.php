<?php

use Guava\Calendar\Concerns\InteractsWithEventRecord;
use Illuminate\Database\Eloquent\Model;

use function Guava\Calendar\calendar_event_signature;

class SignedRecordModel extends Model
{
    protected $guarded = [];
}

function makeRecordResolver(array $extendedProps): object
{
    return new class($extendedProps)
    {
        use InteractsWithEventRecord;

        public function __construct(public array $extendedProps) {}

        public function getRawCalendarContextData(?string $key = null): array | string | null
        {
            return match ($key) {
                'event.extendedProps.model' => $this->extendedProps['model'] ?? null,
                'event.extendedProps.key' => $this->extendedProps['key'] ?? null,
                'event.extendedProps.action' => $this->extendedProps['action'] ?? null,
                'event.extendedProps.signature' => $this->extendedProps['signature'] ?? null,
                default => null,
            };
        }

        // Avoid hitting the database; the tests only care about the tamper checks that run first.
        protected function resolveEventRecordRouteBinding(string $model, mixed $key): ?Model
        {
            return (new SignedRecordModel)->forceFill(['id' => $key]);
        }

        public function resolve(): ?Model
        {
            return $this->resolveEventRecord();
        }
    };
}

function signedProps(string $model, int | string $key, string $action = ''): array
{
    return [
        'model' => $model,
        'key' => $key,
        'action' => $action,
        'signature' => calendar_event_signature($model, $key, $action),
    ];
}

it('resolves a record when the signature is valid', function () {
    $record = makeRecordResolver(signedProps(SignedRecordModel::class, '1', 'view'))->resolve();

    expect($record)->toBeInstanceOf(SignedRecordModel::class);
});

it('rejects a tampered model', function () {
    $props = signedProps(SignedRecordModel::class, '1', 'view');
    $props['model'] = 'App\\Models\\User'; // the stored signature no longer matches

    expect(fn () => makeRecordResolver($props)->resolve())
        ->toThrow(Exception::class, 'signature')
    ;
});

it('rejects a tampered key', function () {
    $props = signedProps(SignedRecordModel::class, '1', 'view');
    $props['key'] = '999';

    expect(fn () => makeRecordResolver($props)->resolve())
        ->toThrow(Exception::class, 'signature')
    ;
});

it('rejects a tampered action', function () {
    $props = signedProps(SignedRecordModel::class, '1', 'view');
    $props['action'] = 'delete';

    expect(fn () => makeRecordResolver($props)->resolve())
        ->toThrow(Exception::class, 'signature')
    ;
});

it('rejects a missing signature', function () {
    $props = signedProps(SignedRecordModel::class, '1', 'view');
    unset($props['signature']);

    expect(fn () => makeRecordResolver($props)->resolve())
        ->toThrow(Exception::class, 'signature')
    ;
});

it('still requires model and key to be present', function () {
    expect(fn () => makeRecordResolver(['key' => 1])->resolve())
        ->toThrow(Exception::class, 'Could not resolve event record')
    ;
});
