<?php
$health = 'ok';

if (!function_exists('check_main_heading')) {
    function check_main_heading() {
        global $health;
        if (!function_exists('woo_options_add') ) {
            function woo_options_add($options) {
                $cx_heading = array( 'name' => __('Canvas Extensions', 'pootlepress-canvas-extensions' ),
                    'icon' => 'favorite', 'type' => 'heading' );
                if (!in_array($cx_heading, $options))
                    $options[] = $cx_heading;
                return $options;
            }
        } else {	// another ( unknown ) child-theme or plugin has defined woo_options_add
            $health = 'ng';
        }
    }
}

add_action( 'admin_init', 'poo_commit_suicide' );

if(!function_exists('poo_commit_suicide')) {
    function poo_commit_suicide() {
        global $health;
        $pluginFile = str_replace('-functions', '', __FILE__);
        $plugin = plugin_basename($pluginFile);
        $plugin_data = get_plugin_data( $pluginFile, false );
        if ( $health == 'ng' && is_plugin_active($plugin) ) {
            deactivate_plugins( $plugin );
            wp_die( "ERROR: <strong>woo_options_add</strong> function already defined by another plugin. " .
                $plugin_data['Name']. " is unable to continue and has been deactivated. " .
                "<br /><br />Please contact PootlePress at <a href=\"mailto:support@pootlepress.com?subject=Woo_Options_Add Conflict\"> support@pootlepress.com</a> for additional information / assistance." .
                "<br /><br />Back to the WordPress <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
        }
    }
}

function stickycss() { ?>
    <style>
        #navhidden {
            min-height: 44px;
            margin-bottom: 3em;
        }
        /* DESKTOP STYLES */
        @media only screen and (min-width: 768px) {
            #nav-container {
                display: block;
            }
            #wrapper #navigation .menus {
                float: initial;
            }
        }

        /* RESPONSIVE STYLES */
        @media only screen and (max-width: 768px) {
            #navigation_top_tabs {
                position: fixed !important;
                left: 0 !important;
            }
            #nav-container {
                display: block;
                min-height: 0px !important;
                margin: 0;
            }
        }
    </style>
<?php
}

/* Provides intergration with pootlepress Menu Pack
 */
function menuPackOptions($style) {
    switch ($style) {
        case '#navigation':
            $stickyStyle 		= "{position:'fixed','min-height':'44px',left:'auto'}";
            $normalStyle 		= "{position:'relative','min-height':'44px',left:'auto'}";
            $navhiddenHeight	= "44px";
            $navhiddenMargin	= "3em";
            return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
            break;
        case '#navigation_top_tabs':
            $stickyStyle 		= "{position:'fixed','z-index':'9999','background-color':'#fff',top:'0px',width:'100%','border-bottom':'7px solid white',left:'auto'}";
            $normalStyle 		= "{position:'absolute','background-color':'inherit',top:'inherit',width:'inherit','border-bottom':'none',left:'275px'}";
            $navhiddenHeight	= "0";
            $navhiddenMargin	= "0";
            return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
            break;
        case '#navigation_header':
            $stickyStyle 		= "{position:'fixed','z-index':'9999',top:'0px',width:'100%',left:'auto','height':'32px'}";
            $normalStyle 		= "{position:'relative',top:'0',width:'inherit','height':'inherit'}";
            $navhiddenHeight	= "32px";
            $navhiddenMargin	= "20px";
            return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
            break;
        case '#navigation_beautiful_type':
            $stickyStyle 		= "{position:'fixed','z-index':'9999',top:'0px',width:'100%',left:'auto'}";
            $normalStyle 		= "{position:'relative',top:'0',width:'inherit'}";
            $navhiddenHeight	= "55px";
            $navhiddenMargin	= "20px";
            return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
            break;
        case '#navigation_top_align':
            $stickyStyle 		= "{position:'fixed','z-index':'9999',top:'0px',width:'100%',left:'auto',background:'#fff'}";
            $normalStyle 		= "{position:'relative',top:'0',width:'604px'}";
            $navhiddenHeight	= "0";
            $navhiddenMargin	= "0";
            return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
            break;
        case '#navigation_centred':
            $stickyStyle 	= "{position:'fixed','z-index':'9999',top:'0px',width:'100%',left:'auto',background:'#FFF url(/wp-content/plugins/pootlepress-menu-pack/styles/images/stroke.gif) repeat-x 1px 29px'}";
            $normalStyle 	= "{position:'relative',top:'0',width:'inherit'}";
            $navhiddenHeight	= "37px";
            $navhiddenMargin	= "20px";
            return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
            break;
        case '#navigation_right_align':
            $stickyStyle 		= "{position:'fixed','margin-top':'0px','width':'960px'}";
            $normalStyle 		= "{position:'relative','min-height':'44px',left:'auto','margin-top':'0px','width':'auto'}";
            $navhiddenHeight	= "44px";
            $navhiddenMargin	= "3em";
            return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
            break;

        default:
            return false;
            break;
    }
}

