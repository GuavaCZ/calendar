// resources/js/event.js
function event({
  event: event2,
  timeText,
  view,
  hasEventClickContextMenu
}) {
  return {
    event: event2,
    contextMenu: null,
    init: function() {
      this.initializeContextMenu();
    },
    initializeContextMenu: function() {
      const element = document.querySelector("[calendar-context-menu]");
      this.contextMenu = Alpine.$data(element);
    },
    /**
     * Called when an event is clicked, if event clicking is enabled in the calendar.
     * @param info
     */
    onClick: function(info) {
      if (this.event.extendedProps.url) {
        window.open(
          this.event.extendedProps.url,
          this.event.extendedProps.url_target ?? "_blank"
        );
      } else if (hasEventClickContextMenu) {
        this.contextMenu.loadActions("eventClick", { event: this.event });
        this.contextMenu.openMenu(info.jsEvent);
      } else {
        this.$wire.onEventClick({
          event: info.event,
          view: info.view
        });
      }
    },
    testing: function() {
      console.log("testing");
    }
  };
}
export {
  event as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vLi4vcmVzb3VyY2VzL2pzL2V2ZW50LmpzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJleHBvcnQgZGVmYXVsdCBmdW5jdGlvbiBldmVudCh7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZXZlbnQsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGltZVRleHQsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmlldyxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBoYXNFdmVudENsaWNrQ29udGV4dE1lbnUsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4pIHtcbiAgICByZXR1cm4ge1xuICAgICAgICBldmVudCxcbiAgICAgICAgY29udGV4dE1lbnU6IG51bGwsXG5cbiAgICAgICAgaW5pdDogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgdGhpcy5pbml0aWFsaXplQ29udGV4dE1lbnUoKVxuICAgICAgICAgICAgLy8gUHJlbG9hZGluZ1xuICAgICAgICAgICAgLy8gdGhpcy4kZWwuYWRkRXZlbnRMaXN0ZW5lcignbW91c2VlbnRlcicsICgpID0+IHtcbiAgICAgICAgICAgIC8vICAgICB0aGlzLmNvbnRleHRNZW51LmxvYWRBY3Rpb25zKHRoaXMuZXZlbnQpXG4gICAgICAgICAgICAvLyB9KVxuICAgICAgICB9LFxuXG4gICAgICAgIGluaXRpYWxpemVDb250ZXh0TWVudTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgY29uc3QgZWxlbWVudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tjYWxlbmRhci1jb250ZXh0LW1lbnVdJylcbiAgICAgICAgICAgIHRoaXMuY29udGV4dE1lbnUgPSBBbHBpbmUuJGRhdGEoZWxlbWVudClcbiAgICAgICAgfSxcblxuICAgICAgICAvKipcbiAgICAgICAgICogQ2FsbGVkIHdoZW4gYW4gZXZlbnQgaXMgY2xpY2tlZCwgaWYgZXZlbnQgY2xpY2tpbmcgaXMgZW5hYmxlZCBpbiB0aGUgY2FsZW5kYXIuXG4gICAgICAgICAqIEBwYXJhbSBpbmZvXG4gICAgICAgICAqL1xuICAgICAgICBvbkNsaWNrOiBmdW5jdGlvbiAoaW5mbykge1xuICAgICAgICAgICAgaWYgKHRoaXMuZXZlbnQuZXh0ZW5kZWRQcm9wcy51cmwpIHtcbiAgICAgICAgICAgICAgICB3aW5kb3cub3BlbihcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ldmVudC5leHRlbmRlZFByb3BzLnVybCxcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5ldmVudC5leHRlbmRlZFByb3BzLnVybF90YXJnZXQgPz8gJ19ibGFuaydcbiAgICAgICAgICAgICAgICApO1xuICAgICAgICAgICAgfSBlbHNlIGlmIChoYXNFdmVudENsaWNrQ29udGV4dE1lbnUpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmNvbnRleHRNZW51LmxvYWRBY3Rpb25zKCdldmVudENsaWNrJywge2V2ZW50OiB0aGlzLmV2ZW50fSlcbiAgICAgICAgICAgICAgICB0aGlzLmNvbnRleHRNZW51Lm9wZW5NZW51KGluZm8uanNFdmVudClcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kd2lyZS5vbkV2ZW50Q2xpY2soe1xuICAgICAgICAgICAgICAgICAgICBldmVudDogaW5mby5ldmVudCxcbiAgICAgICAgICAgICAgICAgICAgdmlldzogaW5mby52aWV3LFxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuXG4gICAgICAgIHRlc3Rpbmc6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKCd0ZXN0aW5nJylcbiAgICAgICAgfVxuICAgIH1cbn1cbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBZSxTQUFSLE1BQXVCO0FBQUEsRUFDSSxPQUFBQTtBQUFBLEVBQ0E7QUFBQSxFQUNBO0FBQUEsRUFDQTtBQUNKLEdBQzVCO0FBQ0UsU0FBTztBQUFBLElBQ0gsT0FBQUE7QUFBQSxJQUNBLGFBQWE7QUFBQSxJQUViLE1BQU0sV0FBWTtBQUNkLFdBQUssc0JBQXNCO0FBQUEsSUFLL0I7QUFBQSxJQUVBLHVCQUF1QixXQUFZO0FBQy9CLFlBQU0sVUFBVSxTQUFTLGNBQWMseUJBQXlCO0FBQ2hFLFdBQUssY0FBYyxPQUFPLE1BQU0sT0FBTztBQUFBLElBQzNDO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQSxJQU1BLFNBQVMsU0FBVSxNQUFNO0FBQ3JCLFVBQUksS0FBSyxNQUFNLGNBQWMsS0FBSztBQUM5QixlQUFPO0FBQUEsVUFDSCxLQUFLLE1BQU0sY0FBYztBQUFBLFVBQ3pCLEtBQUssTUFBTSxjQUFjLGNBQWM7QUFBQSxRQUMzQztBQUFBLE1BQ0osV0FBVywwQkFBMEI7QUFDakMsYUFBSyxZQUFZLFlBQVksY0FBYyxFQUFDLE9BQU8sS0FBSyxNQUFLLENBQUM7QUFDOUQsYUFBSyxZQUFZLFNBQVMsS0FBSyxPQUFPO0FBQUEsTUFDMUMsT0FBTztBQUNILGFBQUssTUFBTSxhQUFhO0FBQUEsVUFDcEIsT0FBTyxLQUFLO0FBQUEsVUFDWixNQUFNLEtBQUs7QUFBQSxRQUNmLENBQUM7QUFBQSxNQUNMO0FBQUEsSUFDSjtBQUFBLElBRUEsU0FBUyxXQUFZO0FBQ2pCLGNBQVEsSUFBSSxTQUFTO0FBQUEsSUFDekI7QUFBQSxFQUNKO0FBQ0o7IiwKICAibmFtZXMiOiBbImV2ZW50Il0KfQo=
