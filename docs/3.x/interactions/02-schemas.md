---
title: Schemas
---

# Schemas

The `create`, `view` and `edit` actions work out of the box and use the correct schemas.

We attempt to guess the filament resource of the model and reuse its schema: `create` and `edit` actions use your **form schema**, while `view` actions use your **infolist schema**, falling back to the form schema if there is no infolist.

If auto discovery works for you, you are ready to go :) Otherwise, you can define the schema yourself, either as a shared default or per model.

## Default schema

If you only work with a single model, or you want to share the same schema across multiple models, implement the `defaultSchema` method (or its alias `schema`) on your widget:

```php
use Filament\Schemas\Schema;

public function defaultSchema(Schema $schema): Schema
{
    return $schema->components([
        // ...
    ]);
}
```

## Schema per model

If you need a specific schema for a model, you have two options:

- Define a method with any name and add the `#[CalendarSchema]` attribute,
- or follow the naming convention `camelCaseModelNameSchema`, such as `fooBarSchema`

```php
use Filament\Schemas\Schema;
use Guava\Calendar\Attributes\CalendarSchema;

// Variant 1
#[CalendarSchema(FooBar::class)]
public function baz(Schema $schema): Schema
{
    return $schema->components([
        // ...
    ]);
}

// Variant 2
public function fooBarSchema(Schema $schema): Schema
{
    return $schema->components([
        // ...
    ]);
}
```

Both variants are equal, it's up to your personal preference which one you want to use.
