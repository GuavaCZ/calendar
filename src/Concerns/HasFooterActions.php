<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use InvalidArgumentException;

trait HasFooterActions
{
    protected array $cachedFooterActions = [];

    public function bootedHasFooterActions(): void
    {
        $this->cacheFooterActions();
    }

    protected function cacheFooterActions(): void
    {
        /** @var Action $action */
        foreach ($this->getFooterActions() as $action) {

            if ($action instanceof ActionGroup) {
                $action->livewire($this);

                /** @var array<string, Action> $flatActions */
                $flatActions = $action->getFlatActions();

                $this->mergeCachedActions($flatActions);
                $this->cachedFooterActions[] = $action;

                continue;
            }

            if (! $action instanceof Action) {
                throw new InvalidArgumentException('Footer actions must be an instance of ' . Action::class . ', or ' . ActionGroup::class . '.');
            }

            $this->cacheAction($action);
            $this->cachedFooterActions[] = $action;
        }
    }

    public function getCachedFooterActions(): array
    {
        return $this->cachedFooterActions;
    }

    public function getFooterActions(): array
    {
        return [];
    }
}
