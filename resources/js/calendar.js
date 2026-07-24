import {
    createCalendar,
    destroyCalendar,
    DayGrid,
    Interaction,
    List,
    ResourceTimeGrid,
    ResourceTimeline,
    TimeGrid,
} from '@event-calendar/core'

// Every view in Guava\Calendar\Enums\CalendarViewType is covered, plus Interaction, which is what
// makes dateClick/select/drag/resize fire at all. Users can also pass arbitrary views through
// `options`, so the full set is registered rather than only the ones a given widget declares.
const plugins = [DayGrid, Interaction, List, ResourceTimeGrid, ResourceTimeline, TimeGrid]

export default function calendar({
                                     view = 'dayGridMonth',
                                     locale = 'en',
                                     firstDay = 1,
                                     dayMaxEvents = false,
                                     eventContent = null,
                                     eventClickEnabled = false,
                                     eventDragEnabled = false,
                                     eventResizeEnabled = false,
                                     noEventsClickEnabled = false,
                                     dateClickEnabled = false,
                                     dateSelectEnabled = false,
                                     datesSetEnabled = false,
                                     viewDidMountEnabled = false,
                                     eventAllUpdatedEnabled = false,
                                     hasDateClickContextMenu = null,
                                     hasDateSelectContextMenu = null,
                                     hasEventClickContextMenu = null,
                                     hasNoEventsClickContextMenu = null,
                                     // The library treats a non-array `resources` as a remote source
                                     // and dereferences it, so this must never default to null.
                                     resources = [],
                                     resourceLabelContent = null,
                                     theme = null,
                                     options = {},
                                     eventAssetUrl,
                                 }
) {
    return {

        init: function () {
            const ec = this.mountCalendar()

            this.syncDarkMode()

            window.addEventListener('calendar--refresh', () => {
                ec.refetchEvents()
            })

            this.$wire.on('calendar--set', (data) => {
                ec.setOption(data.key, data.value)
            })
        },

        // Toggle event-calendar's `ec-dark` class in sync with Filament's dark mode.
        // Since v5, vkurko/calendar only applies its dark palette when this class is
        // present, so we mirror the `.dark` class Filament sets on <html> and keep it
        // in sync when the user switches theme at runtime.
        syncDarkMode: function () {
            const root = document.documentElement

            const apply = () => this.$el.classList.toggle('ec-dark', root.classList.contains('dark'))

            apply()

            const observer = new MutationObserver(apply)
            observer.observe(root, { attributes: true, attributeFilter: ['class'] })

            this.$el.addEventListener('alpine:destroy', () => observer.disconnect())
        },

        mountCalendar: function () {
            const ec = createCalendar(
                this.$el.querySelector('[data-calendar]'),
                plugins,
                this.getSettings(),
            )

            this.$el.addEventListener('alpine:destroy', () => destroyCalendar(ec))

            return ec
        },

        getSettings: function () {
            let settings = {
                view: view,
                locale: locale,
                firstDay: firstDay,
                dayMaxEvents: dayMaxEvents,
                eventSources: [
                    {
                        events: (fetchInfo) => this.$wire.getEventsJs({
                            ...fetchInfo,
                            tzOffset: -new Date().getTimezoneOffset(),
                        })
                    }
                ],
                resources: resources,
                selectable: dateSelectEnabled,
                eventStartEditable: eventDragEnabled,
                eventDurationEditable: eventResizeEnabled,
                dayCellFormat: (date) => date.getDate().toString()
            }

            if (eventContent !== null) {
                settings.eventContent = (info) => {
                    const model = info.event.extendedProps.model
                    const content = eventContent[model] ?? eventContent['_default']

                    if (content === undefined) {
                        return undefined
                    }

                    return {
                        html: content,
                    }
                }
            }

            if (resourceLabelContent !== null) {
                settings.resourceLabelContent = (info) => {
                    const model = info.resource.extendedProps.model
                    const content = resourceLabelContent[model] ?? resourceLabelContent['_default']

                    if (content === undefined) {
                        return undefined
                    }

                    return {
                        html: this.wrapContent(content, info),
                    }
                };
            }

            if (dateClickEnabled) {
                settings.dateClick = (info) => {
                    const data = {
                        date: info.date,
                        dateStr: info.dateStr,
                        allDay: info.allDay,
                        view: info.view,
                        resource: info.resource,
                        tzOffset: -new Date().getTimezoneOffset()
                    }

                    if (hasDateClickContextMenu) {
                        this.openContextMenu(info.jsEvent, data, 'dateClick')
                    } else {
                        this.$wire.onDateClickJs(data)
                    }
                }
            }

            if (dateSelectEnabled) {
                settings.select = (info) => {
                    const data = {
                        start: info.start,
                        startStr: info.startStr,
                        end: info.end,
                        endStr: info.endStr,
                        allDay: info.allDay,
                        view: info.view,
                        resource: info.resource,
                        tzOffset: -new Date().getTimezoneOffset()
                    }

                    if (hasDateSelectContextMenu) {
                        this.openContextMenu(info.jsEvent, data, 'dateSelect')
                    } else {
                        this.$wire.onDateSelectJs(data)
                    }
                }
            }

            if (datesSetEnabled) {
                settings.datesSet = (info) => {
                    this.$wire.onDatesSetJs({
                        start: info.start,
                        startStr: info.startStr,
                        end: info.end,
                        endStr: info.endStr,
                        view: info.view,
                        tzOffset: -new Date().getTimezoneOffset()
                    })
                }
            }

            if (eventClickEnabled) {
                settings.eventClick = (info) => {
                    const component = Alpine.$data(info.el)
                    component.onClick(info)
                }
            }

            settings.eventResize = async (info) => {
                // The global flag is authoritative. EventCalendar's global eventDurationEditable (and
                // the per-event durationEditable:false we emit for locked events) already decides
                // whether this fires, so a per-event value can only restrict, never enable.
                if (! eventResizeEnabled) {
                    info.revert()
                    return
                }

                await this.$wire.onEventResizeJs({
                    event: info.event,
                    oldEvent: info.oldEvent,
                    endDelta: info.endDelta,
                    view: info.view,
                    tzOffset: -new Date().getTimezoneOffset()
                }).then((result) => {
                    if (result === false) {
                        info.revert()
                    }
                })
            };

            settings.eventDrop = async (info) => {
                // The global flag is authoritative. EventCalendar's global eventStartEditable (and the
                // per-event startEditable:false we emit for locked events) already decides whether this
                // fires, so a per-event value can only restrict, never enable.
                if (! eventDragEnabled) {
                    info.revert()
                    return
                }

                await this.$wire.onEventDropJs({
                    event: info.event,
                    oldEvent: info.oldEvent,
                    oldResource: info.oldResource,
                    newResource: info.newResource,
                    delta: info.delta,
                    view: info.view,
                    tzOffset: -new Date().getTimezoneOffset()
                }).then((result) => {
                    if (result === false) {
                        info.revert()
                    }
                })
            }

            settings.eventDidMount = (info) => {
                // `setAttribute` requires a value; passing only the name throws a TypeError and
                // would abort before x-load-src/x-data are applied, leaving the event without its
                // Alpine component. Blade writes the bare `x-load` attribute, i.e. an empty value.
                info.el.setAttribute('x-load', '')
                info.el.setAttribute('x-load-src', eventAssetUrl)
                info.el.setAttribute('x-data', `calendarEvent({
                    event: ${JSON.stringify(info.event)},
                    timeText: "${info.timeText}",
                    view: ${JSON.stringify(info.view)},
                    hasContextMenu: ${hasEventClickContextMenu},
                })`)
            }

            if (noEventsClickEnabled) {
                settings.noEventsClick = (info) => {
                    const data = {
                        view: info.view,
                        tzOffset: -new Date().getTimezoneOffset()
                    }

                    if (hasNoEventsClickContextMenu) {
                        this.openContextMenu(info.jsEvent, data, 'noEventsClick')
                    } else {
                        this.$wire.onNoEventsClickJs(data)
                    }
                }
            }

            if (viewDidMountEnabled) {
                settings.viewDidMount = (info) => {
                    this.$wire.onViewDidMountJs({
                        view: info.view,
                        tzOffset: -new Date().getTimezoneOffset()
                    })
                }
            }

            if (eventAllUpdatedEnabled) {
                settings.eventAllUpdated = (info) => {
                    this.$wire.onEventAllUpdatedJs({
                        view: info.view,
                        tzOffset: -new Date().getTimezoneOffset()
                    })
                }
            }

            if (theme) {
                settings.theme = function (defaultTheme) {
                    return {
                        ...defaultTheme,
                        ...theme
                    }
                }
            }

            return {
                ...settings,
                ...options,
            }
        },

        wrapContent: function (content, info) {
            let container = document.createElement('div')
            container.innerHTML = content

            // Add alpine data and classes
            container.setAttribute('x-data', JSON.stringify(info))
            container.classList.add('w-full')

            // Get the modified HTML
            return container.outerHTML
        },

        openContextMenu: function (jsEvent, data, context) {
            const element = document.querySelector('[calendar-context-menu]')
            const contextMenu = Alpine.$data(element)
            contextMenu.loadActions(context, data)
            contextMenu.openMenu(jsEvent)
        }
    }
}
