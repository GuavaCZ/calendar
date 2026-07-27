---
title: Adding events
---

# Adding events

To add events to your calendar, override the `getEvents` method on your widget:

```php
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

protected function getEvents(FetchInfo $info): Collection | array | Builder
{
    // ...
}
```

The `FetchInfo` object tells you the date range the calendar currently displays: `$info->start` and `$info->end`. Use it to only query events that are actually visible.

There are two ways to provide the events. Which one you use depends on where your events come from.

## From eloquent

In most cases, you want to display your eloquent models as events. The easiest way is to return the query, and we handle the rest:

```php
protected function getEvents(FetchInfo $info): Collection | array | Builder
{
    return Foo::query()
        ->whereDate('ends_at', '>=', $info->start)
        ->whereDate('starts_at', '<=', $info->end);
}
```

For this to work, the model has to implement the `Eventable` contract, which maps the model into a `CalendarEvent`:

```php
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;

class Foo extends Model implements Eventable
{
    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->title($this->name)
            ->start($this->starts_at)
            ->end($this->ends_at);
    }
}
```

> [!IMPORTANT]
> Notice that the model is passed to `CalendarEvent::make($this)`. This stores the model class and record key on the event, which is how we find the record again when the event is clicked or dragged. If you forget it, none of the [interactions](../interactions/01-actions.md) will be able to identify the record.

If you want to display multiple types of models, combine the results of each query yourself:

```php
protected function getEvents(FetchInfo $info): Collection | array | Builder
{
    return collect()
        ->push(...Foo::query()->get())
        ->push(...Bar::query()->get());
}
```

## From an array or collection

Sometimes your events don't live in the database, for example when they come from an external API. In that case, return `CalendarEvent` objects directly:

```php
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;

protected function getEvents(FetchInfo $info): Collection | array | Builder
{
    return [
        CalendarEvent::make()
            ->title('My first event')
            ->start(now())
            ->end(now()->addHours(2)),
    ];
}
```

By the way, `Eventable` is a plain interface, so you are not limited to eloquent models. You can add it to any class you want to display in the calendar.

## Refreshing events

The calendar fetches events on its own when the user navigates around. If your data changed and you need to refresh the currently visible events, call:

```php
$this->refreshRecords();
```
