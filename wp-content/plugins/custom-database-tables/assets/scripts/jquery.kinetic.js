/**
 jQuery.kinetic v2.1.0
 Dave Taylor http://davetayls.me

 @license The MIT License (MIT)
 @preserve Copyright (c) 2012 Dave Taylor http://davetayls.me
 */
!function(t){"use strict";var e="kinetic-active";window.requestAnimationFrame||(window.requestAnimationFrame=function(){return window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(t,e){window.setTimeout(t,1e3/60)}}()),t.support=t.support||{},t.extend(t.support,{touch:"ontouchend"in document});var s=function(){return!1},i=function(e,s){return this.settings=s,this.el=e,this.$el=t(e),this._initElements(),this};i.DATA_KEY="kinetic",i.DEFAULTS={cursor:"move",decelerate:!0,triggerHardware:!1,threshold:0,y:!0,x:!0,slowdown:.9,maxvelocity:40,throttleFPS:60,movingClass:{up:"kinetic-moving-up",down:"kinetic-moving-down",left:"kinetic-moving-left",right:"kinetic-moving-right"},deceleratingClass:{up:"kinetic-decelerating-up",down:"kinetic-decelerating-down",left:"kinetic-decelerating-left",right:"kinetic-decelerating-right"}},i.prototype.start=function(e){this.settings=t.extend(this.settings,e),this.velocity=e.velocity||this.velocity,this.velocityY=e.velocityY||this.velocityY,this.settings.decelerate=!1,this._move()},i.prototype.end=function(){this.settings.decelerate=!0},i.prototype.stop=function(){this.velocity=0,this.velocityY=0,this.settings.decelerate=!0,t.isFunction(this.settings.stopped)&&this.settings.stopped.call(this)},i.prototype.detach=function(){this._detachListeners(),this.$el.removeClass(e).css("cursor","")},i.prototype.attach=function(){this.$el.hasClass(e)||(this._attachListeners(this.$el),this.$el.addClass(e).css("cursor",this.settings.cursor))},i.prototype._initElements=function(){this.$el.addClass(e),t.extend(this,{xpos:null,prevXPos:!1,ypos:null,prevYPos:!1,mouseDown:!1,throttleTimeout:1e3/this.settings.throttleFPS,lastMove:null,elementFocused:null}),this.velocity=0,this.velocityY=0,t(document).mouseup(t.proxy(this._resetMouse,this)).click(t.proxy(this._resetMouse,this)),this._initEvents(),this.$el.css("cursor",this.settings.cursor),this.settings.triggerHardware&&this.$el.css({"-webkit-transform":"translate3d(0,0,0)","-webkit-perspective":"1000","-webkit-backface-visibility":"hidden"})},i.prototype._initEvents=function(){var e=this;this.settings.events={touchStart:function(t){var s;e._useTarget(t.target,t)&&(s=t.originalEvent.touches[0],e.threshold=e._threshold(t.target,t),e._start(s.clientX,s.clientY),t.stopPropagation())},touchMove:function(t){var s;e.mouseDown&&(s=t.originalEvent.touches[0],e._inputmove(s.clientX,s.clientY),t.preventDefault&&t.preventDefault())},inputDown:function(t){e._useTarget(t.target,t)&&(e.threshold=e._threshold(t.target,t),e._start(t.clientX,t.clientY),e.elementFocused=t.target,"IMG"===t.target.nodeName&&t.preventDefault(),t.stopPropagation())},inputEnd:function(t){e._useTarget(t.target,t)&&(e._end(),e.elementFocused=null,t.preventDefault&&t.preventDefault())},inputMove:function(t){e.mouseDown&&(e._inputmove(t.clientX,t.clientY),t.preventDefault&&t.preventDefault())},scroll:function(s){t.isFunction(e.settings.moved)&&e.settings.moved.call(e,e.settings),s.preventDefault&&s.preventDefault()},inputClick:function(t){return Math.abs(e.velocity)>0?(t.preventDefault(),!1):void 0},dragStart:function(t){return e._useTarget(t.target,t)&&e.elementFocused?!1:void 0}},this._attachListeners(this.$el,this.settings)},i.prototype._inputmove=function(e,s){var i=this.$el;this.el;if((!this.lastMove||new Date>new Date(this.lastMove.getTime()+this.throttleTimeout))&&(this.lastMove=new Date,this.mouseDown&&(this.xpos||this.ypos))){var o=e-this.xpos,n=s-this.ypos;if(this.threshold>0){var l=Math.sqrt(o*o+n*n);if(this.threshold>l)return;this.threshold=0}this.elementFocused&&(t(this.elementFocused).blur(),this.elementFocused=null,i.focus()),this.settings.decelerate=!1,this.velocity=this.velocityY=0;var r=this.scrollLeft(),h=this.scrollTop();this.scrollLeft(this.settings.x?r-o:r),this.scrollTop(this.settings.y?h-n:h),this.prevXPos=this.xpos,this.prevYPos=this.ypos,this.xpos=e,this.ypos=s,this._calculateVelocities(),this._setMoveClasses(this.settings.movingClass),t.isFunction(this.settings.moved)&&this.settings.moved.call(this,this.settings)}},i.prototype._calculateVelocities=function(){this.velocity=this._capVelocity(this.prevXPos-this.xpos,this.settings.maxvelocity),this.velocityY=this._capVelocity(this.prevYPos-this.ypos,this.settings.maxvelocity)},i.prototype._end=function(){this.xpos&&this.prevXPos&&this.settings.decelerate===!1&&(this.settings.decelerate=!0,this._calculateVelocities(),this.xpos=this.prevXPos=this.mouseDown=!1,this._move())},i.prototype._useTarget=function(e,s){return t.isFunction(this.settings.filterTarget)?this.settings.filterTarget.call(this,e,s)!==!1:!0},i.prototype._threshold=function(e,s){return t.isFunction(this.settings.threshold)?this.settings.threshold.call(this,e,s):this.settings.threshold},i.prototype._start=function(t,e){this.mouseDown=!0,this.velocity=this.prevXPos=0,this.velocityY=this.prevYPos=0,this.xpos=t,this.ypos=e},i.prototype._resetMouse=function(){this.xpos=!1,this.ypos=!1,this.mouseDown=!1},i.prototype._decelerateVelocity=function(t,e){return 0===Math.floor(Math.abs(t))?0:t*e},i.prototype._capVelocity=function(t,e){var s=t;return t>0?t>e&&(s=e):0-e>t&&(s=0-e),s},i.prototype._setMoveClasses=function(t){var e=this.settings,s=this.$el;s.removeClass(e.movingClass.up).removeClass(e.movingClass.down).removeClass(e.movingClass.left).removeClass(e.movingClass.right).removeClass(e.deceleratingClass.up).removeClass(e.deceleratingClass.down).removeClass(e.deceleratingClass.left).removeClass(e.deceleratingClass.right),this.velocity>0&&s.addClass(t.right),this.velocity<0&&s.addClass(t.left),this.velocityY>0&&s.addClass(t.down),this.velocityY<0&&s.addClass(t.up)},i.prototype._move=function(){var e=(this.$el,this.el),s=this,i=s.settings;i.x&&e.scrollWidth>0?(this.scrollLeft(this.scrollLeft()+this.velocity),Math.abs(this.velocity)>0&&(this.velocity=i.decelerate?s._decelerateVelocity(this.velocity,i.slowdown):this.velocity)):this.velocity=0,i.y&&e.scrollHeight>0?(this.scrollTop(this.scrollTop()+this.velocityY),Math.abs(this.velocityY)>0&&(this.velocityY=i.decelerate?s._decelerateVelocity(this.velocityY,i.slowdown):this.velocityY)):this.velocityY=0,s._setMoveClasses(i.deceleratingClass),t.isFunction(i.moved)&&i.moved.call(this,i),Math.abs(this.velocity)>0||Math.abs(this.velocityY)>0?this.moving||(this.moving=!0,window.requestAnimationFrame(function(){s.moving=!1,s._move()})):s.stop()},i.prototype._getScroller=function(){var e=this.$el;return(this.$el.is("body")||this.$el.is("html"))&&(e=t(window)),e},i.prototype.scrollLeft=function(t){var e=this._getScroller();return"number"!=typeof t?e.scrollLeft():(e.scrollLeft(t),void(this.settings.scrollLeft=t))},i.prototype.scrollTop=function(t){var e=this._getScroller();return"number"!=typeof t?e.scrollTop():(e.scrollTop(t),void(this.settings.scrollTop=t))},i.prototype._attachListeners=function(){var e=this.$el,i=this.settings;t.support.touch&&e.bind("touchstart",i.events.touchStart).bind("touchend",i.events.inputEnd).bind("touchmove",i.events.touchMove),e.mousedown(i.events.inputDown).mouseup(i.events.inputEnd).mousemove(i.events.inputMove),e.click(i.events.inputClick).scroll(i.events.scroll).bind("selectstart",s).bind("dragstart",i.events.dragStart)},i.prototype._detachListeners=function(){var e=this.$el,i=this.settings;t.support.touch&&e.unbind("touchstart",i.events.touchStart).unbind("touchend",i.events.inputEnd).unbind("touchmove",i.events.touchMove),e.unbind("mousedown",i.events.inputDown).unbind("mouseup",i.events.inputEnd).unbind("mousemove",i.events.inputMove),e.unbind("click",i.events.inputClick).unbind("scroll",i.events.scroll).unbind("selectstart",s).unbind("dragstart",i.events.dragStart)},t.Kinetic=i,t.fn.kinetic=function(e,s){return this.each(function(){var o=t(this),n=o.data(i.DATA_KEY),l=t.extend({},i.DEFAULTS,o.data(),"object"==typeof e&&e);n||o.data(i.DATA_KEY,n=new i(this,l)),"string"==typeof e&&n[e](s)})}}(window.jQuery||window.Zepto);
//# sourceMappingURL=jquery.kinetic.js.map