function stickyjs(){
    if (class_exists('Pootlepress_Menu_Pack')) {
        $_menupackstyle = get_option('pootlepress-menu-pack-menu-style');
    } else {
        $_menupackstyle = '';
    }

    $_rightalignenabled  = get_option('pootlepress-align-right-option');

    if ($_rightalignenabled == 'true') {
        //Right Align
        $_navid = '#navigation_right_align';
    } else {
        $_navid = '#navigation';
        if ($_menupackstyle == '' || $_menupackstyle == 'none') $_navid = '#navigation';
        else $_navid = '#navigation_'.$_menupackstyle;
    }

    // not using align right navid, as that cause problem on boxed layout, having 960px width on nav, overflowing the site container
//    $_navid = '#navigation';

    if (is_array(menuPackOptions($_navid)))
        list($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin) = menuPackOptions($_navid);

    if ($_rightalignenabled == 'true') {
        //Change the navid back to the original id
        $_navid = '#navigation';
    }
    ?>
    <script type="text/javascript">

	    /*!
	     * imagesLoaded PACKAGED v3.1.8
	     * JavaScript is all like "You images are done yet or what?"
	     * MIT License
	     */
	    (function(){function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,o=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=o,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var o={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",o):e.eventie=o}(this),function(e,t){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof exports?module.exports=t(e,require("wolfy87-eventemitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(window,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"===d.call(e)}function o(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0,i=e.length;i>n;n++)t.push(e[n]);else t.push(e);return t}function s(e,t,n){if(!(this instanceof s))return new s(e,t);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=o(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),a&&(this.jqDeferred=new a.Deferred);var r=this;setTimeout(function(){r.check()})}function f(e){this.img=e}function c(e){this.src=e,v[e]=this}var a=e.jQuery,u=e.console,h=u!==void 0,d=Object.prototype.toString;s.prototype=new t,s.prototype.options={},s.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);var i=n.nodeType;if(i&&(1===i||9===i||11===i))for(var r=n.querySelectorAll("img"),o=0,s=r.length;s>o;o++){var f=r[o];this.addImage(f)}}},s.prototype.addImage=function(e){var t=new f(e);this.images.push(t)},s.prototype.check=function(){function e(e,r){return t.options.debug&&h&&u.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},s.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify&&t.jqDeferred.notify(t,e)})},s.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},a&&(a.fn.imagesLoaded=function(e,t){var n=new s(this,e,t);return n.jqDeferred.promise(a(this))}),f.prototype=new t,f.prototype.check=function(){var e=v[this.img.src]||new c(this.img.src);if(e.isConfirmed)return this.confirm(e.isLoaded,"cached was confirmed"),void 0;if(this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this;e.on("confirm",function(e,n){return t.confirm(e.isLoaded,n),!0}),e.check()},f.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("confirm",this,t)};var v={};return c.prototype=new t,c.prototype.check=function(){if(!this.isChecked){var e=new Image;n.bind(e,"load",this),n.bind(e,"error",this),e.src=this.src,this.isChecked=!0}},c.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},c.prototype.onload=function(e){this.confirm(!0,"onload"),this.unbindProxyEvents(e)},c.prototype.onerror=function(e){this.confirm(!1,"onerror"),this.unbindProxyEvents(e)},c.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},c.prototype.unbindProxyEvents=function(e){n.unbind(e.target,"load",this),n.unbind(e.target,"error",this)},s});

        jQuery(function ($) {
	        $('#logo').imagesLoaded( function() {
		        var navhiddenHeight = '<?php echo $navhiddenHeight; ?>';
                var navhiddenMargin = '<?php echo $navhiddenMargin; ?>';
                var navid = '<?php echo $_navid; ?>';
                var stickNavOffset = $(navid).offset().top;
                var contentWidth;
                contentWidth = $('#inner-wrapper').width();
                resize();

                //Set some default styling on page load, if not mobile
                if ($(window).width() > 768) {
                    $(navid).css(<?php echo $normalStyle; ?>);
                }
                function stickyNav() {
                    if ($(window).scrollTop() > stickNavOffset) {
                        //Sticky Nav
                        $('#navhidden').css({'min-height':navhiddenHeight,'margin-bottom':navhiddenMargin});
                        $('#navhidden').show();
                        $(navid).css(<?php echo $stickyStyle; ?>);
                    } else {
                        //Normal Nav
                        $('#navhidden').hide();
                        $(navid).css(<?php echo $normalStyle; ?>);
                    }
                }

                function stickyNavFullFooter() {
                    if ($(window).scrollTop() > stickNavOffset) {
                        //Sticky Nav
                        $('#navhidden').css({'min-height':navhiddenHeight,'margin-bottom':navhiddenMargin});
                        $('#navhidden').show();
                        $('#navigation').css(<?php echo $stickyStyle; ?>);
                        $('#navigation').css({left: '50%', transform: '', '-webkit-transform': '', '-ms-transform': ''});
                        $('#navigation').attr('style', $('#navigation').attr('style') + '-webkit-transform: translateX(-50%) !important; -ms-transform: translateX(-50%) !important; transform: translateX(-50%) !important');
                    } else {
                        //Normal Nav
                        $('#navhidden').hide();
                        $('#navigation').css(<?php echo $normalStyle; ?>);
                        $('#navigation').css({left: '', transform: '', '-webkit-transform': '', '-ms-transform': ''});
                    }
                }

                function stickyNavFull() {
                    var innerWidth = contentWidth;
                    if ($(window).scrollTop() > stickNavOffset) {
                        $('#navhidden').show();
                        $('#nav-container').css({'box-sizing': 'border-box', position:'fixed',width: innerWidth,'min-height':'44px',left:'0',right:'0',top:'0','z-index':30});
                    } else {
                        $('#navhidden').hide();
                        $('#nav-container').css({'box-sizing': 'border-box', position:'relative','width': 'auto','min-height':'44px',right:'auto',left:'auto'});
                    }
                }

                function stickyNavBusiness() {
                    var innerWidth = 960;
                    if ($(window).scrollTop() > stickNavOffset) {
                        $('#navhidden').show();
                        $('#navigation').css({position:'fixed',width:innerWidth,'min-height':'44px',left:'50%',top:'0','z-index':30,'margin-left':-innerWidth/2});
                    } else {
                        $('#navhidden').hide();
                        $('#navigation').css({position:'relative','width':'100%','min-height':'44px',left:'auto','margin-left':'auto'});
                    }
                }

                //This function is called in the user scrolls, or the window is resize
                function resize() {
                    contentWidth = $('#inner-wrapper').width();
                    navWidth	 = $('#navigation').width();
                    console.log('navWidth:'+navWidth+' contentWidth:'+contentWidth);

                    if ($(window).width() <= 768) {
                        //Mobile Nav CSS
                        console.log('Mobile');
                        $('#nav-container').css({position: 'initial', 'min-height': '', left: '', width: '', 'margin-left': ''});
                        $('#navigation').css({position: '', 'min-height': '', left: '', width: '', 'margin-left': ''});
                    } else if ($('#nav-container').length > 0) {
                        //nav-container exists
                        stickyNavFull();
                    } else if ($('body').is('.full-width')) {
                        // body is still full width, probably because it is full footer
                        stickyNavFullFooter();
//                    } else if ((contentWidth > 959)&&(navWidth < 961)) { //Business Slider Fix
//                        stickyNavBusiness();
                    } else {
                        stickyNav();
                    }


                    <?php
                    //If boxed layout is enabled, add border
                    $_boxedlayoutenabled = get_option('woo_layout_boxed');

                    if ($_boxedlayoutenabled == 'true') {
                        $_boxedlayoutoptions = get_option('woo_box_border_lr');
                        $_boxedlayoutwidth = $_boxedlayoutoptions['width'];
                        $_boxedlayoutstyle = $_boxedlayoutoptions['style'];
                        $_boxedlayoutcolor = $_boxedlayoutoptions['color'];
                        ?>
                    var boxBorderWidth = <?php echo $_boxedlayoutwidth ?>;
                    var navWidth = 960 - boxBorderWidth * 2;
//                    var boxedNavWidth = contentWidth+1;

//                    $('#navigation').css({'width': navWidth + 'px'});

                    <?php
                    }
                    ?>
                }

                $(window).scroll(function() {
                    resize();
                });
                $(window).resize(function() {
                    resize();
                });
            });
        });

//        //Scroll to top. Prevents any browser caching scroll position
//        window.onload = function () {
//            window.scrollTo(0,0);
//        };
    </script>
<?php
}

function navBefore() {
    echo "<div id=\"navhidden\" style=\"display: none;\">&nbsp;</div>
	";
}
