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

        menu: {
            ['x-show']() {
                return this.open
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

        loadActions: async function (context, data = {}) {
            this.isLoading = true
            this.actions = []
            getContextMenuActionsUsing(context, data)
                .then((actions) => {
                    this.actions = actions
                })
                .finally(() => this.isLoading = false)
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

                    document.querySelectorAll(`.ec-event[data-event-id="${eventId}"]`).forEach(
                        el => el.classList.add('gu-context-menu-open')
                    )
                }
            });
        },

        closeMenu: function () {
            this.open = false;

            document.querySelectorAll('.ec-event.gu-context-menu-open').forEach(
                event => event.classList.remove('gu-context-menu-open')
            )
            if (this.onCloseCallback) {
                this.onCloseCallback();
            }
        }
    }
}
