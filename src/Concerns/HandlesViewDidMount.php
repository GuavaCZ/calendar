<?php

namespace Guava\Calendar\Concerns;

trait HandlesViewDidMount
{
    protected bool $viewDidMountEnabled = false;

    public function onViewDidMount(array $info = []): void {}

    public function isViewDidMountEnabled(): bool
    {
        return $this->viewDidMountEnabled;
    }
}
