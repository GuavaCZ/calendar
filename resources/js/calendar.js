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
                                     resources = null,
                                     resourceLabelContent = null,
                                     theme = null,
                                     options = {},
                                     eventAssetUrl,
                                 }
) {
    return {

        init: function () {
            const ec = this.mountCalendar()

            window.addEventListener('calendar--refresh', () => {
                ec.refetchEvents()
            })

            this.$wire.on('calendar--set', (data) => {
                ec.setOption(data.key, data.value)
            })
        },

        mountCalendar: function () {
            return EventCalendar.create(
                this.$el.querySelector('[data-calendar]'),
                this.getSettings(),
            )
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
                const durationEditable = info.event.durationEditable
                let enabled = eventResizeEnabled

                if (durationEditable !== undefined) {
                    enabled = durationEditable
                }

                if (enabled) {
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
                }
            };

            settings.eventDrop = async (info) => {
                const startEditable = info.event.startEditable
                let enabled = eventDragEnabled

                if (startEditable !== undefined) {
                    enabled = startEditable
                }

                if (enabled) {
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
            }

            settings.eventDidMount = (info) => {
                info.el.setAttribute('x-load')
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
