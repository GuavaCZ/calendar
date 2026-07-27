---
title: Configuration
---

# Configuration

The widget contains a bunch of properties and methods you can override to configure the calendar. This page goes through each of them.

## Heading

By default, the widget renders a heading that says `Calendar` (through our translations, so it follows your app's locale).

You can change it by overriding the `$heading` property, or disable it by setting the property to `null`:

```php
protected string | HtmlString | bool | null $heading = 'My calendar';
```

To render HTML, override the `getHeading` method instead and return a `HtmlString`:

```php
public function getHeading(): string | HtmlString
{
    return new HtmlString('<div>My <b>calendar</b></div>');
}
```

## Locale

By default, the calendar uses your app's locale.

The underlying calendar package doesn't support locales with a region code, so locales such as `fr_CA` or `en_US` would be invalid. We handle this for you by only using the language part of the locale. If you still run into localization issues, you can override the locale manually:

```php
protected ?string $locale = 'en';
```

## First day of the week

By default, the week starts on Monday. You can change it by overriding the `firstDay` property:

```php
use Carbon\WeekDay;

protected WeekDay $firstDay = WeekDay::Sunday;
```

## Day max events

In the day grid views, days with many events can grow very tall. Set `dayMaxEvents` to limit the stacked events to the height of the day cell, with a `+2 more` link for the rest:

```php
protected bool $dayMaxEvents = true;
```

Currently only a boolean is supported. The default is `false`, which means no limit.

## Filament timezone

> [!CAUTION]
> We recommend enabling this, but it comes with side effects you should know about. Keep reading.

The underlying calendar package does **not** support timezones, so everything the user sees is rendered in their local browser time.

This can cause confusion once you add interactivity: filament modals display times in the app's timezone (or in the `FilamentTimezone` you configured), while the calendar renders browser time. To learn more about the `FilamentTimezone` setting, please refer to the [filament documentation](https://filamentphp.com/docs/5.x/forms/date-time-picker#timezones).

If you want the calendar to use the same timezone as configured via `FilamentTimezone`, enable it on the widget:

```php
protected bool $useFilamentTimezone = true;
```

We achieve this by intercepting the dates sent from and to the calendar and overriding their timezone. However, we cannot override all dates the calendar uses internally. For example, the now indicator still uses the browser time. We are trying to find a solution for this.

## Header and footer actions

You can render filament actions above and below the calendar by implementing `getHeaderActions` or `getFooterActions`:

```php
public function getHeaderActions(): array
{
    return [
        $this->createAction(Foo::class),
    ];
}
```

Both accept regular actions as well as action groups. Please read the [actions](../interactions/01-actions.md) page first, as actions inside the calendar need our `CalendarAction` trait.

## Raw calendar options

The `CalendarEvent` objects and widget properties cover the most common options, but [vkurko/calendar](https://github.com/vkurko/calendar#options) supports many more. You can pass any of them by overriding the `getOptions` method:

```php
public function getOptions(): array
{
    return [
        'nowIndicator' => true,
        'slotDuration' => '00:15',
    ];
}
```

To change an option at runtime, use `setOption`. For example, to programmatically navigate the calendar to tomorrow:

```php
$this->setOption('date', today()->addDay()->toIso8601String());
```
