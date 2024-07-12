<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Illuminate\View\View;

trait HasSchema
{
    protected null | Closure | array $schema = null;

    public function schema(array | Closure $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * vkurko/calendar doesn't support async method calls in eventContent,
     * that's why we need to pass all views to the client side.
     *
     * @return array|null null to use default, string to use single view, array to use multiple views depending on the model of the event.
     */
    public function getSchema(?string $model = null): ?array
    {
        return $this->evaluate($this->schema, [
            'model' => $model,
        ]);
    }

    public function getSchemaForModel(?string $model = null): ?array
    {
        $schema = $this->getSchema($model);
        $schema = data_get($schema, $model, $schema);

        return $schema
            ? collect($schema)
                ->filter(fn ($component) => ! is_array($component))
                ->all()
            : null;
    }
}
