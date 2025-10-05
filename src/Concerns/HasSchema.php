<?php

namespace Guava\Calendar\Concerns;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Guava\Calendar\Attributes\CalendarSchema;
use Guava\Calendar\Exceptions\SchemaNotFoundException;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

trait HasSchema
{
    /**
     * @throws SchemaNotFoundException
     */
    public function getSchemaForModel(Schema $schema, ?string $model = null): Schema
    {
        // Try finding a method with a ForModel attribute
        $reflectionClass = new ReflectionClass($this);

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC + ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(CalendarSchema::class);

            foreach ($attributes as $attribute) {
                if ($model === $attribute->newInstance()->model) {
                    return $this->{$method->getName()}($schema);
                }
            }
        }

        // Try guessing and finding a method with the correct name (<camelCaseModel>Schema, such as fooBarSchema)
        $methodName = Str::of(class_basename($model))
            ->camel()
            ->append('Schema')
            ->toString()
        ;
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}($schema);
        }

        // Try finding a "defaultSchema" or "schema" method.
        if (method_exists($this, 'defaultSchema')) {
            return $this->defaultSchema($schema);
        }

        if (method_exists($this, 'schema')) {
            return $this->schema($schema);
        }

        throw new SchemaNotFoundException;
    }

    /**
     * @throws SchemaNotFoundException
     */
    public function getFormSchemaForModel(Schema $schema, ?string $model = null): Schema
    {
        try {
            return $this->getSchemaForModel($schema, $model);
        } catch (SchemaNotFoundException $e) {
            // Try to find form schema in resource
            /** @var resource $resource */
            if ($resource = Filament::getModelResource($model)) {
                return $resource::form($schema);
            }

            throw $e;
        }
    }

    /**
     * @throws SchemaNotFoundException
     */
    public function getInfolistSchemaForModel(Schema $schema, ?string $model = null): Schema
    {
        try {
            return $this->getSchemaForModel($schema, $model);
        } catch (SchemaNotFoundException $e) {
            // Try to find infolist schema in resource
            /** @var resource $resource */
            if ($resource = Filament::getModelResource($model)) {
                return $resource::infolist($schema);
            }

            throw $e;
        }
    }
}
