<?php

use Filament\Actions\Action;
use Guava\Calendar\Concerns\HasCalendarContextData;
use Guava\Calendar\Concerns\HasContextMenu;
use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\DateClickInfo;

/**
 * Regression cover for #140: context menu actions mounted with no contextual info, so
 * `mountUsing()` could not read the clicked date.
 *
 * Filament renders an action's `mountAction()` handler from `getInvokedArguments()`, which only
 * `Action::__invoke()` populates — `->arguments()` leaves it null. The context therefore never
 * reached the browser, and on the mount request `getCalendarContextInfo()` had nothing to
 * reconstruct from.
 */
function dateClickPayload(): array
{
    return [
        'date' => '2026-07-28T09:00:00.000Z',
        'allDay' => false,
        'tzOffset' => 0,
        'view' => [
            'type' => 'timeGridWeek',
            'title' => 'Jul 27 – Aug 2, 2026',
            'currentStart' => '2026-07-27T00:00:00.000Z',
            'currentEnd' => '2026-08-03T00:00:00.000Z',
            'activeStart' => '2026-07-27T00:00:00.000Z',
            'activeEnd' => '2026-08-03T00:00:00.000Z',
        ],
    ];
}

/**
 * A minimal host for the two context-menu traits. The real widget pulls these in via
 * InteractsWithCalendar alongside Filament's InteractsWithActions; here only the seams the
 * traits actually reach for are stubbed, keeping the test panel-free like the rest of the suite.
 */
function makeContextMenuHost(array $dateClickActions): object
{
    return new class($dateClickActions)
    {
        use HasCalendarContextData;
        use HasContextMenu;

        public function __construct(protected array $dateClickActions)
        {
            $this->cacheContextMenuActions();
        }

        protected function getDateClickContextMenuActions(): array
        {
            return $this->dateClickActions;
        }

        // Registering actions on the Livewire component is not what these tests exercise; the
        // trait keeps its own map in $cachedContextMenuActions, which is what we assert on.
        protected function cacheAction(Action $action): void {}

        public function shouldUseFilamentTimezone(): bool
        {
            return false;
        }
    };
}

it('renders the clicked context into the action mountAction() handler', function () {
    $action = Action::make('createMeeting');
    $host = makeContextMenuHost([$action]);

    $html = $host->getContextMenuActionsUsing(Context::DateClick, dateClickPayload())->first();

    // Before the fix this rendered as mountAction('createMeeting') with no payload at all.
    expect($html)
        ->toContain('mountAction')
        ->toContain('dateClick')
        ->toContain('2026-07-28T09:00:00')
    ;
});

it('does not mutate the cached action while rendering the menu', function () {
    $action = Action::make('createMeeting');
    $host = makeContextMenuHost([$action]);

    $host->getContextMenuActionsUsing(Context::DateClick, dateClickPayload());

    // Actions are cached once in bootedHasContextMenu() and shared across requests, so one
    // request's context must never stick to them. __invoke() clones for exactly this reason,
    // where the previous ->arguments() call mutated the cached instance in place.
    expect($action->getArguments())->toBe([])
        ->and($action->getInvokedArguments())->toBeNull()
    ;
});

it('rebuilds the contextual info from the mounted action arguments', function () {
    // What happens on the *mount* request: the raw context data is gone (it is a protected
    // property, so Livewire never persisted it) and the info has to come back from the
    // arguments Filament round-tripped through the browser.
    $arguments = [
        'context' => Context::DateClick->value, // a string once it has been through JSON
        'data' => dateClickPayload(),
        'useFilamentTimezone' => false,
    ];

    $host = new class($arguments)
    {
        use HasCalendarContextData;

        public function __construct(protected array $arguments) {}

        public function getMountedAction(): Action
        {
            return Action::make('createMeeting')->arguments($this->arguments);
        }

        public function shouldUseFilamentTimezone(): bool
        {
            return false;
        }
    };

    $info = $host->getCalendarContextInfo();

    expect($info)->toBeInstanceOf(DateClickInfo::class)
        ->and($info->date->format('Y-m-d H:i'))->toBe('2026-07-28 09:00')
        ->and($info->allDay)->toBeFalse()
    ;
});

it('returns no contextual info when nothing was mounted', function () {
    $host = new class
    {
        use HasCalendarContextData;

        public function getMountedAction(): ?Action
        {
            return null;
        }

        public function shouldUseFilamentTimezone(): bool
        {
            return false;
        }
    };

    expect($host->getCalendarContextInfo())->toBeNull();
});
