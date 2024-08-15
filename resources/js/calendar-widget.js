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
                                           dateSelectEnabled = false,
                                           dateClickEnabled = false,
                                           viewDidMountEnabled = false,
                                           dayMaxEvents = false,
                                           moreLinkContent = null,
                                           resources = [],
                                           hasDateClickContextMenu = false,
                                           hasDateSelectContextMenu = false,
                                           hasEventClickContextMenu = false,
                                           hasNoEventsClickContextMenu = false,
                                           options = {},
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

            if (dateClickEnabled) {
                settings.dateClick = (info) => {
                    if (hasDateClickContextMenu) {
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
                    if (info.event.extendedProps.url) {
                        const target = info.event.extendedProps.url_target ?? '_blank';
                        window.open(info.event.extendedProps.url, target);
                    } else if (hasEventClickContextMenu) {
                        self.$el.querySelector('[calendar-context-menu]').dispatchEvent(new CustomEvent('calendar--open-menu', {
                            detail: {
                                mountData: {
                                    event: info.event,
                                    view: info.view,
                                },
                                jsEvent: info.jsEvent,
                                context: 'eventClick',
                            },
                        }));
                    } else {
                        this.$wire.onEventClick({
                            event: info.event,
                            view: info.view,
                        });
                    }
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

            this.ec = new EventCalendar(this.$el.querySelector('div'), {
                ...settings,
                ...options
            });

            window.addEventListener('calendar--refresh', () => this.ec.refetchEvents())
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

        getResourceLabelContent: function(info) {
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
