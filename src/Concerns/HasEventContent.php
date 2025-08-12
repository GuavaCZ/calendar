<?php

namespace Guava\Calendar\Concerns;

use Guava\Calendar\Attributes\EventContentForModel;
use Guava\Calendar\Exceptions\EventContentNotFoundException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use ReflectionClass;

trait HasEventContent
{
    public function getEventContent(): null | string | array
    {
        // Try finding a method with a ForModel attribute
        $reflectionClass = new ReflectionClass($this);

        $models = [];
        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(EventContentForModel::class);

            foreach ($attributes as $attribute) {
                $content = $this->{$method->getName()}();
                $models[$attribute->newInstance()->model] = $content instanceof Htmlable ? $content->toHtml() : $content;
            }
        }

        if (! empty($models)) {
            return $models;
        }

        // Try finding a "defaultEventContent" or "eventContent" method.
        if (method_exists($this, 'defaultEventContent')) {
            return $this->defaultEventContent();
        }

        if (method_exists($this, 'eventContent')) {
            return $this->eventContent();
        }

        return null;
    }

    public function getEventContentForModel(?string $model = null)
    {
        // Try finding a method with a ForModel attribute
        $reflectionClass = new ReflectionClass($this);

        foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC + \ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(EventContentForModel::class);

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
