---
title: Actions
---

# Actions

Before you read about the different ways to interact with the calendar, you need to understand how actions work inside it.

Actions used within the calendar need our `CalendarAction` trait to work properly. We provide drop-in replacements for the regular filament actions that already include everything necessary:

- `Guava\Calendar\Filament\Actions\CreateAction`
- `Guava\Calendar\Filament\Actions\ViewAction`
- `Guava\Calendar\Filament\Actions\EditAction`
- `Guava\Calendar\Filament\Actions\DeleteAction`

All they do is extend the regular filament action and add a few important `setUp` calls. So whenever you use one of these, **make sure** you import the action from our package, not from filament.

## Defining actions

Every action you use in the calendar should be defined as a public method on the widget, just as usual when [adding an action to a livewire component](https://filamentphp.com/docs/5.x/components/action#adding-the-action) in filament.

The `view`, `edit` and `delete` actions are already present for you, you don't need to add them.

Create actions you still define yourself, since each model needs its own. For example, to add a `createFooAction` (where `Foo` is a model in your app):

```php
use Guava\Calendar\Filament\Actions\CreateAction;

public function createFooAction(): CreateAction
{
    // You can use our helper method
    return $this->createAction(Foo::class);

    // Or you can add it manually, both variants are equivalent:
    return CreateAction::make('createFoo')
        ->model(Foo::class);
}
```

## Mounting actions

Whenever you want to mount an action programmatically within a calendar context, such as in the `onDateClick` handler, use the `mountAction` method:

```php
use Guava\Calendar\ValueObjects\DateClickInfo;

protected function onDateClick(DateClickInfo $info): void
{
    $this->mountAction('createFoo');
}
```

In the background, we pass a few extra arguments along, so your actions can access the calendar context.

## Accessing context information

Inside calendar actions, you can type hint contextual information and it will be injected for you if available:

| Parameter | Description |
|-----------|-------------|
| `Context` | The current context enum, or `null` if not in a calendar context |
| `ContextualInfo` | The info object of the current context, whatever it is |
| `DateClickInfo` | The info of a date click, otherwise `null` |
| `DateSelectInfo` | The info of a date selection, otherwise `null` |
| `EventClickInfo` | The info of an event click, otherwise `null` |
| `NoEventsClickInfo` | The info of a no-events click, otherwise `null` |

For example, to prefill the form based on where the user clicked:

```php
use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\DateSelectInfo;

public function createFooAction(): CreateAction
{
    return $this->createAction(Foo::class)
        ->mountUsing(function (?ContextualInfo $info) {
            if ($info instanceof DateClickInfo) {
                // do something on date click
            }

            // Both checks are equal, but instanceof gives you better IDE help
            if ($info->getContext() === Context::DateSelect) {
                // do something on date select
            }
        })
        // You can also type hint each info directly:
        ->mountUsing(fn (?DateClickInfo $dateClick, ?DateSelectInfo $dateSelect) => /* ... */);
}
```

This is not limited to `mountUsing`, almost all action methods have access to these. For example, to hide an action in the date click context:

```php
use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\Context;

$this->createAction(Foo::class)
    ->hidden(fn (?ContextualInfo $info) => $info?->getContext() === Context::DateClick);
```

## Authorization

> [!CAUTION]
> Actions have no default authorization. This means anyone can use any action until you add it yourself.

Since these are regular filament actions, adding authorization is a breeze:

```php
use Guava\Calendar\Filament\Actions\CreateAction;

public function createFooAction(): CreateAction
{
    return $this->createAction(Foo::class)
        // Authorizes against your FooPolicy
        ->authorize('create', Foo::class)
        // Optionally give the user feedback with a notification
        // containing the response message from your policy
        ->authorizationNotification()
        // For context menu actions, you can instead disable the action
        // and show the message as a tooltip
        ->authorizationTooltip();
}
```

For detailed information, please follow the filament documentation on [authorizing actions](https://filamentphp.com/docs/5.x/actions/overview#authorization).

## Record vs event record

When you place a calendar widget on a filament resource page, `$record` is the record of the currently opened resource page, whereas `$eventRecord` is the record of the calendar event you are interacting with.
