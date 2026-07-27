---
title: Configuring events
---

# Configuring events

The `CalendarEvent` object is a fluent wrapper around the [event object](https://github.com/vkurko/calendar?tab=readme-ov-file#event-object) of the underlying calendar package. This page goes through the available methods.

## Title

Sets the title that is rendered in the calendar:

```php
CalendarEvent::make()->title('My event');
```

To output HTML, pass a `HtmlString` or any other `Htmlable`:

```php
CalendarEvent::make()->title(new HtmlString('<b>My event</b>'));
```

## Start and end date

```php
CalendarEvent::make()
    ->start(today())
    ->end(today()->addDays(3));
```

## All-day events

```php
CalendarEvent::make()->allDay();
```

## Colors

By default, events use the primary color of your filament panel. You can override the background and text color per event:

```php
CalendarEvent::make()
    ->backgroundColor('#ff0000')
    ->textColor('#ffffff');
```

## Styles

For more control than the colors, you can apply custom CSS styles to the event element. The array accepts three formats, and you can mix them freely:

```php
CalendarEvent::make()->styles([
    'color: red' => $this->isImportant(), // applied only if the condition is true
    'background-color' => '#ffff00',      // property => value
    'font-size: 12px',                    // always applied
]);
```

The conditional format is handy for styling based on the state of a record, for example graying out cancelled events.

## Classes

The same pattern works for CSS classes, via `classNames` (or its alias `classes`):

```php
CalendarEvent::make()->classNames([
    'my-event',
    'my-event--cancelled' => $this->isCancelled(), // applied only if the condition is true
]);
```

## Display mode

By default, events render as blocks. You can render an event as a background event instead, which fills the whole date cell:

```php
CalendarEvent::make()
    ->display('background')
    // or use the short-hands:
    ->displayBackground()
    ->displayAuto();
```

> [!NOTE]
> Background events only work for all-day events and only in specific views. If unsupported, the event is not rendered at all.

## Editing behavior

When [drag & drop or resizing](../interactions/05-drag-and-resize.md) is enabled on the widget, you can still lock individual events:

```php
CalendarEvent::make()
    ->editable(false)         // neither draggable nor resizable
    ->startEditable(false)    // not draggable
    ->durationEditable(false); // not resizable
```

## Click action

Sets the action to mount when the event is clicked. It can be the name of any filament action defined in your widget. The `view` and `edit` actions are already provided for you:

```php
CalendarEvent::make()->action('edit');
```

Please read the [actions](../interactions/01-actions.md) page for how actions work inside the calendar.

## Model and record key

To mount actions with the correct record, the event needs to know the model class and the record key. Passing the record to `make` sets both for you:

```php
$record = Foo::find(1);

CalendarEvent::make($record);

// which is equivalent to:
CalendarEvent::make()
    ->model($record::class)
    ->key($record->getKey());
```

The model is also used to pick the right [schema](../interactions/02-schemas.md) and [event content](03-custom-event-content.md) when you work with multiple models.

## URL

Instead of mounting an action, an event can simply link somewhere:

```php
CalendarEvent::make()->url('https://example.com', target: '_self');
```

The target defaults to `_blank`.

## Linking to resources

If you use [resources](../resources/01-adding-resources.md), tell the event which resource(s) it belongs to:

```php
CalendarEvent::make()
    ->resourceId('foo')             // a single resource ID, can be repeated
    ->resourceIds(['bar', 'baz']);  // or multiple at once
```

## Timezone

If a specific event should be interpreted in a different timezone, you can set it per event:

```php
CalendarEvent::make()->timezone('Europe/Prague');
```

## Custom data

You can pass any custom data to the event. It ends up in the `extendedProps` of the calendar object, which is useful for [custom event content](03-custom-event-content.md):

```php
CalendarEvent::make()
    ->extendedProp('foo', 'bar')
    // or
    ->extendedProps(['baz' => 'qux']);
```
