function c(){return{open:!1,size:{width:0,height:0},position:{x:0,y:0},mountData:{},context:null,menu:{"x-show"(){return this.open},"x-bind:style"(){return`
                position: absolute;
                z-index: 40;
                top: ${this.position.y}px;
                left: ${this.position.x}px;
                `},"x-on:click.away"(){this.closeMenu()}},init:async function(){let t=this.$el.querySelector('[x-bind="menu"]');this.size={width:t.offsetWidth,height:t.offsetHeight},this.$el.addEventListener("calendar--open-menu",i=>this.openMenu(i))},openMenu:function(t){console.log(t.detail.context),this.context=t.detail.context,this.mountData=t.detail,this.$nextTick(()=>{let i=t.detail.jsEvent.clientX,e=t.detail.jsEvent.clientY,n=t.detail.jsEvent.pageX,o=t.detail.jsEvent.pageY,s=i+this.size.width>window.innerWidth?i+this.size.width-window.innerWidth:0,h=e+this.size.height>window.innerHeight?e+this.size.height-window.innerHeight:0;this.position.x=n-s,this.position.y=o-h,this.open=!0})},closeMenu:function(){this.open=!1}}}export{c as default};
