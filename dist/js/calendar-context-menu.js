// resources/js/calendar-context-menu.js
function calendarContextMenu({
  calendar = null
}) {
  return {
    open: false,
    size: {
      width: 0,
      height: 0
    },
    position: {
      x: 0,
      y: 0
    },
    mountData: {},
    menu: {
      ["x-show"]() {
        return this.open;
      },
      ["x-bind:style"]() {
        return `
                position: absolute;
                z-index: 40;
                top: ${this.position.y}px;
                left: ${this.position.x}px;
                `;
      },
      ["x-on:click.away"]() {
        this.closeMenu();
      }
    },
    init: async function() {
      const menu = this.$el.querySelector('[x-bind="menu"]');
      this.size = {
        width: menu.offsetWidth,
        height: menu.offsetHeight
      };
      this.$el.addEventListener("calendar--open-menu", (event) => this.openMenu(event));
    },
    openMenu: function(event) {
      this.mountData = event.detail;
      this.$nextTick(() => {
        const clientX = event.detail.jsEvent.clientX;
        const clientY = event.detail.jsEvent.clientY;
        const pageX = event.detail.jsEvent.pageX;
        const pageY = event.detail.jsEvent.pageY;
        const offsetX = clientX + this.size.width > window.innerWidth ? clientX + this.size.width - window.innerWidth : 0;
        const offsetY = clientY + this.size.height > window.innerHeight ? clientY + this.size.height - window.innerHeight : 0;
        this.position.x = pageX - offsetX;
        this.position.y = pageY - offsetY;
        this.open = true;
      });
    },
    closeMenu: function() {
      this.open = false;
    }
  };
}
export {
  calendarContextMenu as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vLi4vcmVzb3VyY2VzL2pzL2NhbGVuZGFyLWNvbnRleHQtbWVudS5qcyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gY2FsZW5kYXJDb250ZXh0TWVudSh7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjYWxlbmRhciA9IG51bGwsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pIHtcbiAgICByZXR1cm4ge1xuXG4gICAgICAgIG9wZW46IGZhbHNlLFxuXG4gICAgICAgIHNpemU6IHtcbiAgICAgICAgICAgIHdpZHRoOiAwLFxuICAgICAgICAgICAgaGVpZ2h0OiAwLFxuICAgICAgICB9LFxuICAgICAgICBwb3NpdGlvbjoge1xuICAgICAgICAgICAgeDogMCxcbiAgICAgICAgICAgIHk6IDAsXG4gICAgICAgIH0sXG4gICAgICAgIG1vdW50RGF0YToge30sXG5cbiAgICAgICAgbWVudToge1xuICAgICAgICAgICAgWyd4LXNob3cnXSgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGhpcy5vcGVuXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgWyd4LWJpbmQ6c3R5bGUnXSgpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gYFxuICAgICAgICAgICAgICAgIHBvc2l0aW9uOiBhYnNvbHV0ZTtcbiAgICAgICAgICAgICAgICB6LWluZGV4OiA0MDtcbiAgICAgICAgICAgICAgICB0b3A6ICR7dGhpcy5wb3NpdGlvbi55fXB4O1xuICAgICAgICAgICAgICAgIGxlZnQ6ICR7dGhpcy5wb3NpdGlvbi54fXB4O1xuICAgICAgICAgICAgICAgIGA7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgWyd4LW9uOmNsaWNrLmF3YXknXSgpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmNsb3NlTWVudSgpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuXG4gICAgICAgIGluaXQ6IGFzeW5jIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGNvbnN0IG1lbnUgPSB0aGlzLiRlbC5xdWVyeVNlbGVjdG9yKCdbeC1iaW5kPVwibWVudVwiXScpO1xuICAgICAgICAgICAgdGhpcy5zaXplID0ge1xuICAgICAgICAgICAgICAgIHdpZHRoOiBtZW51Lm9mZnNldFdpZHRoLFxuICAgICAgICAgICAgICAgIGhlaWdodDogbWVudS5vZmZzZXRIZWlnaHQsXG4gICAgICAgICAgICB9O1xuXG4gICAgICAgICAgICB0aGlzLiRlbC5hZGRFdmVudExpc3RlbmVyKCdjYWxlbmRhci0tb3Blbi1tZW51JywgKGV2ZW50KSA9PiB0aGlzLm9wZW5NZW51KGV2ZW50KSk7XG4gICAgICAgIH0sXG5cbiAgICAgICAgb3Blbk1lbnU6IGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICAgICAgdGhpcy5tb3VudERhdGEgPSBldmVudC5kZXRhaWw7XG5cbiAgICAgICAgICAgIHRoaXMuJG5leHRUaWNrKCgpID0+IHtcbiAgICAgICAgICAgICAgICBjb25zdCBjbGllbnRYID0gZXZlbnQuZGV0YWlsLmpzRXZlbnQuY2xpZW50WDtcbiAgICAgICAgICAgICAgICBjb25zdCBjbGllbnRZID0gZXZlbnQuZGV0YWlsLmpzRXZlbnQuY2xpZW50WTtcbiAgICAgICAgICAgICAgICBjb25zdCBwYWdlWCA9IGV2ZW50LmRldGFpbC5qc0V2ZW50LnBhZ2VYO1xuICAgICAgICAgICAgICAgIGNvbnN0IHBhZ2VZID0gZXZlbnQuZGV0YWlsLmpzRXZlbnQucGFnZVk7XG5cbiAgICAgICAgICAgICAgICBjb25zdCBvZmZzZXRYID0gY2xpZW50WCArIHRoaXMuc2l6ZS53aWR0aCA+IHdpbmRvdy5pbm5lcldpZHRoXG4gICAgICAgICAgICAgICAgICAgID8gY2xpZW50WCArIHRoaXMuc2l6ZS53aWR0aCAtIHdpbmRvdy5pbm5lcldpZHRoXG4gICAgICAgICAgICAgICAgICAgIDogMDtcbiAgICAgICAgICAgICAgICBjb25zdCBvZmZzZXRZID0gY2xpZW50WSArIHRoaXMuc2l6ZS5oZWlnaHQgPiB3aW5kb3cuaW5uZXJIZWlnaHRcbiAgICAgICAgICAgICAgICAgICAgPyBjbGllbnRZICsgdGhpcy5zaXplLmhlaWdodCAtIHdpbmRvdy5pbm5lckhlaWdodFxuICAgICAgICAgICAgICAgICAgICA6IDA7XG5cbiAgICAgICAgICAgICAgICB0aGlzLnBvc2l0aW9uLnggPSBwYWdlWCAtIG9mZnNldFg7XG4gICAgICAgICAgICAgICAgdGhpcy5wb3NpdGlvbi55ID0gcGFnZVkgLSBvZmZzZXRZO1xuICAgICAgICAgICAgICAgIHRoaXMub3BlbiA9IHRydWU7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSxcblxuICAgICAgICBjbG9zZU1lbnU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMub3BlbiA9IGZhbHNlO1xuICAgICAgICB9XG4gICAgfVxufVxuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFlLFNBQVIsb0JBQXFDO0FBQUEsRUFDSSxXQUFXO0FBQ2YsR0FBRztBQUMzQyxTQUFPO0FBQUEsSUFFSCxNQUFNO0FBQUEsSUFFTixNQUFNO0FBQUEsTUFDRixPQUFPO0FBQUEsTUFDUCxRQUFRO0FBQUEsSUFDWjtBQUFBLElBQ0EsVUFBVTtBQUFBLE1BQ04sR0FBRztBQUFBLE1BQ0gsR0FBRztBQUFBLElBQ1A7QUFBQSxJQUNBLFdBQVcsQ0FBQztBQUFBLElBRVosTUFBTTtBQUFBLE1BQ0YsQ0FBQyxRQUFRLElBQUk7QUFDVCxlQUFPLEtBQUs7QUFBQSxNQUNoQjtBQUFBLE1BQ0EsQ0FBQyxjQUFjLElBQUk7QUFDZixlQUFPO0FBQUE7QUFBQTtBQUFBLHVCQUdBLEtBQUssU0FBUyxDQUFDO0FBQUEsd0JBQ2QsS0FBSyxTQUFTLENBQUM7QUFBQTtBQUFBLE1BRTNCO0FBQUEsTUFDQSxDQUFDLGlCQUFpQixJQUFJO0FBQ2xCLGFBQUssVUFBVTtBQUFBLE1BQ25CO0FBQUEsSUFDSjtBQUFBLElBRUEsTUFBTSxpQkFBa0I7QUFDcEIsWUFBTSxPQUFPLEtBQUssSUFBSSxjQUFjLGlCQUFpQjtBQUNyRCxXQUFLLE9BQU87QUFBQSxRQUNSLE9BQU8sS0FBSztBQUFBLFFBQ1osUUFBUSxLQUFLO0FBQUEsTUFDakI7QUFFQSxXQUFLLElBQUksaUJBQWlCLHVCQUF1QixDQUFDLFVBQVUsS0FBSyxTQUFTLEtBQUssQ0FBQztBQUFBLElBQ3BGO0FBQUEsSUFFQSxVQUFVLFNBQVUsT0FBTztBQUN2QixXQUFLLFlBQVksTUFBTTtBQUV2QixXQUFLLFVBQVUsTUFBTTtBQUNqQixjQUFNLFVBQVUsTUFBTSxPQUFPLFFBQVE7QUFDckMsY0FBTSxVQUFVLE1BQU0sT0FBTyxRQUFRO0FBQ3JDLGNBQU0sUUFBUSxNQUFNLE9BQU8sUUFBUTtBQUNuQyxjQUFNLFFBQVEsTUFBTSxPQUFPLFFBQVE7QUFFbkMsY0FBTSxVQUFVLFVBQVUsS0FBSyxLQUFLLFFBQVEsT0FBTyxhQUM3QyxVQUFVLEtBQUssS0FBSyxRQUFRLE9BQU8sYUFDbkM7QUFDTixjQUFNLFVBQVUsVUFBVSxLQUFLLEtBQUssU0FBUyxPQUFPLGNBQzlDLFVBQVUsS0FBSyxLQUFLLFNBQVMsT0FBTyxjQUNwQztBQUVOLGFBQUssU0FBUyxJQUFJLFFBQVE7QUFDMUIsYUFBSyxTQUFTLElBQUksUUFBUTtBQUMxQixhQUFLLE9BQU87QUFBQSxNQUNoQixDQUFDO0FBQUEsSUFDTDtBQUFBLElBRUEsV0FBVyxXQUFZO0FBQ25CLFdBQUssT0FBTztBQUFBLElBQ2hCO0FBQUEsRUFDSjtBQUNKOyIsCiAgIm5hbWVzIjogW10KfQo=