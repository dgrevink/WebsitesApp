/*! UIkit 1.0.2 | http://www.getuikit.com | (c) 2013 YOOtheme | MIT License */(function(e,t){"use strict";var n=e.UIkit||{};n.fn||(n.fn=function(t,r){var i=arguments,s=t.match(/^([a-z\-]+)(?:\.([a-z]+))?/i),o=s[1],u=s[2];return n[o]?this.each(function(){var t=e(this),s=t.data(o);s||t.data(o,s=new n[o](this,u?void 0:r)),u&&s[u].apply(s,Array.prototype.slice.call(i,1))}):(e.error("UIkit component ["+o+"] does not exist."),this)},n.support={},n.support.transition=function(){var e=function(){var e,n=t.body||t.documentElement,r={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(e in r)if(void 0!==n.style[e])return r[e]}();return e&&{end:e}}(),n.Utils={},n.Utils.debounce=function(e,t,n){var r;return function(){var s=this,o=arguments,u=function(){r=null,n||e.apply(s,o)},a=n&&!r;clearTimeout(r),r=setTimeout(u,t),a&&e.apply(s,o)}},n.Utils.options=function(t){if(e.isPlainObject(t))return t;var n=t.indexOf("{"),r={};if(-1!=n)try{r=Function("","var json = "+t.substr(n)+"; return JSON.parse(JSON.stringify(json));")()}catch(i){}return r},e.UIkit=n,e.fn.uk=n.fn,e.UIkit.langdirection="rtl"==e("html").attr("dir")?"right":"left")})(jQuery,document),function(e){function t(e){return"tagName"in e?e:e.parentNode}function n(e,t,n,r){var i=Math.abs(e-t),s=Math.abs(n-r);return i>=s?e-t>0?"Left":"Right":n-r>0?"Up":"Down"}function r(){f=null,l.last&&(l.el.trigger("longTap"),l={})}function i(){f&&clearTimeout(f),f=null}function s(){o&&clearTimeout(o),u&&clearTimeout(u),a&&clearTimeout(a),f&&clearTimeout(f),o=u=a=f=null,l={}}var o,u,a,f,l={},c=750;e(document).ready(function(){var p,d;e(document.body).bind("touchstart",function(n){p=Date.now(),d=p-(l.last||p),l.el=e(t(n.originalEvent.touches[0].target)),o&&clearTimeout(o),l.x1=n.originalEvent.touches[0].pageX,l.y1=n.originalEvent.touches[0].pageY,d>0&&250>=d&&(l.isDoubleTap=!0),l.last=p,f=setTimeout(r,c)}).bind("touchmove",function(e){i(),l.x2=e.originalEvent.touches[0].pageX,l.y2=e.originalEvent.touches[0].pageY}).bind("touchend",function(){i(),l.x2&&Math.abs(l.x1-l.x2)>30||l.y2&&Math.abs(l.y1-l.y2)>30?a=setTimeout(function(){l.el.trigger("swipe"),l.el.trigger("swipe"+n(l.x1,l.x2,l.y1,l.y2)),l={}},0):"last"in l&&(u=setTimeout(function(){var t=e.Event("tap");t.cancelTouch=s,l.el.trigger(t),l.isDoubleTap?(l.el.trigger("doubleTap"),l={}):o=setTimeout(function(){o=null,l.el.trigger("singleTap"),l={}},250)},0))}).bind("touchcancel",s),e(window).bind("scroll",s)}),["swipe","swipeLeft","swipeRight","swipeUp","swipeDown","doubleTap","tap","singleTap","longTap"].forEach(function(t){e.fn[t]=function(e){return this.bind(t,e)}})}(jQuery),function(e,t){"use strict";var n=function(t,n){var r=this;this.options=e.extend({},this.options,n),this.element=e(t).on("click",this.options.trigger,function(e){e.preventDefault(),r.close()})};e.extend(n.prototype,{options:{fade:!0,duration:200,trigger:".uk-alert-close"},close:function(){function e(){t.trigger("closed").remove()}var t=this.element.trigger("close");this.options.fade?t.css("overflow","hidden").css("max-height",t.height()).animate({height:0,opacity:0,"padding-top":0,"padding-bottom":0,"margin-top":0,"margin-bottom":0},this.options.duration,e):e()}}),t.alert=n,e(document).on("click.alert.uikit","[data-uk-alert]",function(r){r.preventDefault();var s=e(this);s.data("alert")||(s.data("alert",new n(s,t.Utils.options(s.data("uk-alert")))),e(r.target).is(s.data("alert").options.trigger)&&s.data("alert").close())})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=function(t,n){var r=this,i=e(t);this.options=e.extend({},this.options,n),this.element=i.on("click",this.options.target,function(t){t.preventDefault(),i.find(r.options.target).not(this).removeClass("uk-active").blur(),i.trigger("change",[e(this).addClass("uk-active")])})};e.extend(n.prototype,{options:{target:".uk-button"},getSelected:function(){this.element.find(".uk-active")}});var r=function(t,n){var r=e(t);this.options=e.extend({},this.options,n),this.element=r.on("click",this.options.target,function(t){t.preventDefault(),r.trigger("change",[e(this).toggleClass("uk-active").blur()])})};e.extend(r.prototype,{options:{target:".uk-button"},getSelected:function(){this.element.find(".uk-active")}});var i=function(t){var n=this;this.element=e(t).on("click",function(e){e.preventDefault(),n.toggle(),n.element.blur()})};e.extend(i.prototype,{toggle:function(){this.element.toggleClass("uk-active")}}),t.button=i,t["button-checkbox"]=r,t["button-radio"]=n,e(document).on("click.button-radio.uikit","[data-uk-button-radio]",function(r){var i=e(this);i.data("button-radio")||(i.data("button-radio",new n(i,t.Utils.options(i.data("uk-button-radio")))),e(r.target).is(i.data("button-radio").options.target)&&e(r.target).trigger("click"))}),e(document).on("click.button-checkbox.uikit","[data-uk-button-checkbox]",function(n){var i=e(this);i.data("button-checkbox")||(i.data("button-checkbox",new r(i,t.Utils.options(i.data("uk-button-checkbox")))),e(n.target).is(i.data("button-checkbox").options.target)&&e(n.target).trigger("click"))}),e(document).on("click.button.uikit","[data-uk-button]",function(){var t=e(this);t.data("button")||t.data("button",new i(t,t.data("uk-button"))).trigger("click")})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=!1,r=function(t,r){var i=this;this.options=e.extend({},this.options,r),this.element=e(t),this.dropdown=this.element.find(".uk-dropdown"),this.centered=this.dropdown.hasClass("uk-dropdown-center"),this.justified=this.options.justify?e(this.options.justify):!1,this.boundary=e(this.options.boundary),this.boundary.length||(this.boundary=e(window)),"click"==this.options.mode?this.element.on("click",function(t){e(t.target).parents(".uk-dropdown").length||t.preventDefault(),n&&n[0]!=i.element[0]&&n.removeClass("uk-open"),i.element.hasClass("uk-open")?(e(t.target).is("a")||!i.element.find(".uk-dropdown").find(t.target).length)&&(i.element.removeClass("uk-open"),n=!1):(i.checkDimensions(),i.element.addClass("uk-open"),n=i.element,e(document).off("click.outer.dropdown"),setTimeout(function(){e(document).on("click.outer.dropdown",function(t){!n||n[0]!=i.element[0]||!e(t.target).is("a")&&i.element.find(".uk-dropdown").find(t.target).length||(n.removeClass("uk-open"),e(document).off("click.outer.dropdown"))})},10))}):this.element.on("mouseenter",function(){i.remainIdle&&clearTimeout(i.remainIdle),n&&n[0]!=i.element[0]&&n.removeClass("uk-open"),i.checkDimensions(),i.element.addClass("uk-open"),n=i.element}).on("mouseleave",function(){i.remainIdle=setTimeout(function(){i.element.removeClass("uk-open"),i.remainIdle=!1,n&&n[0]==i.element[0]&&(n=!1)},i.options.remaintime)})};e.extend(r.prototype,{remainIdle:!1,options:{mode:"hover",remaintime:800,justify:!1,boundary:e(window)},checkDimensions:function(){var t=this.dropdown.css("margin-"+e.UIkit.langdirection,"").css("min-width",""),n=t.show().offset(),r=t.outerWidth(),i=this.boundary.width(),s=this.boundary.offset()?this.boundary.offset().left:0;if(this.centered&&(t.css("margin-"+e.UIkit.langdirection,-1*(parseFloat(r)/2-t.parent().width()/2)),n=t.offset(),(r+n.left>i||0>n.left)&&(t.css("margin-"+e.UIkit.langdirection,""),n=t.offset())),this.justified&&this.justified.length){var o=this.justified.outerWidth();if(t.css("min-width",o),"right"==e.UIkit.langdirection){var u=i-(this.justified.offset().left+o),a=i-(t.offset().left+t.outerWidth());t.css("margin-right",u-a)}else t.css("margin-left",this.justified.offset().left-n.left);n=t.offset()}r+(n.left-s)>i&&(t.addClass("uk-dropdown-flip"),n=t.offset()),0>n.left&&t.addClass("uk-dropdown-stack"),t.css("display","")}}),t.dropdown=r,e(document).on("mouseenter.dropdown.uikit","[data-uk-dropdown]",function(){var n=e(this);n.data("dropdown")||(n.data("dropdown",new r(n,t.Utils.options(n.data("uk-dropdown")))),"hover"==n.data("dropdown").options.mode&&n.trigger("mouseenter"))})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=e(window),r="resize orientationchange",i=function(i,s){var o=this;this.options=e.extend({},this.options,s),this.element=e(i),this.columns=this.element.children(),this.elements=this.options.target?this.element.find(this.options.target):this.columns,this.columns.length&&n.bind(r,function(){var r=function(){o.match()};return e(function(){r(),n.on("load",r)}),t.Utils.debounce(r,150)}())};e.extend(i.prototype,{options:{target:!1},match:function(){this.revert();var t=this.columns.filter(":visible:first");if(t.length){var n=Math.ceil(100*parseFloat(t.css("width"))/parseFloat(t.parent().css("width")))>=100?!0:!1,r=0,i=this;if(!n)return this.elements.each(function(){r=Math.max(r,e(this).outerHeight())}).each(function(t){var n=e(this),s="border-box"==n.css("box-sizing")?"outerHeight":"height",u=i.columns.eq(t),a=n.height()+(r-u[s]());n.css("min-height",a+"px")}),this}},revert:function(){return this.elements.css("min-height",""),this}});var s=function(i){var s=this;this.element=e(i),this.columns=this.element.children(),this.columns.length&&n.bind(r,function(){var r=function(){s.process()};return e(function(){r(),n.on("load",r)}),t.Utils.debounce(r,150)}())};e.extend(s.prototype,{process:function(){this.revert();var t=!1,n=this.columns.filter(":visible:first"),r=n.length?n.offset().top:!1;if(r!==!1)return this.columns.each(function(){var n=e(this);n.is(":visible")&&(t?n.addClass("uk-grid-margin"):n.offset().top!=r&&(n.addClass("uk-grid-margin"),t=!0))}),this},revert:function(){return this.columns.removeClass("uk-grid-margin"),this}}),t["grid-match"]=i,t["grid-margin"]=s,e(function(){e("[data-uk-grid-match],[data-uk-grid-margin]").each(function(){var n=e(this);n.is("[data-uk-grid-match]")&&!n.data("grid-match")&&n.data("grid-match",new i(n,t.Utils.options(n.data("uk-grid-match")))),n.is("[data-uk-grid-margin]")&&!n.data("grid-margin")&&n.data("grid-margin",new s(n,t.Utils.options(n.data("uk-grid-margin"))))})})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=!1,r=e("html"),i=function(r,i){var s=this;this.element=e(r),this.options=e.extend({keyboard:!0,show:!1,bgclose:!0},i),this.transition=t.support.transition,this.element.on("click",".uk-modal-close",function(e){e.preventDefault(),s.hide()}).on("click",function(t){var n=e(t.target);n[0]==s.element[0]&&s.options.bgclose&&s.hide()}),this.options.keyboard&&e(document).on("keyup.ui.modal.escape",function(e){n&&27==e.which&&s.isActive()&&s.hide()})};e.extend(i.prototype,{transition:!1,toggle:function(){this[this.isActive()?"hide":"show"]()},show:function(){var e=this;this.isActive()||(n&&n.hide(),this.element.removeClass("uk-open").show(),n=this,r.addClass("uk-modal-page").height(),e.element.addClass("uk-open"))},hide:function(){if(this.isActive())if(t.support.transition){var e=this;this.element.one(t.support.transition.end,function(){e._hide()}).removeClass("uk-open")}else this._hide()},_hide:function(){this.element.hide().removeClass("uk-open"),r.removeClass("uk-modal-page"),n=!1},isActive:function(){return n==this}});var s=function(t,n){var r=this,s=e(t);this.options=e.extend({target:s.is("a")?s.attr("href"):!1},n),this.element=s,this.modal=new i(this.options.target,n),s.on("click",function(e){e.preventDefault(),r.show()}),e.each(["show","hide","isActive"],function(e,t){r[t]=function(){return r.modal[t]()}})};s.Modal=i,t.modal=s,e(document).on("click.modal.uikit","[data-uk-modal]",function(){var n=e(this);n.data("modal")||(n.data("modal",new s(n,t.Utils.options(n.data("uk-modal")))),n.data("modal").show())})}(jQuery,jQuery.UIkit),function(e,t,n){"use strict";("ontouchstart"in window||window.DocumentTouch&&document instanceof n)&&e("html").addClass("uk-touch");var r={show:function(t){if(t=e(t),t.length){var n=e("html"),i=t.find(".uk-offcanvas-bar:first"),s=i.hasClass("uk-offcanvas-bar-flip")?-1:1,o=-1==s&&e(window).width()<window.innerWidth?window.innerWidth-e(window).width():0,u={x:window.scrollX,y:window.scrollY};t.addClass("uk-active"),n.css("width",n.outerWidth()).addClass("uk-offcanvas-page").width(),n.css("margin-"+e.UIkit.langdirection,(i.width()-o)*s),window.scrollTo(u.x,u.y),i.css("left"==e.UIkit.langdirection?-1==s?"right":"left":-1==s?"left":"right",0),t.off(".ukoffcanvas").on("click.ukoffcanvas swipeRight.ukoffcanvas swipeLeft.ukoffcanvas",function(t){var n=e(t.target);if(!t.type.match(/swipe/)){if(n.hasClass("uk-offcanvas-bar"))return;if(n.parents(".uk-offcanvas-bar:first").length)return}t.stopImmediatePropagation(),r.hide()}),e(document).on("keydown.offcanvas",function(e){27===e.keyCode&&r.hide()})}},hide:function(t){var n=e("html"),r=e(".uk-offcanvas.uk-active"),i=r.find(".uk-offcanvas-bar:first");r.length&&(e.UIkit.support.transition&&!t?(n.one(e.UIkit.support.transition.end,function(){n.removeClass("uk-offcanvas-page").css("width","")}).css("margin-"+e.UIkit.langdirection,0),setTimeout(function(){i.one(e.UIkit.support.transition.end,function(){r.removeClass("uk-active")}).css({left:"",right:""})},150)):(n.removeClass("uk-offcanvas-page").css("width","").css("margin-"+e.UIkit.langdirection,""),r.removeClass("uk-active"),i.css({left:"",right:""})),r.off(".ukoffcanvas"),e(document).off(".ukoffcanvas"))}},i=function(t,n){var i=this,s=e(t);this.options=e.extend({target:s.is("a")?s.attr("href"):!1},n),this.element=s,s.on("click",function(e){e.preventDefault(),r.show(i.options.target)})};i.offcanvas=r,t.offcanvas=i,e(document).on("click.offcanvas.uikit","[data-uk-offcanvas]",function(n){n.preventDefault();var r=e(this);r.data("offcanvas")||(r.data("offcanvas",new i(r,t.Utils.options(r.data("uk-offcanvas")))),r.trigger("click"))})}(jQuery,jQuery.UIkit,window.DocumentTouch),function(e,t){"use strict";function n(t){var n=e(t),r="auto";if(n.is(":visible"))r=n.outerHeight();else{var i={position:n.css("position"),visibility:n.css("visibility"),display:n.css("display")};r=n.css({position:"absolute",visibility:"hidden",display:"block"}).outerHeight(),n.css(i)}return r}var r=function(t,n){var r=this;this.options=e.extend({},this.options,n),this.element=e(t).on("click",this.options.toggler,function(t){t.preventDefault();var n=e(this);r.open(n.parent()[0]==r.element[0]?n:n.parent("li"))}),this.element.find(this.options.lists).each(function(){var t=e(this),n=t.parent(),i=n.hasClass("uk-active");t.wrap('<div style="overflow:hidden;height:0;position:relative;"></div>'),n.data("list-container",t.parent()),i&&r.open(n,!0)})};e.extend(r.prototype,{options:{toggler:">li.uk-parent > a[href='#']",lists:">li.uk-parent > ul",multiple:!1},open:function(t,r){var i=this.element,s=e(t);this.options.multiple||i.children(".uk-open").not(t).each(function(){e(this).data("list-container")&&e(this).data("list-container").stop().animate({height:0},function(){e(this).parent().removeClass("uk-open")})}),s.toggleClass("uk-open"),s.data("list-container")&&(r?s.data("list-container").stop().height(s.hasClass("uk-open")?"auto":0):s.data("list-container").stop().animate({height:s.hasClass("uk-open")?n(s.data("list-container").find("ul:first")):0}))}}),t.nav=r,e(function(){e("[data-uk-nav]").each(function(){var n=e(this);n.data("nav")||n.data("nav",new r(n,t.Utils.options(n.data("uk-nav"))))})})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n,r=function(t,n){var r=this;this.options=e.extend({},this.options,n),this.element=e(t).on({focus:function(){r.show()},blur:function(){r.hide()},mouseenter:function(){r.show()},mouseleave:function(){r.hide()}}),this.tip="function"==typeof this.options.src?this.options.src.call(this.element):this.options.src,this.element.attr("data-cached-title",this.element.attr("title")).attr("title","")};e.extend(r.prototype,{tip:"",options:{offset:5,pos:"top",src:function(){return this.attr("title")}},show:function(){if(this.tip.length){n.css({top:-2e3,visibility:"hidden"}).show(),n.html('<div class="uk-tooltip-inner">'+this.tip+"</div>");var t=e.extend({},this.element.offset(),{width:this.element[0].offsetWidth,height:this.element[0].offsetHeight}),r=n[0].offsetWidth,i=n[0].offsetHeight,s="function"==typeof this.options.offset?this.options.offset.call(this.element):this.options.offset,o="function"==typeof this.options.pos?this.options.pos.call(this.element):this.options.pos,u={display:"none",visibility:"visible",top:t.top+t.height+i,left:t.left},a=o.split("-");switch("left"!=a[0]&&"right"!=a[0]||"right"!=e.UIkit.langdirection||(a[0]="left"==a[0]?"right":"left"),a[0]){case"bottom":e.extend(u,{top:t.top+t.height+s,left:t.left+t.width/2-r/2});break;case"top":e.extend(u,{top:t.top-i-s,left:t.left+t.width/2-r/2});break;case"left":e.extend(u,{top:t.top+t.height/2-i/2,left:t.left-r-s});break;case"right":e.extend(u,{top:t.top+t.height/2-i/2,left:t.left+t.width+s})}2==a.length&&(u.left="left"==a[1]?t.left:t.left+t.width-r),n.css(u).attr("class","uk-tooltip uk-tooltip-"+o).show()}},hide:function(){this.element.is("input")&&this.element[0]===document.activeElement||n.hide()},content:function(){return this.tip}}),t.tooltip=r,e(function(){n=e('<div class="uk-tooltip"></div>').appendTo("body")}),e(document).on("mouseenter.tooltip.uikit focus.tooltip.uikit","[data-uk-tooltip]",function(){var n=e(this);n.data("tooltip")||n.data("tooltip",new r(n,t.Utils.options(n.data("uk-tooltip")))).trigger("mouseenter")})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=function(t,n){var r=this;if(this.options=e.extend({},this.options,n),this.element=e(t).on("click",this.options.toggler,function(e){e.preventDefault(),r.show(this)}),this.options.connect){this.connect=e(this.options.connect).find(".uk-active").removeClass(".uk-active").end();var i=this.element.find(this.options.toggler).filter(".uk-active");i.length&&this.show(i)}};e.extend(n.prototype,{options:{connect:!1,toggler:">*"},show:function(t){t=isNaN(t)?e(t):this.element.find(this.options.toggler).eq(t);var n=t;if(!n.hasClass("uk-disabled")){if(this.element.find(this.options.toggler).filter(".uk-active").removeClass("uk-active"),n.addClass("uk-active"),this.options.connect&&this.connect.length){var r=this.element.find(this.options.toggler).index(n);this.connect.children().removeClass("uk-active").eq(r).addClass("uk-active")}this.element.trigger("ui.switcher.show",[n])}}}),t.switcher=n,e(function(){e("[data-uk-switcher]").each(function(){var r=e(this);r.data("switcher")||r.data("switcher",new n(r,t.Utils.options(r.data("uk-switcher"))))})})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=function(t,n){this.element=e(t),this.options=e.extend({connect:!1},this.options,n),this.options.connect&&(this.connect=e(this.options.connect));var r=e("<li></li>").addClass("uk-tab-responsive uk-active").append('<a href="javascript:void(0);"> <i class="uk-icon-caret-down"></i></a>'),i=r.find("a:first"),s=e('<div class="uk-dropdown uk-dropdown-small"><ul class="uk-nav uk-nav-dropdown"></ul><div>'),o=s.find("ul");i.text(this.element.find("li.uk-active:first").find("a").text()),this.element.hasClass("uk-tab-bottom")&&s.addClass("uk-dropdown-up"),this.element.hasClass("uk-tab-flip")&&s.addClass("uk-dropdown-flip"),this.element.find("a").each(function(){var t=e(this),n=e('<li><a href="#">'+t.text()+"</a></li>").on("click",function(e){e.preventDefault(),t.parent().trigger("click"),r.removeClass("uk-open")});t.parents(".uk-disabled:first").length||o.append(n)}),this.element.uk("switcher",{toggler:">li:not(.uk-tab-responsive)",connect:this.options.connect}),r.append(s).uk("dropdown"),this.element.append(r).data({dropdown:r.data("dropdown"),mobilecaption:i}).on("ui.switcher.show",function(e,t){r.addClass("uk-active"),i.text(t.find("a").text())})};t.tab=n,e(function(){e("[data-uk-tab]").each(function(){var r=e(this);r.data("tab")||r.data("tab",new n(r,t.Utils.options(r.data("uk-tab"))))})})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=function(t,n){var r=this;this.options=e.extend({},this.options,n),this.element=e(t),this.timer=null,this.value=null,this.input=this.element.find(".uk-search-field"),this.form=this.input.length?e(this.input.get(0).form):e(),this.input.attr("autocomplete","off"),this.input.on({keydown:function(e){if(r.form[r.input.val()?"addClass":"removeClass"](r.options.filledClass),e&&e.which&&!e.shiftKey)switch(e.which){case 13:r.done(r.selected),e.preventDefault();break;case 38:r.pick("prev"),e.preventDefault();break;case 40:r.pick("next"),e.preventDefault();break;case 27:case 9:r.hide();break;default:}},keyup:function(){r.trigger()},blur:function(e){setTimeout(function(){r.hide(e)},200)}}),this.form.find("button[type=reset]").bind("click",function(){r.form.removeClass("uk-open").removeClass("uk-loading").removeClass("uk-active"),r.value=null,r.input.focus()}),this.dropdown=e('<div class="uk-dropdown uk-dropdown-search"><ul class="uk-nav uk-nav-search"></ul></div>').appendTo(this.form).find(".uk-nav-search")};e.extend(n.prototype,{options:{source:!1,param:"search",method:"post",minLength:3,delay:300,match:":not(.uk-skip)",skipClass:"uk-skip",loadingClass:"uk-loading",filledClass:"uk-active",resultsHeaderClass:"uk-nav-header",moreResultsClass:"",noResultsClass:"",listClass:"results",hoverClass:"uk-active",msgResultsHeader:"Search Results",msgMoreResults:"More Results",msgNoResults:"No results found",onSelect:function(e){window.location=e.data("choice").url},onLoadedResults:function(e){return e}},request:function(t){var n=this;this.form.addClass(this.options.loadingClass),this.options.source?e.ajax(e.extend({url:this.options.source,type:this.options.method,dataType:"json",success:function(e){e=n.options.onLoadedResults.apply(this,[e]),n.form.removeClass(n.options.loadingClass),n.suggest(e)}},t)):this.form.removeClass(n.options.loadingClass)},pick:function(e){var t=!1;if("string"==typeof e||e.hasClass(this.options.skipClass)||(t=e),"next"==e||"prev"==e){var n=this.dropdown.children().filter(this.options.match);if(this.selected){var r=n.index(this.selected);t="next"==e?n.eq(n.length>r+1?r+1:0):n.eq(0>r-1?n.length-1:r-1)}else t=n["next"==e?"first":"last"]()}t&&t.length&&(this.selected=t,this.dropdown.children().removeClass(this.options.hoverClass),this.selected.addClass(this.options.hoverClass))},done:function(e){return e?(e.hasClass(this.options.moreResultsClass)?this.form.submit():e.data("choice")&&this.options.onSelect.apply(this,[e]),this.hide(),void 0):(this.form.submit(),void 0)},trigger:function(){var e=this,t=this.value,n={};return this.value=this.input.val(),this.value.length<this.options.minLength?this.hide():(this.value!=t&&(this.timer&&window.clearTimeout(this.timer),this.timer=window.setTimeout(function(){n[e.options.param]=e.value,e.request({data:n})},this.options.delay,this)),this)},suggest:function(t){if(t){var n=this,r={mouseover:function(){n.pick(e(this).parent())},click:function(t){t.preventDefault(),n.done(e(this).parent())}};t===!1?this.hide():(this.selected=null,this.dropdown.empty(),this.options.msgResultsHeader&&e("<li>").addClass(this.options.resultsHeaderClass+" "+this.options.skipClass).html(this.options.msgResultsHeader).appendTo(this.dropdown),t.results&&t.results.length>0?(e(t.results).each(function(){var t=e('<li><a href="#">'+this.title+"</a></li>").data("choice",this);this.text&&t.find("a").append("<div>"+this.text+"</div>"),n.dropdown.append(t)}),this.options.msgMoreResults&&(e("<li>").addClass("uk-nav-divider "+n.options.skipClass).appendTo(n.dropdown),e("<li>").addClass(n.options.moreResultsClass).html('<a href="#">'+n.options.msgMoreResults+"</a>").appendTo(n.dropdown).on(r)),n.dropdown.find("li>a").on(r)):this.options.msgNoResults&&e("<li>").addClass(this.options.noResultsClass+" "+this.options.skipClass).html("<a>"+this.options.msgNoResults+"</a>").appendTo(this.dropdown),this.show())}},show:function(){this.visible||(this.visible=!0,this.form.addClass("uk-open"))},hide:function(){this.visible&&(this.visible=!1,this.form.removeClass(this.options.loadingClass).removeClass("uk-open"))}}),t.search=n,e(document).on("focus.search.uikit","[data-uk-search]",function(){var r=e(this);r.data("search")||r.data("search",new n(r,t.Utils.options(r.data("uk-search"))))})}(jQuery,jQuery.UIkit),function(e,t){"use strict";var n=function(t,n){var r=this;this.options=e.extend({duration:1e3,transition:"easeOutExpo"},n),this.element=e(t).on("click",function(){var t=(e(this.hash).length?e(this.hash):e("body")).offset().top,n=e(document).height(),i=e(window).height();return t+i>n&&(t=t-i+50),e("html,body").stop().animate({scrollTop:t},r.options.duration,r.options.transition),!1})};t["smooth-scroll"]=n,e.easing.easeOutExpo||(e.easing.easeOutExpo=function(e,t,n,r,i){return t==i?n+r:r*(-Math.pow(2,-10*t/i)+1)+n}),e(document).on("click.smooth-scroll.uikit","[data-uk-smooth-scroll]",function(){var r=e(this);r.data("smooth-scroll")||r.data("smooth-scroll",new n(r,t.Utils.options(r.data("uk-smooth-scroll")))).trigger("click")})}(jQuery,jQuery.UIkit);