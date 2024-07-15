<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Illuminate\Contracts\Support\Htmlable;

trait HasEventContent
{
    protected null | Closure | string $eventContent = null;

    public function eventContent($eventContent): static
    {
        $this->eventContent = $eventContent;

        return $this;
    }

    /**
     * vkurko/calendar doesn't support async method calls in eventContent,
     * that's why we need to pass all views to the client side.
     *
     * @return string|array|null null to use default, string to use single view, array to use multiple views depending on the model of the event.
     */
    public function getEventContent(): null | string | array
    {
        return $this->evaluate($this->eventContent);
    }

    public function getEventContentJs(): null | string | array
    {
        $eventContent = $this->getEventContent();

        if (! is_array($eventContent)) {
            return $eventContent;
        }

        $result = [];
        foreach ($eventContent as $model => $content) {
            $result[$model] = $content instanceof Htmlable ? $content->toHtml() : $content;
        }

        return $result;
    }
}
