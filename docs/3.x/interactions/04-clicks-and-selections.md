---
title: Clicks and selections
---

# Clicks and selections

The calendar can report clicks on dates, events and empty list views back to your widget. All of these are disabled by default. Once you enable one, a request is sent to livewire every time it happens, so only enable what you actually use.

For each click type you can either implement your own handler, or use the [context menu](03-context-menus.md) feature. If both are present, the context menu wins.

## Date click

Triggered when a date cell is clicked. Enable it first:

```php
protected bool $dateClickEnabled = true;
```

Then override the `onDateClick` method:

```php
use Guava\Calendar\ValueObjects\DateClickInfo;

protected function onDateClick(DateClickInfo $info): void
{
    // For example, mount a create action
    $this->mountAction('createFoo');
}
```

The `DateClickInfo` gives you the clicked `date`, whether it was an `allDay` cell, the current `view` and the `resource` if you clicked inside a resource view.

## Date selection

Triggered when the user drags across date cells to make a selection. Enable it first:

```php
protected bool $dateSelectEnabled = true;
```

Then override the `onDateSelect` method:

```php
use Guava\Calendar\ValueObjects\DateSelectInfo;

protected function onDateSelect(DateSelectInfo $info): void
{
    $this->mountAction('createFoo');
}
```

The `DateSelectInfo` gives you the `start` and `end` of the selection, which is great for prefilling the dates of a create form through `mountUsing`, see [accessing context information](01-actions.md#accessing-context-information).

## Event click

Triggered when an event is clicked. Enable it first:

```php
protected bool $eventClickEnabled = true;
```

Unlike the other clicks, this one does something by default: it mounts the `view` action with the clicked record. You can change the default action:

```php
protected ?string $defaultEventClickAction = 'edit';
```

It can be any action defined in your widget, even your own custom ones. Individual events can also override it via [`action()`](../events/02-configuring-events.md#click-action).

To take full control instead, override the `onEventClick` method:

```php
use Guava\Calendar\ValueObjects\EventClickInfo;
use Illuminate\Database\Eloquent\Model;

protected function onEventClick(EventClickInfo $info, Model $event, ?string $action = null): void
{
    // $event contains the clicked record, also available as $info->record
}
```

## No events click

> [!NOTE]
> This one only has an effect in list views.

Triggered when a list view has no events and its empty content is clicked. Enable it first:

```php
protected bool $noEventsClickEnabled = true;
```

Then override the `onNoEventsClick` method:

```php
use Guava\Calendar\ValueObjects\NoEventsClickInfo;

protected function onNoEventsClick(NoEventsClickInfo $info): void
{
    $this->mountAction('createFoo');
}
```

## Security

The info objects are built from data sent by the browser, so they can be tampered with. Always validate on the server side and never trust the data blindly.
