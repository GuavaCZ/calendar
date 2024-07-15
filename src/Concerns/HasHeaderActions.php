<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use InvalidArgumentException;

trait HasHeaderActions
{
    protected array $cachedHeaderActions = [];

    public function bootedHasHeaderActions(): void
    {
        $this->cacheHeaderActions();
    }

    protected function cacheHeaderActions(): void
    {
        /** @var Action $action */
        foreach ($this->getHeaderActions() as $action) {
            if ($action instanceof ActionGroup) {
                $action->livewire($this);

                /** @var array<string, Action> $flatActions */
                $flatActions = $action->getFlatActions();

                $this->mergeCachedActions($flatActions);
                $this->cachedHeaderActions[] = $action;

                continue;
            }

            if (! $action instanceof Action) {
                throw new InvalidArgumentException('Header actions must be an instance of ' . Action::class . ', or ' . ActionGroup::class . '.');
            }

            $this->cacheAction($action);
            $this->cachedHeaderActions[] = $action;
        }
    }

    public function getCachedHeaderActions(): array
    {
        return $this->cachedHeaderActions;
    }

    public function getHeaderActions(): array
    {
        return [];
    }
}
