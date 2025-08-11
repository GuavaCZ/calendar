<?php

namespace Guava\Calendar\Concerns;

use Closure;
use Guava\Calendar\Attributes\CalendarSchema;
use Guava\Calendar\Attributes\EventContent;
use Guava\Calendar\Exceptions\EventContentNotFoundException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use ReflectionClass;

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
        // Try finding a method with a ForModel attribute
        $reflectionClass = new ReflectionClass($this);

        $models = [];
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(EventContent::class);

            foreach ($attributes as $attribute) {
                $content = $this->{$method->getName()}();
                $models[$attribute->newInstance()->model] = $content instanceof Htmlable ? $content->toHtml() : $content;
            }
        }

        if (!empty($models)) {
            return $models;
        }

        return $this->getEventContent();

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

    public function getEventContentForModel(?string $model = null)
    {
        // Try finding a method with a ForModel attribute
        $reflectionClass = new ReflectionClass($this);

        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(EventContent::class);

            foreach ($attributes as $attribute) {
                if ($model === $attribute->newInstance()->model) {
                    return $this->{$method->getName()};
                }
            }
        }

        // Try guessing and finding a method with the correct name (<camelCaseModel>EventContent, such as courseEventContent)
        $methodName = Str::of(class_basename($model))
            ->camel()
            ->append('EventContent')
            ->toString()
        ;
        if (method_exists($this, $methodName)) {
            return $this->{$methodName};
        }

        // Try finding a "defaultEventContent" or "eventContent" method.
        if (method_exists($this, 'defaultEventContent')) {
            return $this->defaultSchema;
        }

        if (method_exists($this, 'eventContent')) {
            return $this->schema;
        }

        throw new EventContentNotFoundException;
    }
}
