/**
 * The offset the browser was at on a given date, in minutes east of UTC.
 *
 * Not today's offset: a date in the browser's other daylight saving phase sits
 * at a different offset from the one now in force, and the server rebuilds the
 * wall time from whatever this sends.
 */
const tzOffsetAt = (date) => -new Date(date ?? Date.now()).getTimezoneOffset()

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
                tzOffset: tzOffsetAt(info.event?.start)
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
