---
title: Upgrading
---

# Upgrading

This guide covers the upgrade from 2.x to 3.x.

Version 3.x exists for filament 5 and requires PHP 8.2 and Laravel 12 or newer. There are no changes to the public API of the package, so in most cases you only need to upgrade filament itself and bump the constraint.

## Upgrade filament

Please follow the [filament upgrade guide](https://filamentphp.com/docs/5.x/upgrade-guide) first. If your panels don't run on filament 5 yet, the calendar won't work either.

## Bump the constraint

```bash
composer require guava/calendar:"^3.0"
```

Then republish the assets, since the built JS changed:

```bash
php artisan filament:assets
```

## Check your theme

The source path in your **theme.css** is unchanged:

```css
@source '../../../../vendor/guava/calendar/resources/**/*';
```

If you never added it, please read the [installation](02-installation.md) page, as a custom theme is required.

## Found an issue?

If you find a step that is missing here, please open a PR and modify this file. We will review it and merge it.
