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
            #nav-container #navigation .menus {
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
        jQuery(function ($) {
            $(document).ready(function() {
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
