<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use InvalidArgumentException;

trait HasContextMenuActions
{
    protected array $cachedContextMenuActions = [];

    public function bootedHasContextMenuActions(): void
    {
        $this->cacheContextMenuActions();
    }

    protected function cacheContextMenuActions(): void
    {
        /** @var Action $action */
        foreach ($this->getContextMenuActions() as $action) {
            $action = $action->view(Action::GROUPED_VIEW);

            if (! $action instanceof Action) {
                throw new InvalidArgumentException('Context menu actions must be an instance of ' . Action::class . '.');
            }

            $this->cacheAction($action);
            $this->cachedContextMenuActions[] = $action;
        }
    }

    public function getCachedContextMenuActions(): array
    {
        return $this->cachedContextMenuActions;
    }

    public function getContextMenuActions(): array
    {
        return [];
    }
}
