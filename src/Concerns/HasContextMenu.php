<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Guava\Calendar\Enums\Context;
use Illuminate\Support\Collection;
use InvalidArgumentException;

trait HasContextMenu
{
    protected array $cachedContextMenuActions = [];

    public function bootedHasContextMenu(): void
    {
        $this->cacheContextMenuActions();
    }

    public function getContextMenuActionsUsing(Context $context, array $data = []): Collection
    {
        $this->setRawCalendarContextData($context, $data);

        $actions = match ($context) {
            Context::EventClick => $this->getCachedEventClickContextMenuActions(),
            Context::DateClick => $this->getCachedDateClickContextMenuActions(),
            Context::DateSelect => $this->getCachedDateSelectContextMenuActions(),
            Context::NoEventsClick => $this->getCachedNoEventsClickContextMenuActions()
        };

        return collect($actions)
            ->filter(fn (Action $action) => $action->isVisible())
            ->map(
                fn (Action $action) => $action
                    ->arguments($this->getRawCalendarContextData())
                    ->toHtml()
            )
        ;
    }

    public function hasContextMenu(?Context $context = null): bool
    {
        return match ($context) {
            Context::DateClick => ! empty($this->getCachedDateClickContextMenuActions()),
            Context::DateSelect => ! empty($this->getCachedDateSelectContextMenuActions()),
            Context::EventClick => ! empty($this->getCachedEventClickContextMenuActions()),
            Context::NoEventsClick => ! empty($this->getCachedNoEventsClickContextMenuActions()),
            default => ! empty($this->getCachedContextMenuActions()),
        };
    }

    protected function cacheContextMenuActions(): void
    {
        foreach ($this->getDateClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::DateClick);
        }
        foreach ($this->getDateSelectContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::DateSelect);
        }
        foreach ($this->getEventClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::EventClick);
        }
        foreach ($this->getNoEventsClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::NoEventsClick);
        }
    }

    protected function getCachedContextMenuActions(): array
    {
        return $this->cachedContextMenuActions;
    }

    protected function getDateClickContextMenuActions(): array
    {
        return [];
    }

    protected function getDateSelectContextMenuActions(): array
    {
        return [];
    }

    protected function getEventClickContextMenuActions(): array
    {
        return [];
    }

    protected function getNoEventsClickContextMenuActions(): array
    {
        return [];
    }

    protected function getCachedDateClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::DateClick->value, []);
    }

    protected function getCachedDateSelectContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::DateSelect->value, []);
    }

    protected function getCachedEventClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::EventClick->value, []);
    }

    protected function getCachedNoEventsClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::NoEventsClick->value, []);
    }

    private function cacheContextMenuAction(Action $action, Context $context): void
    {
        $action = $action
            ->grouped()
        ;

        if (! $action instanceof Action) {
            throw new InvalidArgumentException('Context menu actions must be an instance of ' . Action::class . '.');
        }

        $this->cacheAction($action);
        $cachedActions = data_get($this->cachedContextMenuActions, $context->value, []);
        $this->cachedContextMenuActions[$context->value] = [
            ...$cachedActions,
            $action,
        ];
    }
}
