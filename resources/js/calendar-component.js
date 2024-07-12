export default function calendarComponent({
                                              events = [],
                                              eventContent = null,
                                              selectable = false,
                                              onEventClick = false,
                                              options = {},
                                              moreLinkContent = null,
                                          }) {
    return {

        init: async function () {
            let self = this;
            let settings = {
                view: 'dayGridMonth',
                // events: events,
                eventSources: [
                    {
                        events: (fetchInfo, successCallback, failureCallback) => this.$wire.getEventsJs()
                    }
                ],
                locale: 'en',
                firstDay: 1,
                dayMaxEvents: false,
                selectable: false,
                editable: false,
                eventStartEditable: false,
                eventClick: (info) => {
                    if (info.event.extendedProps.url) {
                        window.open(info.event.extendedProps.url, '_blank');
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

            if (selectable) {
                settings.selectable = true;
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
            // Perform the replacements
            // this.replacePlaceholders(container, info);
            container.setAttribute('x-data', JSON.stringify(info));
            container.classList.add('w-full');

            // Get the modified HTML
            return container.outerHTML;
        },

        // Function to perform replacements
        replacePlaceholders: function (element, replacements, parentKey = '') {
            Object.keys(replacements).forEach(key => {
                const nestedKey = parentKey ? `${parentKey}.${key}` : key;
                const value = replacements[key];

                if (typeof value === 'object') {
                    // If value is an object, perform recursive replacements
                    this.replacePlaceholders(element, value, nestedKey);
                } else {
                    // Perform the replacement
                    const placeholder = `{${nestedKey}}`;
                    element.innerHTML = element.innerHTML.replace(new RegExp(placeholder, 'g'), value);
                }
            });
        }
    }
}
