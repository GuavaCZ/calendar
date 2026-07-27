---
title: Lifecycle events
---

# Lifecycle events

Besides the user interactions, the calendar can notify your widget about a few lifecycle events. These are disabled by default as well.

## Dates set

Triggered when the date range of the calendar is initially set or changed by clicking the previous/next buttons, changing the view, manipulating the date via the API, and so on.

Enable it first:

```php
protected bool $datesSetEnabled = true;
```

Then override the `onDatesSet` method:

```php
use Guava\Calendar\ValueObjects\DatesSetInfo;

protected function onDatesSet(DatesSetInfo $info): void
{
    // For example, store $info->start and $info->end in the session
    // to remember the date range across page refreshes
}
```

## View did mount

Triggered right after the calendar view has been added to the DOM.

Enable it first:

```php
protected bool $viewDidMountEnabled = true;
```

Then override the `onViewDidMount` method:

```php
use Guava\Calendar\ValueObjects\ViewDidMountInfo;

protected function onViewDidMount(ViewDidMountInfo $info): void
{
    // ...
}
```

## Event all updated

Triggered after the calendar finished updating all its events, for example after a refresh.

Enable it first:

```php
protected bool $eventAllUpdatedEnabled = true;
```

Then override the `onEventAllUpdated` method:

```php
use Guava\Calendar\ValueObjects\EventAllUpdatedInfo;

protected function onEventAllUpdated(EventAllUpdatedInfo $info): void
{
    // ...
}
```

## Refreshing the calendar

You can also go the other way and tell the calendar that your data changed:

```php
// Refresh the events
$this->refreshRecords();

// Refresh the resources
$this->refreshResources();
```
