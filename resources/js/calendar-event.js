export default function calendarEvent({
                                          event,
                                          timeText,
                                          view,
                                          hasContextMenu,
                                      }
) {
    return {
        event,
        contextMenu: null,
        widget: null,

        init: function () {
            // Scope lookups to this event's own calendar, not the whole page.
            this.widget = this.$el.closest('[data-calendar-widget]')

            if (hasContextMenu) {
                this.initializeContextMenu()
            }
            this.$el.setAttribute('data-event-id', event.id)

            const segments = () => this.widget.querySelectorAll(
                `.ec-event[data-event-id="${event.id}"]`
            )

            this.$el.addEventListener('mouseenter', () => {
                segments().forEach(el => el.classList.add('gu-hover'))
            })

            this.$el.addEventListener('mouseleave', () => {
                segments().forEach(el => el.classList.remove('gu-hover'))
            })
        },

        initializeContextMenu: function () {
            const element = this.widget.querySelector('[calendar-context-menu]')
            this.contextMenu = Alpine.$data(element)
        },

        /**
         * Called when an event is clicked, if event clicking is enabled in the calendar.
         * @param info
         */
        onClick: async function (info) {
            if (info.event.extendedProps.url) {
                window.open(
                    this.event.extendedProps.url,
                    this.event.extendedProps.url_target ?? '_blank'
                )
                return
            }

            const data = {
                event: info.event,
                view: info.view,
                tzOffset: -new Date().getTimezoneOffset()
            }

            if (hasContextMenu) {
                const position = {
                    clientX: info.jsEvent.clientX,
                    clientY: info.jsEvent.clientY,
                    pageX: info.jsEvent.pageX,
                    pageY: info.jsEvent.pageY,
                }

                const actions = await this.contextMenu.loadActions('eventClick', data, this.$el)

                if (actions.length) {
                    this.contextMenu.openMenu(position, this.$el)
                }

                return
            }

            this.$wire.onEventClickJs(data)
        },
    }
}
