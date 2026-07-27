---
title: Custom event content
---

# Custom event content

By default, events render with the default view of the calendar package. If you want full control over the markup, you can provide your own content.

To keep things fast, the content is rendered **once** on the server and then reused for every event. This means you **cannot** access the event data from blade or PHP. Instead, each event is wrapped in an alpine component that exposes the event data, so you build the dynamic parts with [AlpineJS](https://alpinejs.dev/).

## A single content for all events

If all your events render the same way, return a view or a `HtmlString` from the `eventContent` method (or its alias `defaultEventContent`):

```php
use Illuminate\Support\HtmlString;

protected function eventContent(): HtmlString | string
{
    return view('calendar.event');
}
```

The blade view uses alpine to read the event data:

```blade
<div class="flex flex-col items-start">
    <span x-text="event.title"></span>
    <template x-for="user in event.extendedProps.users">
        <span x-text="user.name"></span>
    </template>
</div>
```

Anything you passed to the event via `extendedProps` is available under `event.extendedProps`.

## A content per model

If you display multiple models and want each to render differently, implement a content method per model. You have two options:

- Define a method with any name and add the `#[CalendarEventContent]` attribute,
- or follow the naming convention `camelCaseModelNameEventContent`, such as `fooEventContent`

```php
use Guava\Calendar\Attributes\CalendarEventContent;
use Illuminate\Support\HtmlString;

// Variant 1
#[CalendarEventContent(Foo::class)]
protected function myFooContent(): HtmlString | string
{
    return view('calendar.foo-event');
}

// Variant 2
protected function barEventContent(): HtmlString | string
{
    return view('calendar.bar-event');
}
```

Both variants are equal, it's up to your personal preference which one you want to use.

> [!IMPORTANT]
> Matching by model only works when the event knows its model. Make sure you pass the record to `CalendarEvent::make($record)`, as described in [adding events](01-adding-events.md).
