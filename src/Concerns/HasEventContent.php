<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Attributes\CalendarEventContent;
use Illuminate\Contracts\Support\Htmlable;
use ReflectionClass;

trait HasEventContent
{
    public function getEventContentJs(): ?array
    {
        // Try finding a method with a CalendarEventContent attribute
        $reflectionClass = new ReflectionClass($this);

        $views = [];
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(CalendarEventContent::class);

            foreach ($attributes as $attribute) {
                $content = $this->{$method->getName()}();
                $views[$attribute->newInstance()->model] = $content instanceof Htmlable ? $content->toHtml() : $content;
            }
        }

        // Try finding a "defaultEventContent" or "eventContent" method.
        if (method_exists($this, 'defaultEventContent')) {
            $views['_default'] = $this->defaultEventContent();
        }

        // Try finding a "defaultEventContent" or "eventContent" method.
        if (method_exists($this, 'eventContent')) {
            $views['_default'] = $this->eventContent();
        }

        if (! empty($views)) {
            return $views;
        }

        return null;
    }
}
