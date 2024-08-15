<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Illuminate\Contracts\Support\Htmlable;

trait HasResourceLabelContent
{
    protected null | Closure | string $resourceLabelContent = null;

    public function resourceLabelContent($resourceLabelContent): static
    {
        $this->resourceLabelContent = $resourceLabelContent;

        return $this;
    }

    /**
     * vkurko/calendar doesn't support async method calls in eventContent,
     * that's why we need to pass all views to the client side.
     *
     * @return string|array|null null to use default, string to use single view, array to use multiple views depending on the model of the event.
     */
    public function getResourceLabelContent(): null | string | array
    {
        return $this->evaluate($this->resourceLabelContent);
    }

    public function getResourceLabelContentJs(): null | string | array
    {
        $resourceLabelContent = $this->getResourceLabelContent();

        if (! is_array($resourceLabelContent)) {
            return $resourceLabelContent;
        }

        $result = [];
        foreach ($resourceLabelContent as $model => $content) {
            $result[$model] = $content instanceof Htmlable ? $content->toHtml() : $content;
        }

        return $result;
    }
}
