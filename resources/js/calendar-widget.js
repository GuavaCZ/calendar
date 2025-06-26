import {createEventContent} from "@event-calendar/core";

export default function calendarWidget({
                                           view = 'dayGridMonth',
                                           locale = 'en',
                                           firstDay = 1,
                                           events = [],
                                           eventContent = null,
                                           resourceLabelContent = null,
                                           selectable = false,
                                           eventClickEnabled = false,
                                           eventDragEnabled = false,
                                           eventResizeEnabled = false,
                                           noEventsClickEnabled = false,
                                           dateClickEnabled = false,
                                           dateSelectEnabled = false,
                                           datesSetEnabled = false,
                                           viewDidMountEnabled = false,
                                           eventAllUpdatedEnabled = false,
                                           dayMaxEvents = false,
                                           moreLinkContent = null,
                                           resources = [],
                                           hasDateClickContextMenu = false,
                                           hasDateSelectContextMenu = false,
                                           hasEventClickContextMenu = false,
                                           hasNoEventsClickContextMenu = false,
                                           options = {},
                                           dayHeaderFormat = null,
                                           slotLabelFormat = null,
                                           eventAssetUrl,
                                       }) {
    return {

        calendarEl: null,

        init: async function () {
            this.calendarEl = this.$el;
            let self = this;
            let settings = {
                view: view,
                resources: resources,
                eventSources: [
                    {
                        events: (fetchInfo, successCallback, failureCallback) => {
                            return this.$wire.getEventsJs(fetchInfo)
                        }
                    }
                ],
                locale: locale,
                firstDay: firstDay,
                dayMaxEvents: dayMaxEvents,
                selectable: dateSelectEnabled,
                eventStartEditable: eventDragEnabled,
                eventDurationEditable: eventResizeEnabled,
            };

            if (dayHeaderFormat) {
                settings.dayHeaderFormat = dayHeaderFormat;
            }

            if (slotLabelFormat) {
                settings.slotLabelFormat = slotLabelFormat;
            }


            if (dateClickEnabled) {
                settings.dateClick = (info) => {
                    if (hasDateClickContextMenu) {
                        const element = document.querySelector('[calendar-context-menu]')
                        const menu = Alpine.$data(element)
                        menu.loadActions('dateClick', {
                            date: info.date,
                            dateStr: info.dateStr,
                            allDay: info.allDay,
                            view: info.view,
                            resource: info.resource,
                        })
                        menu.openMenu(info.jsEvent)
                        return;
                        self.$el.querySelector('[calendar-context-menu]').dispatchEvent(new CustomEvent('calendar--open-menu', {
                            detail: {
                                mountData: {
                                    date: info.date,
                                    dateStr: info.dateStr,
                                    allDay: info.allDay,
                                    view: info.view,
                                    resource: info.resource,
                                },
                                jsEvent: info.jsEvent,
                                dayEl: info.dayEl,
                                context: 'dateClick',
                            },
                        }));
                    } else {
                        this.$wire.onDateClick({
                            date: info.date,
                            dateStr: info.dateStr,
                            allDay: info.allDay,
                            view: info.view,
                            resource: info.resource,
                        });
                    }
                };
            }

            if (dateSelectEnabled) {
                settings.select = (info) => {
                    if (hasDateSelectContextMenu) {
                        self.$el.querySelector('[calendar-context-menu]').dispatchEvent(new CustomEvent('calendar--open-menu', {
                            detail: {
                                mountData: {
                                    start: info.start,
                                    startStr: info.startStr,
                                    end: info.end,
                                    endStr: info.endStr,
                                    allDay: info.allDay,
                                    view: info.view,
                                    resource: info.resource,
                                },
                                jsEvent: info.jsEvent,
                                context: 'dateSelect',
                            },
                        }));
                    } else {
                        this.$wire.onDateSelect({
                            start: info.start,
                            startStr: info.startStr,
                            end: info.end,
                            endStr: info.endStr,
                            allDay: info.allDay,
                            view: info.view,
                            resource: info.resource,
                        });
                    }
                };
            }

            if (datesSetEnabled) {
                settings.datesSet = (info) => {
                    this.$wire.onDatesSet({
                        start: info.start,
                        startStr: info.startStr,
                        end: info.end,
                        endStr: info.endStr,
                        view: info.view,
                    });
                };
            }

            if (eventContent !== null) {
                settings.eventContent = (info) => {
                    const content = self.getEventContent(info);

                    if (content === undefined) {
                        return undefined;
                    }

                    return {
                        html: content,
                    };
                }
            }

            if (moreLinkContent !== null) {
                settings.moreLinkContent = (arg) => {
                    return {
                        html: self.getMoreLinkContent(arg),
                    };
                }
            }

            if (resourceLabelContent !== null) {
                settings.resourceLabelContent = (info) => {
                    const content = self.getResourceLabelContent(info);

                    if (content === undefined) {
                        return undefined;
                    }

                    return {
                        html: content,
                    };
                };
            }

            if (eventClickEnabled) {
                settings.eventClick = (info) => {
                    const component = Alpine.$data(info.el)
                    component.onClick(info)
                };
            }

            if (noEventsClickEnabled) {
                settings.noEventsClick = (info) => {
                    if (hasNoEventsClickContextMenu) {
                        self.$el.querySelector('[calendar-context-menu]').dispatchEvent(new CustomEvent('calendar--open-menu', {
                            detail: {
                                mountData: {
                                    view: info.view,
                                },
                                jsEvent: info.jsEvent,
                                context: 'noEventsClick',
                            },
                        }));
                    } else {
                        this.$wire.onNoEventsClick({
                            view: info.view,
                        });
                    }
                }
            }

            settings.eventResize = async (info) => {
                const durationEditable = info.event.durationEditable;
                let enabled = eventResizeEnabled;

                if (durationEditable !== undefined) {
                    enabled = durationEditable;
                }

                if (enabled) {
                    await this.$wire.onEventResize({
                        event: info.event,
                        oldEvent: info.oldEvent,
                        endDelta: info.endDelta,
                        view: info.view,
                    }).then((result) => {
                        if (result === false) {
                            info.revert();
                        }
                    });
                }
            };

            settings.eventDrop = async (info) => {
                const startEditable = info.event.startEditable;
                let enabled = eventDragEnabled;

                if (startEditable !== undefined) {
                    enabled = startEditable;
                }

                if (enabled) {
                    await this.$wire.onEventDrop({
                        event: info.event,
                        oldEvent: info.oldEvent,
                        oldResource: info.oldResource,
                        newResource: info.newResource,
                        delta: info.delta,
                        view: info.view,
                    }).then((result) => {
                        if (result === false) {
                            info.revert();
                        }
                    });
                }
            };


            if (viewDidMountEnabled) {
                settings.viewDidMount = (view) => {
                    this.$wire.onViewDidMount({
                        view: view,
                    });
                };
            }

            if (eventAllUpdatedEnabled) {
                settings.eventAllUpdated = (info) => {
                    this.$wire.onEventAllUpdated({
                        info: info,
                    });
                };
            }

            settings.eventDidMount = (info) => {
                info.el.setAttribute('x-load')
                info.el.setAttribute('x-load-src', eventAssetUrl)
                info.el.setAttribute('x-data', `event({
                    event: ${JSON.stringify(info.event)},
                    timeText: "${info.timeText}",
                    view: ${JSON.stringify(info.view)},
                    hasEventClickContextMenu: ${hasEventClickContextMenu},
                })`)
            }

            this.ec = EventCalendar.create(this.$el.querySelector('div'), {
                ...settings,
                ...options
            });

            window.addEventListener('calendar--refresh', () => {
                this.ec.refetchEvents();
            });

            this.$wire.on('calendar--set', (data) => {
                this.ec.setOption(data.key, data.value);
            });
        },

        getEventContent: function (info) {
            if (typeof eventContent === 'string') {
                return this.wrapContent(eventContent, info);
            }

            if (typeof eventContent === 'object') {
                const model = info.event.extendedProps.model;
                const content = eventContent[model];

                if (content === undefined) {
                    return undefined;
                }

                return this.wrapContent(content, info);
            }

            return undefined;
        },

        getResourceLabelContent: function (info) {
            if (typeof resourceLabelContent === 'string') {
                return this.wrapContent(resourceLabelContent, info);
            }

            if (typeof resourceLabelContent === 'object') {
                const model = info.event.extendedProps.model;
                const content = resourceLabelContent[model];

                if (content === undefined) {
                    return undefined;
                }

                return this.wrapContent(content, info);
            }

            return undefined;
        },

        getMoreLinkContent: function (info) {
            return this.wrapContent(moreLinkContent, info);
        },

        wrapContent: function (content, info) {
            let container = document.createElement('div');
            container.innerHTML = content;

            // Add alpine data and classes
            container.setAttribute('x-data', JSON.stringify(info));
            container.classList.add('w-full');

            // Get the modified HTML
            return container.outerHTML;
        },
    }
}
