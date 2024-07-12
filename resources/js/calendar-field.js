export default function calendarField({
                                              events = [],
                                              eventContent = null,
                                              selectable = false,
                                          }) {
    return {

        init: async function () {
            let self = this;
            console.log('Initialize');
            let options = {
                view: 'dayGridMonth',
                events: events,
                locale: 'cs',
                firstDay: 1,
                // eventClick: (info) => this.$wire.test(info)
                eventClick: (info) => console.log(info),
                dateClick: () => {
                    console.log('No events click');
                }
            };

            if (eventContent !== null) {
                options.eventContent = (info) => {
                    return {
                        html: self.getEventContent(info),
                    };
                }
            }

            if (selectable) {
                options.selectable = true;
            }

            this.ec = new EventCalendar(document.getElementById('calendar'), options);
            window.addEventListener('ec-add-event', this.addEvent);
        },

        addEvent: function(event) {
            this.ec.addEvent(event);
        },

        getEventContent: function (info) {
            let container = document.createElement('div');
            container.innerHTML = eventContent;
            // Perform the replacements
            // this.replacePlaceholders(container, info);
            container.setAttribute('x-data', JSON.stringify(info));

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
