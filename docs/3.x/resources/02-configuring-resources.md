---
title: Configuring resources
---

# Configuring resources

The `CalendarResource` object is a fluent wrapper around the [resource object](https://github.com/vkurko/calendar?tab=readme-ov-file#resource-object) of the underlying calendar package. This page goes through the available methods.

## ID

Every resource needs a unique ID, which you pass to `make`. This is the ID your events reference through `resourceId`:

```php
CalendarResource::make('room-1');
```

## Title

Sets the label that is rendered for the resource:

```php
CalendarResource::make('room-1')->title('Room 1');
```

To output HTML, pass a `HtmlString` or any other `Htmlable`.

## Event colors

You can set default colors for all events of a resource:

```php
CalendarResource::make('room-1')
    ->eventBackgroundColor('#ff0000')
    ->eventTextColor('#ffffff');
```

Colors set on an [event](../events/02-configuring-events.md#colors) itself take precedence.

## Nested resources

Resources can have children, which renders them as a collapsible group in the timeline views:

```php
CalendarResource::make('building-a')
    ->title('Building A')
    ->children([
        CalendarResource::make('room-1')->title('Room 1'),
        CalendarResource::make('room-2')->title('Room 2'),
    ]);
```

You can also add them one by one with `child()`.

## Custom data

Just like events, resources accept any custom data via `extendedProps`, which you can use in [custom label content](03-custom-label-content.md):

```php
CalendarResource::make('room-1')
    ->extendedProp('foo', 'bar')
    // or
    ->extendedProps(['baz' => 'qux']);
```
