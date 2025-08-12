export default function calendar({
    eventAssetUrl,
                                     eventClickEnabled = false,
    eventContent = null,
                                 }
) {
    return {

        init: function () {
            this.mountCalendar()
        },

        mountCalendar: function () {
            let ec = EventCalendar.create(
                this.$el,
                // this.$el.querySelector('[data-calendar]'),
                this.getSettings(),
            )
        },

        getSettings: function () {
            let settings = {
                view: 'dayGridMonth',
                eventSources: [
                    {
                        events: (fetchInfo) => {
                            return this.$wire.getEventsJs(fetchInfo)
                        }
                    }
                ],
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

            if (eventClickEnabled) {
                settings.eventClick = (info) => {
                    const component = Alpine.$data(info.el)
                    component.onClick(info)
                };
            }
            // if (eventClickEnabled) {
                // settings.eventClick = (info) => {
                //     if (info.event.extendedProps.url) {
                //         const target = info.event.extendedProps.url_target ?? '_blank'
                //         window.open(target, '_blank')
                //         return
                //     }
                //
                //     // if (hasEventClickContextMenu) {
                //         // todo
                //     // }
                //
                //     this.$wire.onEventClick({
                //         event: info.event,
                //         view: info.view,
                //     })
                // }
            // }

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

            return settings
        },


        getEventContent: function (info) {
            if (typeof eventContent === 'string') {
                return this.wrapContent(eventContent, info)
            }

            if (typeof eventContent === 'object') {
                const model = info.event.extendedProps.model
                const content = eventContent[model]

                if (content === undefined) {
                    return undefined
                }

                return this.wrapContent(content, info)
            }

            return undefined
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
    }
}
