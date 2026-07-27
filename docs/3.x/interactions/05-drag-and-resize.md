---
title: Drag & drop and resizing
---

# Drag & drop and resizing

The calendar lets your users move events to a different date by dragging them, and change their duration by resizing them. Both are disabled by default.

> [!IMPORTANT]
> Unlike the click handlers, these handlers return a boolean. Return `true` to accept the change, or `false` to revert the event to its original position on the frontend.

## Drag & drop

Enable it first:

```php
protected bool $eventDragEnabled = true;
```

Then override the `onEventDrop` method and persist the new dates:

```php
use Guava\Calendar\ValueObjects\EventDropInfo;
use Illuminate\Database\Eloquent\Model;

protected function onEventDrop(EventDropInfo $info, Model $event): bool
{
    $event->update([
        'starts_at' => $info->event->getStart(),
        'ends_at' => $info->event->getEnd(),
    ]);

    return true;
}
```

The `EventDropInfo` contains the moved `event` with its new dates, the `oldEvent` with the original ones and the `record`.

## Resizing

Enable it first:

```php
protected bool $eventResizeEnabled = true;
```

Then override the `onEventResize` method:

```php
use Guava\Calendar\ValueObjects\EventResizeInfo;
use Illuminate\Database\Eloquent\Model;

protected function onEventResize(EventResizeInfo $info, Model $event): bool
{
    $event->update([
        'ends_at' => $info->event->getEnd(),
    ]);

    return true;
}
```

## Locking individual events

Enabling drag or resize applies to all events. To lock specific events, use `editable`, `startEditable` and `durationEditable` on the [event object](../events/02-configuring-events.md#editing-behavior).

## Security

Returning `false` only reverts the event visually. The dates still arrive from the browser, so please validate them before persisting. For example, check that the user is actually allowed to move the event and that the new dates make sense.
