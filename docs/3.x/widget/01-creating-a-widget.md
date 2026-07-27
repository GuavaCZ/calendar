---
title: Creating a widget
---

# Creating a widget

Create a widget class and extend `CalendarWidget`:

```php
use Guava\Calendar\Filament\CalendarWidget;

class MyCalendarWidget extends CalendarWidget
{
}
```

You can also generate it with artisan:

```bash
php artisan make:filament-widget
```

> [!IMPORTANT]
> If you use the artisan command, make sure to remove the generated `$view` property from the class. Our widget brings its own view.

Then register the widget like any other filament widget, for example on your `Dashboard` or on a resource page. That's all, you now have a working calendar in your panel :)

The widget spans the full width of the page by default. Since it is a regular filament widget, you can change that through the usual `$columnSpan` property.

Your calendar is still empty at this point. Continue with [adding events](../events/01-adding-events.md) to fill it.
