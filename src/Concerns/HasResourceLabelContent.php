<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Attributes\CalendarResourceLabelContent;
use Illuminate\Contracts\Support\Htmlable;
use ReflectionClass;

trait HasResourceLabelContent
{
    public function getResourceLabelContentJs(): ?array
    {
        // Try finding a method with a CalendarEventContent attribute
        $reflectionClass = new ReflectionClass($this);

        $views = [];
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(CalendarResourceLabelContent::class);

            foreach ($attributes as $attribute) {
                $content = $this->{$method->getName()}();
                $views[$attribute->newInstance()->model] = $content instanceof Htmlable ? $content->toHtml() : $content;
            }
        }

        // Try finding a "defaultResourceLabelContent" or "resourceLabelContent" method.
        if (method_exists($this, 'defaultResourceLabelContent')) {
            $views['_default'] = $this->defaultResourceLabelContent();
        }

        // Try finding a "defaultResourceLabelContent" or "resourceLabelContent" method.
        if (method_exists($this, 'resourceLabelContent')) {
            $views['_default'] = $this->resourceLabelContent();
        }

        if (! empty($views)) {
            return $views;
        }

        return null;
    }
}
