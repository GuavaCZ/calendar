---
title: Installation
---

# Installation

## Requirements

- PHP 8.2 or higher
- Filament 5.x

## Installing the package

You can install the package via composer:

```bash
composer require guava/calendar
```

Next, publish the package assets:

```bash
php artisan filament:assets
```

## Custom theme

A custom filament theme is **required**, otherwise the CSS of the calendar is not built and the widget will look broken.

If you don't have one yet, please read the [filament documentation](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme) on how to create a custom theme.

Once you have a theme, add the following to your **theme.css** file:

```css
@source '../../../../vendor/guava/calendar/resources/**/*';
```

We also recommend importing our theme, which styles the calendar to better fit the default filament look:

```css
@import '../../../../vendor/guava/calendar/resources/css/theme.css';
```

The import is optional, but without it the calendar uses the default vkurko/calendar styling.

> [!NOTE]
> If your theme.css lives in a non-standard path, adjust the paths accordingly.
