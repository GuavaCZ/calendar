<?php

namespace Guava\Calendar\Concerns;

trait HandlesViewMount
{
    protected bool $viewDidMountEnabled = false;

    public function onViewDidMount(array $info = []): void {}

    public function viewDidMount(bool $enabled = true): static
    {
        $this->viewDidMountEnabled = $enabled;

        return $this;
    }

    public function isViewDidMountEnabled(): bool
    {
        return $this->viewDidMountEnabled;
    }
}
