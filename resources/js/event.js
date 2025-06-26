export default function event({
                                  event,
                                  timeText,
                                  view,
                                  hasEventClickContextMenu,
                              }
) {
    return {
        event,
        contextMenu: null,

        init: function () {
            this.initializeContextMenu()
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
            if (this.event.extendedProps.url) {
                window.open(
                    this.event.extendedProps.url,
                    this.event.extendedProps.url_target ?? '_blank'
                );
            } else if (hasEventClickContextMenu) {
                this.contextMenu.loadActions('eventClick', {event: this.event})
                this.contextMenu.openMenu(info.jsEvent)
            } else {
                this.$wire.onEventClick({
                    event: info.event,
                    view: info.view,
                });
            }
        },

        testing: function () {
            console.log('testing')
        }
    }
}
