<?php

namespace Guava\Calendar\Concerns;

trait HasMountedActionContextData
{
    protected array $mountedActionContextData = [];

    protected function setMountedActionContextData(array $data): void
    {
        $this->mountedActionContextData = $data;
    }

    protected function getMountedActionContextData(?string $key = null): array|string|null
    {
        if ($key) {
            return data_get($this->mountedActionContextData, $key);
        }

        return $this->mountedActionContextData;
    }
}
