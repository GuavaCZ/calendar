---
title: Custom label content
---

# Custom label content

By default, resource labels render with the default view of the calendar package. Just like with [event content](../events/03-custom-event-content.md), you can provide your own.

The same rule applies here: the content is rendered **once** on the server and reused for every resource, so you cannot access the resource data from blade or PHP. Each label is wrapped in an alpine component that exposes the resource data, and you build the dynamic parts with [AlpineJS](https://alpinejs.dev/).

## A single content for all resources

If all your resources render the same way, return a view or a `HtmlString` from the `resourceLabelContent` method (or its alias `defaultResourceLabelContent`):

```php
use Illuminate\Support\HtmlString;

protected function resourceLabelContent(): HtmlString | string
{
    return view('calendar.resource');
}
```

The blade view uses alpine to read the resource data:

```blade
<div class="flex flex-col items-start">
    <span x-text="resource.title"></span>
</div>
```

Anything you passed via `extendedProps` is available under `resource.extendedProps`.

## A content per model

If you display multiple models as resources, you can implement a label content method per model. You have two options:

- Define a method with any name and add the `#[CalendarResourceLabelContent]` attribute,
- or follow the naming convention `camelCaseModelNameResourceLabelContent`, such as `fooResourceLabelContent`

```php
use Guava\Calendar\Attributes\CalendarResourceLabelContent;
use Illuminate\Support\HtmlString;

// Variant 1
#[CalendarResourceLabelContent(Foo::class)]
protected function myFooLabel(): HtmlString | string
{
    return view('calendar.foo-resource');
}

// Variant 2
protected function barResourceLabelContent(): HtmlString | string
{
    return view('calendar.bar-resource');
}
```

Both variants are equal, it's up to your personal preference which one you want to use.
