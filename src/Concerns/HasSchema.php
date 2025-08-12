<?php

namespace Guava\Calendar\Concerns;

use Exception;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Guava\Calendar\Attributes\SchemaForModel;
use Guava\Calendar\Exceptions\SchemaNotFoundException;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

trait HasSchema
{
    /**
     * @throws SchemaNotFoundException
     */
    public function getSchemaForModel(?string $model = null): Schema
    {
        // Try finding a method with a SchemaForModel attribute
        $reflectionClass = new ReflectionClass($this);

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC + ReflectionMethod::IS_PROTECTED) as $method) {
            $attributes = $method->getAttributes(SchemaForModel::class);

            foreach ($attributes as $attribute) {
                if ($model === $attribute->newInstance()->model) {
                    return $this->{$method->getName()};
                }
            }
        }

        // Try guessing and finding a method with the correct name (<camelCaseModel>Schema, such as eventSchema)
        $methodName = Str::of(class_basename($model))
            ->camel()
            ->append('Schema')
            ->toString()
        ;
        if (method_exists($this, $methodName)) {
            return $this->{$methodName};
        }

        // Try finding a "defaultSchema" or "schema" method.
        if (method_exists($this, 'defaultSchema')) {
            return $this->defaultSchema;
        }

        if (method_exists($this, 'schema')) {
            return $this->schema;
        }

        throw new SchemaNotFoundException('No schema found for the given model');
    }

    /**
     * @throws SchemaNotFoundException
     */
    public function getFormSchemaForModel(?string $model = null): Schema
    {
        try {
            return $this->getSchemaForModel($model);
        } catch (SchemaNotFoundException $e) {
            // Try to find form schema in resource
            /** @var resource $resource */
            if ($resource = Filament::getModelResource($model)) {
                return $resource::form(Schema::make($this));
            }

            throw $e;
        }
    }

    /**
     * @throws SchemaNotFoundException
     */
    public function getInfolistSchemaForModel(?string $model = null): Schema
    {
        try {
            return $this->getSchemaForModel($model);
        } catch (SchemaNotFoundException $e) {
            // Try to find infolist schema in resource
            /** @var resource $resource */
            if ($resource = Filament::getModelResource($model)) {
                return $resource::infolist(Schema::make($this));
            }

            throw $e;
        }
    }
}
