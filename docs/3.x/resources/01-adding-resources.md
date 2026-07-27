---
title: Adding resources
---

# Adding resources

To add resources to your calendar, override the `getResources` method on your widget:

```php
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

protected function getResources(): Collection | array | Builder
{
    // ...
}
```

Just like with events, there are two ways to provide them.

## From eloquent

Return the query, and we handle the rest:

```php
protected function getResources(): Collection | array | Builder
{
    return Bar::query();
}
```

For this to work, the model has to implement the `Resourceable` contract, which maps the model into a `CalendarResource`:

```php
use Guava\Calendar\Contracts\Resourceable;
use Guava\Calendar\ValueObjects\CalendarResource;

class Bar extends Model implements Resourceable
{
    public function toCalendarResource(): CalendarResource
    {
        return CalendarResource::make($this->getKey())
            ->title($this->name);
    }
}
```

## From an array or collection

Alternatively, return `CalendarResource` objects directly:

```php
use Guava\Calendar\ValueObjects\CalendarResource;

protected function getResources(): Collection | array | Builder
{
    return [
        CalendarResource::make('baz') // has to be a unique ID
            ->title('My resource'),
    ];
}
```

As with `Eventable`, the `Resourceable` interface is not limited to eloquent models. Feel free to add it to any class.

## Linking events to resources

Displaying resources is only half of the job. Your events also need to say which resource they belong to, otherwise they won't show up in the resource views.

Pass the resource IDs to your `CalendarEvent` objects, either in `getEvents` or in your `toCalendarEvent` methods:

```php
CalendarEvent::make($this)
    ->title($this->name)
    ->start($this->starts_at)
    ->end($this->ends_at)
    ->resourceId($this->room_id);
```

An event can belong to multiple resources at once, see [linking to resources](../events/02-configuring-events.md#linking-to-resources).

## Refreshing resources

If your resources changed and you need to refresh them, call:

```php
$this->refreshResources();
```
