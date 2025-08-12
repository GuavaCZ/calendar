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

        init: function () {
            if (hasContextMenu) {
                this.initializeContextMenu()
            }
            // Preloading
            // this.$el.addEventListener('mouseenter', () => {
            //     this.contextMenu.loadActions(this.event)
            // })
        },

        initializeContextMenu: function () {
            const element = document.querySelector('[calendar-context-menu]')
            this.contextMenu = Alpine.$data(element)
        },

        /**
         * Called when an event is clicked, if event clicking is enabled in the calendar.
         * @param info
         */
        onClick: function (info) {
            if (info.event.extendedProps.url) {
                window.open(
                    this.event.extendedProps.url,
                    this.event.extendedProps.url_target ?? '_blank'
                )
                return
            }

            if (hasContextMenu) {
                this.contextMenu.loadActions('eventClick', {event: this.event})
                this.contextMenu.openMenu(
                    info.jsEvent,
                    this.$el
                )
                return
            }

            this.$wire.onEventClickJs({
                event: info.event,
                view: info.view,
                tzOffset: -new Date().getTimezoneOffset()
            })
        },
    }
}
