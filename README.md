![calendar Banner](https://github.com/GuavaCZ/calendar/raw/main/docs/images/banner.jpg)


# Adds support for vkurko/calendar to Filament PHP.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/guava/calendar.svg?style=flat-square)](https://packagist.org/packages/guava/calendar)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/guava/calendar/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/GuavaCZ/calendar/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/guava/calendar/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/GuavaCZ/calendar/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/guava/calendar.svg?style=flat-square)](https://packagist.org/packages/guava/calendar)

This package adds support for [vkurko/calendar](https://github.com/vkurko/calendar) (free, open-source alternative to FullCalendar) to your FilamentPHP panels.

## Showcase
![Showcase 01](https://github.com/GuavaCZ/calendar/raw/main/docs/images/showcase_01.png)
![Showcase 02](https://github.com/GuavaCZ/calendar/raw/main/docs/images/showcase_02.png)

<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/demo_preview.mp4" type="video/mp4">
</video>

https://github.com/user-attachments/assets/a4460084-e8a8-4b1b-9ccd-4d887895155b

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

Make sure you have a custom filament theme (read [here](https://filamentphp.com/docs/3.x/panels/themes#creating-a-custom-theme) how to create one) and add the following to the `content` property of your theme's tailwind.config.js:

```js
{
    content: [
        //...

        './vendor/guava/calendar/resources/**/*.blade.php',
    ]
}
```
This ensures that the CSS is properly built.


## Usage

# Creating the calendar Widget
First you need to create a custom widget and extend the `CalendarWidget` class. Make sure to remove the `view` property from the generated widget class!

Either use the artisan command or simply create an empty class and extend `CalendarWidget`:
```bash
php artisan make:filament-widget
```

The widget class should look like this:
```php
use \Guava\Calendar\Widgets\CalendarWidget;

class MyCalendarWidget extends CalendarWidget
{
}
```

Add the widget like a regular widget to any filament page you like, such as your `Dashboard`.

## Customizing the calendar view
By default, we show the `dayGridMonth` view. You can customize the view by overriding the `calendarView` property on the widget class:

```php
protected string $calendarView = 'resourceTimeGridWeek';
```

All available views are listed in the [calendar documentation](https://github.com/vkurko/calendar?tab=readme-ov-file#view).

## Adding events
By default, the calendar will be empty. To add events, simply override the `getEvents` method:

```php
use Illuminate\Support\Collection;
use Guava\Calendar\ValueObjects\Event;

public function getEvents(array $fetchInfo = []): Collection | array
    {
        return [
            // Chainable object-oriented variant
            Event::make()
                ->title('My first event')
                ->start(today())
                ->end(today()),
                
            // Array variant
            ['title' => 'My second event', 'start' => today()->addDays(3), 'end' => today()->addDays(3)],
            
            // Eloquent model implementing the `Eventable` interface
            MyEvent::find(1),
        ];
    }
```

### Creating events
As shown in the example, there are multiple ways to create events. At the very least, an array object with a `title`, `start` and `end` properties is required.

To help you with creating events, we provide an `Event` ValueObject which contains methods with all available properties an event can have.

This is possible because the `Event` clas implements the `Eventable` interface, which returns the array object. You can add this interface to any class you want which should be treated as an event, such as your eloquent models.

Here is an example:

#### using an Eloquent model as Events
```php
class Foo extends Model implements Eventable
{
    // ...
    
    public function toEvent(): Event|array {
        return Event::make($this)
            ->title($this->name)
            ->start($this->starts_at)
            ->end($this->ends_at);
    }
}
```

Notice that the model is passed to the `Event` constructor in the `make` method. This sets the `key` and `model` properties to the event object, so it can be used to trigger actions.

### Event object
The event object takes all available options like the underlying calendar package, for more info [read here](https://github.com/vkurko/calendar?tab=readme-ov-file#event-object).

Below is a list of available methods on the event object:

#### Setting the title
Sets the title of the event that is rendered in the calendar.
```php
Event::make()->title('My event');
```

#### Customizing the start/end date
Sets the start or end date (and time) of the event in the calendar.
```php
Event::make()
    ->start(today())
    ->end(today()->addDays(3));
```

#### Making the event all-day
Sets whether the event is an all-day event or not.
```php
Event::make()->allDay();
```

#### Customizing the background / text color
Sets the background color of the event (by default it is the primary color of the panel).
```php
Event::make()
->backgroundColor('#ff0000')
->textColor('#ffffff');
```

#### Customizing Event Styles

You can add custom styles to your event elements by using the styles method. This method accepts an array where each entry can be a CSS style declaration. The styles will be directly applied to the event element in the view. You can define styles in three ways:

- As a key-value pair where the key is the CSS property and value is the condition under which the style should apply.
- As a key-value pair where the key is the CSS property and the value is directly the CSS value.
- As a single string for static styles that always apply.

Here's how you can use it:

```php
Event::make()->styles([
    'color: red' => true,            // Applies the style if the condition (true) is met
    'background-color' => '#ffff00', // Directly applies the background color
    'font-size: 12px'                // Always applies this font size
]);
```

##### Usage Notes:

- The first format ('color: red' => true) is useful for conditional styling based on dynamic conditions. For instance, changing the text color based on an event's type or status.

- The second format ('background-color' => '#ffff00') is straightforward for applying styles where the values do not depend on conditions.
- The third format ('font-size: 12px') is used when the style does not require any condition and is always applied to the event.
This flexibility allows you to easily customize the appearance of events based on dynamic conditions or predefined settings.

#### Customizing Event Classes

Following the same pattern as with the styles property, it is possible to inject custom classes into the Event element using the `classNames` or `classes` property.

Here's how you can use it:

```php
Event::make()->classNames([
    'class-1',            
    'class-2' => true  // Applies the class if the condition (true) is met
]);
```

##### Usage Notes:

- The second format ('class-2' => true) is useful for conditional classes based on dynamic conditions.

#### Customizing the display
By default, events are rendered as `blocks`. This is when the display is set to `auto`, which it is by default. You can also change the event to be rendered as a background event, which then fills the whole date cell. To do so, you can set `display` to `background` on the event:

This doesn't work always though, it only works on all day events and in specific views. If the `background` event is unsupported, the event will not be rendered at all.

```php
Event::make()
->display('background') // or 'auto'
->displayAuto() // short-hand for ->display('auto')
->displayBackground(); // short-hand for ->display('background')
```

#### Setting the action on click
This sets the action that should be mounted when the event is clicked. It can be any name of a filament action you defined in your widget, such as `edit` or `view`.

By default, all `CalendarWidget` classes already include a `view` and `edit` action.

```php
Event::make()->action('edit');
```

#### Set the model and record key
To mount the action with the correct record, we need to pass the model type and primary key of the record.

The model is also required if you want to display multiple types of events and have each be rendered differently (see customizing event content).

```php
$record = MyModel::find(1);
// 1. variant
Event::make($record);

// 2. variant
Event::make()
    ->model($record::class)
    ->key($record->getKey());
```

#### Passing custom data
You can pass any custom data to the event that you wish:
```php
Event::make()
->extendedProp('foo', 'bar')
// or
->extendedProps(['baz' => 'qux', 'quux' => 'corge']);
```

## Available Methods

### Refresh events
If you need to trigger a refresh of the events in the calendar, you can call `refreshRecords()` on the widget.

```php
$this->refreshRecords();
```

### Refresh resources
If you need to trigger a refresh of the resources in the calendar, you can call `refreshResources()` on the widget.

```php
$this->refreshResources();
```

### Set Option
To change any calendar option during runtime, you can use the `setOption()` method on the widget.

For example to programmatically change the date, you can use:
```php
$this->setOption('date', today()->addDay()->toIso8601String());
```

## Custom Event Content
By default, we use the default view from the calendar package. However, you are able to use your own by overriding the `getEventContent` method on your calendar widget class.

In order to keep things performant, the blade view is rendered **once** on the server and then re-used for every event. Thus, you **cannot** access the event data from the server side via Blade or Laravel, or do any server-side operations.

However, each event is wrapped in an alpine component, which exposes the event data that you can freely use using [AlpineJS](https://alpinejs.dev/).

If you only have one type of events or events that render the same way, you can simply return a view or a HtmlString from the getEventContent method:

```php
public function getEventContent(): null|string|array
{
    // return a blade view
    return view('calendar.event');
    
    // return a HtmlString
    return new HtmlString('<div>My event</div>');
}
```

Example of the `calendar.event` view blade file: 
```bladehtml
<div class="flex flex-col items-start">
    <span x-text="event.title"></span>
    <template x-for="user in event.extendedProps.users">
        <span x-text="user.name"></span>
    </template>
</div>
```

If you want to render events differently based on their model type, you can return an array like so:
```php
public function getEventContent(): null|string|array
{
    return [
        MyModel::class => view('calendar.my-model-event'),
        AnotherModel::class => view('calendar.another-model-event'),
    ];
}
```

## Custom resource label content
By default, we use the default view from the calendar package. However, you are able to use your own by overriding the `getResourceLabelContent` method on your calendar widget class.

```php
public function getResourceLabelContent(): null|string|array
{
    // return a blade view
    return view('calendar.resource');
    
    // return a HtmlString
    return new HtmlString('<div>My resource</div>');
}
```

## Customize the form schema
When an event triggers an action (such as view or edit actions), a modal with a form is mounted.

You can customize the form schema by overriding the `getSchema` method in your widget class:

```php
public function getSchema(?string $model = null): ?array
{
    // If you only work with one model type, you can ignore the $model parameter and simply return a schema
    return [
        TextInput::make('title')
    ];
    
    // If you have multiple model types on your calendar, you can return different schemas based on the $model property
    return match($model) {
        Foo::class => [
            TextInput::make('name'),
        ],
        Bar::class => [
            TextInput::make('title'),
            TextArea::make('description'),
        ],
    }
}
```

## Resources
Resource views (their names start with `resource`) allow you to group events into resources (such as rooms, projects, etc.).

To use resource views, you need to create resources and assign your events to these resources.

![Resources Screenshot 01](https://github.com/GuavaCZ/calendar/raw/main/docs/images/resources_screenshot_01.png)


### Creating resources
To create resources, you need to override the `getResources` method on your calendar widget class. Just like events, there are three options you can choose from to create resources:

```php
public function getResources(): Collection|array
{
    return [
        // Chainable object-oriented variant
        Resource::make('foo')
            ->title('Room 1'),
            
        // Array variant
        ['id' => 'bar', 'title' => 'Room 2'],
        
        // Eloquent model implementing the `Resourceable` interface
        MyRoom::find(1),
    ];
}
```

## Handling events

By default, the calendar is a view-only collection of events. You can enable more functionalities by configuring various events as described below.

### Event-click event
An event click event is triggered when an event in the calendar is clicked. By default, a click event mounts the `view` action.

To listen to click events, simply override the `eventClickEnabled` property:

```php
protected bool $eventClickEnabled = true;
```

You can set the default click action by overriding the `defaultEventClickAction` property of the widget. This simply needs to be the name of an action that you can freely define in your widget, like regular Filament actions:

```php
protected ?string $defaultEventClickAction = 'edit';
```

And that's it! As long as pass your model policy checks, an edit modal will be mounted when you click on an event.

If you want to handle the event click logic completely by yourself, you may override the `onEventClick` method:

```php
    public function onEventClick(array $info = []): void
{
    // do something on click
    // $info contains the event data:
    // $info['event'] - the event object
    // $info['view'] - the view object
}
```

### Event Resize event
A resize event is triggered when an event is resized at the ending edge of the event. This allows you to quickly modify the duration of an event.

To listen to resize events, simply override the `eventResizeEnabled` property:

```php
protected bool $eventResizeEnabled = true;
```

Except for resolving the (event) record the event is related to, there is no default action and it's up to you to implement the logic. To do that, override the `onEventResize` method:

```php
public function onEventResize(array $info = []): bool
{
    // Don't forget to call the parent method to resolve the event record
    parent::onEventResize($info);
     
    // Validate the data
    // Update the record ($this->getEventRecord())
    // $info contains the event data:
    // $info['event'] - the event object
    // $info['oldEvent'] - the event object before resizing
    // $info['endDelta'] - the difference in time between the old and new event
    
    // Return true if the event was resized successfully
    // Return false if the event was not resized and should be reverted on the client-side   
}
```

### Event Drag & Drop event
A drop event is triggered when an event is dragged and dropped to a different slot in the calendar. This allows you to quicky move the start (and end) date of an event.

To listen to drag and drop events, simply override the `eventDragEnabled` property:

```php
protected bool $eventDragEnabled = true;
```

Except for resolving the (event) record the event is related to, there is no default action and it's up to you to implement the logic. To do that, override the `onEventDrop` method:

```php
public function onEventDrop(array $info = []): bool
{
    // Don't forget to call the parent method to resolve the event record
    parent::onEventDrop($info); 
    
    // Validate the data
    // Update the record ($this->getEventRecord())
    // $info contains the event data:
    // $info['event'] - the event object
    // $info['oldEvent'] - the event object before resizing
    // $info['oldResource'] - the old resource object
    // $info['newResource'] - the new resource object
    // $info['delta'] - the duration object representing the amount of time the event was moved by
    // $info['view'] - the view object
    
    // Return true if the event was moved successfully
    // Return false if the event was not moved and should be reverted on the client-side
}
```

### Date Click event
A date click event is triggered when an date cell is clicked in the calendar.

To listen to date click events, simply override the `dateClickEnabled` property:

```php
protected bool $dateClickEnabled = true;
```

By default, nothing happens on date click. You can either use the `date click context menu feature` (more info below in the `Context Menu` section __[here](#date-click-context-menu)__) or implement your own logic, by overriding the `onDateClick` method:

```php
public function onDateClick(array $info = []): bool
{
    // Validate the data
    // $info contains the event data:
    // $info['date'] - the date clicked on
    // $info['dateStr'] - the date clicked on as a UTC string
    // $info['allDay'] - whether the date is an all-day slot
    // $info['view'] - the view object
    // $info['resource'] - the resource object
}
```

### Date Select event
A date select event is triggered when a date range is selected in the calendar.

To listen to date select events, simply override the `dateSelectEnabled` property:

```php
protected bool $dateSelectEnabled = true;
```

By default, nothing happens on date select. You can either use the `date select context menu feature` (more info below in the `Context Menu` section __[here](#date-select-context-menu)__) or implement your own logic, by overriding the `onDateSelect` method:

```php
public function onDateSelect(array $info = []): bool
{
    // Validate the data
    // $info contains the event data:
    // $info['start'] - the start date of the range
    // $info['startStr'] - the start date as an UTC string
    // $info['end'] - the end date of the range
    // $info['endStr'] - the end date as an UTC string
    // $info['allDay'] - whether the date is an all-day slot
    // $info['view'] - the view object
    // $info['resource'] - the resource object
}
```

### No-events-click event

A no-events-click event is applicable only on `list` views and is triggered when a user clicks on the `no events` cell. By default, this event does nothing and it's up to you to implement the logic.

To listen to no-events-click events, simply override the `noEventsClickEnabled` property:

```php
protected bool $noEventsClickEnabled = true;
```

To handle the no-events-click logic, override the `onNoEventsClick` method:

```php
public function onNoEventsClick(array $info = []): void
{
    // do something on click
    // $info contains the event data:
    // $info['view'] - the view object
}
```

## Context menu
Optionally you can add a context menu to your calendar, which allows you to create events by clicking on a date cell or by selecting a date/time range by dragging.

There are multiple places where you can use context menus at.

<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/context_menu_preview.mp4" type="video/mp4">
</video>

https://github.com/user-attachments/assets/a2641b40-9cbd-4c40-b360-7621caa86c40

<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/context_menu_preview_2.mp4" type="video/mp4">
</video>


https://github.com/user-attachments/assets/4996cc6a-7cee-4c7d-976a-60d3a4368f76

### Date click context menu
This context menu is triggered when a user clicks on a date cell in the calendar.

To enable the context menu, all you need to do is enable date clicks and implement the `getDateClickContextMenuActions` method:

For example:
```php
protected bool $dateClickEnabled = true;

public function getDateClickContextMenuActions(): array
{
    return [
        CreateAction::make('foo')
            ->model(Foo::class)
            ->mountUsing(fn ($arguments, $form) => $form->fill([
                'starts_at' => data_get($arguments, 'dateStr'),
                'ends_at' => data_get($arguments, 'dateStr'),
            ])),    
    ];
}
```

The mount using function is used to fill the form with the arguments from the calendar. It contains all information that vkurko/calendar provides in the `select` and `dateClick` events, but most importantly:
- `startStr` and `endStr` for range selection
- `dateStr` for date clicks


### Date select context menu
This context menu is triggered when a user selects on a date range in the calendar.

To enable the context menu, all you need to do is enable date selects and implement the `getDateSelectContextMenuActions` method:

For example:
```php
protected bool $dateSelectEnabled = true;

public function getDateSelectContextMenuActions(): array
{
    return [
        CreateAction::make('foo')
            ->model(Foo::class)
            ->mountUsing(fn ($arguments, $form) => $form->fill([
                'starts_at' => data_get($arguments, 'startStr'),
                'ends_at' => data_get($arguments, 'endStr'),
            ])),
    ];
}
```

### Event click context menu
This context menu is triggered when a user clicks on an event in the calendar.

To enable the context menu, all you need to do is enabled event Clicks and implement the `getEventClickContextMenuActions` method:

For example:
```php
protected bool $eventClickEnabled = true;

public function getEventClickContextMenuActions(): array
{
    return [
        $this->viewAction(),
        $this->editAction(),
        $this->deleteAction(),
    ];
}
```

### No events click context menu
This context menu is only rendered on `list` views and is triggered when a user clicks on the `no events` cell when there are no events.

To enable the context menu, all you need to do is implement the `getNoEventsClickContextMenuActions` method. Also, make sure that the `noEventsClickEnabled` property is set to `true`.

```php
public function getNoEventsClickContextMenuActions(): array
{
    return [
        CreateAction::make('foo')
            ->model(Foo::class)
    ];
}

```

<video width="320" height="240" controls>
  <source src="https://github.com/GuavaCZ/calendar/raw/main/docs/images/no_events_context_menu.mp4" type="video/mp4">
</video>

https://github.com/user-attachments/assets/7c2537d5-8acf-459f-a9a8-be02d4018448

## Customization
### Locale
By default, the calendar will use the app's locale.

The underlying calendar package doesn't support locales as a combination of language and region/country code, so locales such as `fr_CA` or `en_US` become invalid.

We attempt to resolve this by only using the first language part of the locale. If you still run into any issues with the localization, you can override the calendar's locale manually using the `locale` property:

```php
protected ?string $locale = 'en';
```

## Troubleshooting
### Context menu actions don't work
If you encounter issues with the context menu, either that the actions don't mount correctly or that the arguments array is empty, make sure that the name of the action is unique across the whole widget. If there is another action with the same name, it might be mounted instead of the one you want.

### Record vs Event record
When working with resource widgets, `$record` is the record of the currently opened resource record, whereas `$eventRecord` is the record of the calendar event (during event actions, context menus, etc.).

## Authorization
Due to security reasons, actions use Laravel's default authorization mechanism to check if user is allowed to perform actions.

This means that most likely your actions might not work when you add them (such as view or edit actions on event click). If that's the case, please create a policy for your model and add the necessary checks to the policy.

You can also overide the `authorize` method on your widget class and handle all authorization logic on your own.

```php
// $ability will contain the name of the action
public function authorize($ability, $arguments = []);
```

## Security measures
Keep in mind that a lot of the data in this package comes from the client side JavaScript and could be tampered with. Always validate the data on the server side and never trust the data from the client side.

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
- [Lukas Frey](https://github.com/GuavaCZ)
- [All Contributors](../../contributors)
- Spatie - Our package skeleton is a modified version of [Spatie's Package Skeleton](https://github.com/spatie/package-skeleton-laravel)
- [vkurko/calendar](https://github.com/vkurko/calendar) - free, open-source alternative to FullCalendar
- [saade/filament-fullcalendar](https://github.com/saade/filament-fullcalendar) - heavy inspiration for this package

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
