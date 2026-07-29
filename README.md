![calendar Banner](https://github.com/GuavaCZ/calendar/raw/main/.github/banner.png)


# Adds support for vkurko/calendar to Filament PHP.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/guava/calendar.svg?style=flat-square)](https://packagist.org/packages/guava/calendar)
[![Total Downloads](https://img.shields.io/packagist/dt/guava/calendar.svg?style=flat-square)](https://packagist.org/packages/guava/calendar)

This package adds support for [vkurko/calendar](https://github.com/vkurko/calendar) (free, open-source alternative to FullCalendar) to your FilamentPHP panels.

It allows you to create a widget with a calendar with support for **multiple** models and even resources you can group your events into. For example, you could have lessons (events) that are held in different rooms (resources).


## Documentation

The full documentation is available at [guava.cz](https://guava.cz/developers/packages/calendar?ref=github&utm_campaign=calendar).

## Version compatibility
| Filament version | Plugin version | Min. PHP version |
| ---------------- |:--------------:|:----------------:|
| 3.x              | 1.x            | 8.1              |
| 4.x              | 2.x            | 8.1              |
| 5.x              | 3.x            | 8.2              |

## Showcase
![Showcase 01](https://github.com/GuavaCZ/calendar/raw/main/docs/images/showcase_01.png)
![Showcase 02](https://github.com/GuavaCZ/calendar/raw/main/docs/images/showcase_02.png)



https://github.com/user-attachments/assets/fc7828ab-ccd2-4252-942a-9679af1e7687

<video width="320" height="240" controls>
  <source src="https://github.com/user-attachments/assets/fc7828ab-ccd2-4252-942a-9679af1e7687" type="video/mp4">
</video>


<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/demo_preview.mp4" type="video/mp4">
</video>

https://github.com/user-attachments/assets/a4460084-e8a8-4b1b-9ccd-4d887895155b


![Resources Screenshot 01](https://github.com/GuavaCZ/calendar/raw/main/docs/images/resources_screenshot_01.png)

<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/context_menu_preview.mp4" type="video/mp4">
</video>

https://github.com/user-attachments/assets/a2641b40-9cbd-4c40-b360-7621caa86c40

<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/context_menu_preview_2.mp4" type="video/mp4">
</video>


https://github.com/user-attachments/assets/4996cc6a-7cee-4c7d-976a-60d3a4368f76


<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/no_events_context_menu.mp4" type="video/mp4">
</video>

https://github.com/user-attachments/assets/7c2537d5-8acf-459f-a9a8-be02d4018448


## Support us

Your support is key to the continual advancement of our plugin. We appreciate every user who has contributed to our journey so far.

While our plugin is available for all to use, if you are utilizing it for commercial purposes and believe it adds significant value to your business, we kindly ask you to consider supporting us through GitHub Sponsors. This sponsorship will assist us in continuous development and maintenance to keep our plugin robust and up-to-date. Any amount you contribute will greatly help towards reaching our goals. Join us in making this plugin even better and driving further innovation.

## Installation

You can install the package via composer:

```bash
composer require guava/calendar
```

Make sure to publish the package assets using:

```bash
php artisan filament:assets
```

Finally, make sure you have a **custom filament theme** (read [here](https://filamentphp.com/docs/4.x/styling/overview#creating-a-custom-theme) how to create one) and add the following to your **theme.css** file:

This ensures that the CSS is properly built:
```css
@source '../../../../vendor/guava/calendar/resources/**/*';
```

This is optional but highly recommended as it will apply styles to better fit with the (default) filament theme:
```css
@import '../../../../vendor/guava/calendar/resources/css/theme.css';
```

The paths might be a little bit different if your theme.css is located in a non-standard path. Adjust accordingly.

## Usage

Create a widget extending `CalendarWidget`, return some events, and add it to a Filament page:

```php
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;

class MyCalendarWidget extends CalendarWidget
{
    protected function getEvents(FetchInfo $info): Collection|array
    {
        return [
            CalendarEvent::make()
                ->title('My first event')
                ->start(now())
                ->end(now()->addHours(2)),
        ];
    }
}
```

Add the widget to any Filament page you like, such as your `Dashboard`, and you're done!

For everything else, including multiple models, resources, interactivity (drag & drop, resize, context menus), custom event/resource content, actions and authorization, see the full documentation at [guava.cz](https://guava.cz/developers/packages/calendar?ref=github&utm_campaign=calendar).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits
- [Lukas Frey](https://github.com/lukas-frey)
- [All Contributors](../../contributors)
- Spatie - Our package skeleton is a modified version of [Spatie's Package Skeleton](https://github.com/spatie/package-skeleton-laravel)
- [vkurko/calendar](https://github.com/vkurko/calendar) - free, open-source alternative to FullCalendar
- [saade/filament-fullcalendar](https://github.com/saade/filament-fullcalendar) - heavy inspiration for this package

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
