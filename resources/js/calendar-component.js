export default function calendarComponent({
                                              view = 'dayGridMonth',
                                              locale = 'en',
                                              firstDay = 1,
                                              events = [],
                                              eventContent = null,
                                              selectable = false,
                                              onEventClick = false,
                                              dayMaxEvents = false,
                                              moreLinkContent = null,
                                              resources = [],
                                              options = {},
                                          }) {
    return {

        init: async function () {
            let self = this;
            let settings = {
                view: view,
                resources: resources,
                eventSources: [
                    {
                        events: (fetchInfo, successCallback, failureCallback) => {
                            console.log('load');
                            return this.$wire.getEventsJs(fetchInfo)
                        }
                    }
                ],
                locale: locale,
                firstDay: firstDay,
                dayMaxEvents: dayMaxEvents,
                selectable: false,
                editable: false,
                eventStartEditable: false,
                eventClick: (info) => {
                    if (info.event.extendedProps.url) {
                        const target = info.event.extendedProps.url_target ?? '_blank';
                        window.open(info.event.extendedProps.url, target);
                    } else if (onEventClick) {
                        this.$wire.onEventClick(info);
                    }
                }
            };

            if (eventContent !== null) {
                settings.eventContent = (info) => {
                    return {
                        html: self.getEventContent(info),
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

            this.ec = new EventCalendar(this.$el.querySelector('div'), {
                ...settings,
                ...options
            });
            window.addEventListener('ec-add-event', this.addEvent);
            window.addEventListener('calendar--refresh', () => this.ec.refetchEvents())
        },

        addEvent: function (event) {
            this.ec.addEvent(event);
        },

        getEventContent: function (info) {
            if (typeof eventContent === 'string') {
                console.log('eventContent', eventContent);
                return this.wrapContent(eventContent, info);
            }

            if (typeof eventContent === 'object') {
                const model = info.event.extendedProps.model;
                const content = eventContent[model];
                return this.wrapContent(content, info);
            }
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
