<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;

trait HasHeaderActions
{
    protected array $cachedHeaderActions = [];

    public function bootedHasHeaderActions(): void
    {
        $this->cacheHeaderActions();
    }

    protected function cacheHeaderActions() {
        /** @var Action $action */
        foreach ($this->getHeaderActions() as $action) {
            $action->livewire($this);

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
