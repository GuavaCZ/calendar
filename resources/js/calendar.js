export default function calendar({
                                     eventClickEnabled = false,
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

            if (eventClickEnabled) {
                settings.eventClick = (info) => {
                    if (info.event.extendedProps.url) {
                        const target = info.event.extendedProps.url_target ?? '_blank'
                        window.open(target, '_blank')
                        return
                    }

                    // if (hasEventClickContextMenu) {
                        // todo
                    // }

                    this.$wire.onEventClick({
                        event: info.event,
                        view: info.view,
                    })
                }
            }

            return settings
        }
    }
}
