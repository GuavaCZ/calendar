---
title: Calendar views
---

# Calendar views

By default, the calendar shows the `DayGridMonth` view. You can change it by overriding the `calendarView` property:

```php
use Guava\Calendar\Enums\CalendarViewType;

protected CalendarViewType $calendarView = CalendarViewType::TimeGridWeek;
```

## Available views

The `CalendarViewType` enum contains all views the underlying calendar supports:

| View type | Description |
|-----------|-------------|
| `DayGridDay`, `DayGridWeek`, `DayGridMonth` | The classic grid of day cells |
| `TimeGridDay`, `TimeGridWeek` | Days split into time slots |
| `ListDay`, `ListWeek`, `ListMonth`, `ListYear` | Events as a simple list |
| `ResourceTimeGridDay`, `ResourceTimeGridWeek` | Time grid with a column per resource |
| `ResourceTimelineDay`, `ResourceTimelineWeek`, `ResourceTimelineMonth`, `ResourceTimelineYear` | Horizontal timeline with a row per resource |

> [!NOTE]
> If you pick one of the `Resource*` views, you also need to tell the calendar which [resources](../resources/01-adding-resources.md) to display.
