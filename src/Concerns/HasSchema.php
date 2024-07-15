<?php

namespace Guava\Calendar\Concerns;

use Closure;

trait HasSchema
{
    protected null | Closure | array $schema = null;

    public function schema(array | Closure $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    public function getSchema(?string $model = null): ?array
    {
        return $this->evaluate($this->schema, [
            'model' => $model,
        ]);
    }
}
