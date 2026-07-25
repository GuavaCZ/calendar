export default function calendarContextMenu({
                                                getContextMenuActionsUsing,
                                            }) {
    return {
        open: false,

        size: {
            width: 0,
            height: 0,
        },
        position: {
            x: 0,
            y: 0,
        },
        mountData: {},
        context: null,
        actions: [],
        isLoading: false,
        onCloseCallback: null,

        // Identifies the newest request so a slow response from a previous click can be discarded.
        requestId: 0,
        loadingTimer: null,

        // How long a request may run before the click gets a visible loading hint. Below this the
        // response usually arrives first, so nothing is shown and there is nothing to take back.
        loadingDelay: 150,

        menu: {
            ['x-show']() {
                // Callers only open the menu once actions are known, so an empty panel cannot
                // reach the screen.
                return this.open && this.actions.length > 0
            },
            ['x-bind:style']() {
                return `
                    position: absolute;
                    z-index: 40;
                    top: ${this.position.y}px;
                    left: ${this.position.x}px;
                `
            },
            ['x-on:click.away']() {
                this.closeMenu()
            }
        },

        init: async function () {
            const menu = this.$el.querySelector('[x-bind="menu"]')
            this.size = {
                width: menu.offsetWidth,
                height: menu.offsetHeight,
            }

            this.$el.addEventListener('calendar--open-menu', (event) => this.openMenu(event))
        },

        // Returns the actions so the caller can skip opening an empty menu.
        loadActions: async function (context, data = {}, eventElement = null) {
            const requestId = ++this.requestId

            // Drop any hint left over from a click we are no longer opening.
            this.hideLoading()

            this.isLoading = true
            this.actions = []
            this.loadingTimer = setTimeout(() => this.showLoading(eventElement), this.loadingDelay)

            try {
                const actions = await getContextMenuActionsUsing(context, data)

                // A newer click has already taken over; this response is stale.
                if (requestId !== this.requestId) {
                    return []
                }

                this.actions = actions

                return actions
            } finally {
                if (requestId === this.requestId) {
                    this.isLoading = false
                    this.hideLoading()
                }
            }
        },

        showLoading: function (eventElement) {
            this.widget().classList?.add('gu-loading')
            eventElement?.classList.add('gu-context-menu-loading')
        },

        hideLoading: function () {
            clearTimeout(this.loadingTimer)
            this.widget().classList?.remove('gu-loading')

            // Clear every event, not just the last one: a superseded click can leave its own behind.
            this.widget().querySelectorAll('.ec-event.gu-context-menu-loading').forEach(
                el => el.classList.remove('gu-context-menu-loading')
            )
        },

        openMenu: async function (event, eventElement = null) {
            this.$nextTick(() => {
                const clientX = event.clientX;
                const clientY = event.clientY;
                const pageX = event.pageX;
                const pageY = event.pageY;

                const offsetX = clientX + this.size.width > window.innerWidth
                    ? clientX + this.size.width - window.innerWidth
                    : 0;
                const offsetY = clientY + this.size.height > window.innerHeight
                    ? clientY + this.size.height - window.innerHeight
                    : 0;

                this.position.x = pageX - offsetX;
                this.position.y = pageY - offsetY;
                this.open = true;

                if (eventElement) {
                    const eventId = eventElement.getAttribute('data-event-id')

                    this.widget().querySelectorAll(`.ec-event[data-event-id="${eventId}"]`).forEach(
                        el => el.classList.add('gu-context-menu-open')
                    )
                }
            });
        },

        // Scope highlighting to this menu's own calendar, not the whole page.
        widget: function () {
            return this.$el.closest('[data-calendar-widget]') ?? document
        },

        closeMenu: function () {
            this.open = false;

            this.widget().querySelectorAll('.ec-event.gu-context-menu-open').forEach(
                event => event.classList.remove('gu-context-menu-open')
            )
            if (this.onCloseCallback) {
                this.onCloseCallback();
            }
        }
    }
}
