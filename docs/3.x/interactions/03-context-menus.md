---
title: Context menus
---

# Context menus

Instead of handling clicks yourself, you can let us render a context menu at the mouse cursor and populate it with actions.

There is one method per click type. Implement the ones you need and return the actions to show:

```php
protected function getDateClickContextMenuActions(): array
{
    return [
        $this->createFooAction(),
        $this->createBarAction(),
    ];
}

protected function getDateSelectContextMenuActions(): array
{
    return [
        $this->createFooAction(),
    ];
}

protected function getEventClickContextMenuActions(): array
{
    return [
        $this->viewAction(),
        $this->editAction(),
        $this->deleteAction(),
    ];
}

protected function getNoEventsClickContextMenuActions(): array
{
    return [
        $this->createFooAction(),
    ];
}
```

The corresponding interaction has to be enabled for the menu to show up, for example `dateClickEnabled` for the date click menu. Please read [clicks and selections](04-clicks-and-selections.md) for the individual flags.

> [!NOTE]
> The context menu has a higher priority than your own handlers. If the method returns a non-empty array, the menu always takes precedence over the corresponding `on*` handler.

## Troubleshooting

If your context menu actions mount the wrong thing, make sure the name of each action is unique across the whole widget. If there is another action with the same name, it might be mounted instead of the one you want.
