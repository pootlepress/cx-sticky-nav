<?php
if (!function_exists('check_main_heading')) {
	function check_main_heading() {
		$options = get_option('woo_template');
		if (!in_array("Canvas Extensions", $options)) {
			function woo_options_add($options){
				$i = count($options);
				$options[$i++] = array(
						'name' => __('Canvas Extensions', 'pootlepress-canvas-extensions' ), 
						'icon' => 'favorite', 
						'type' => 'heading'
						);
				return $options;
			}
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
<?
}

/* Provides intergration with pootlepress Menu Pack
 */
function menuPackOptions($style) {
	switch ($style) {
		case '#navigation':
			$stickyStyle 		= "{position:'fixed'}";
			$normalStyle 		= "{position:'relative','min-height':'44px',left:'auto'}";
			$navhiddenHeight	= "44px";
			$navhiddenMargin	= "3em";
			return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
		break;
		case '#navigation_top_tabs':
			$stickyStyle 		= "{position:'fixed','z-index':'99999','background-color':'#fff',top:'0px',width:'100%','border-bottom':'7px solid white',left:'auto'}";
			$normalStyle 		= "{position:'absolute','background-color':'inherit',top:'inherit',width:'inherit','border-bottom':'none',left:'275px'}";
			$navhiddenHeight	= "0";
			$navhiddenMargin	= "0";
			return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
		break;
		case '#navigation_header':
			$stickyStyle 		= "{position:'fixed','z-index':'99999',top:'0px',width:'100%',left:'auto','height':'32px'}";
			$normalStyle 		= "{position:'relative',top:'0',width:'inherit','height':'inherit'}";
			$navhiddenHeight	= "32px";
			$navhiddenMargin	= "20px";
			return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
		break;	
		case '#navigation_beautiful_type':
			$stickyStyle 		= "{position:'fixed','z-index':'99999',top:'0px',width:'100%',left:'auto'}";
			$normalStyle 		= "{position:'relative',top:'0',width:'inherit'}";
			$navhiddenHeight	= "55px";
			$navhiddenMargin	= "20px";
			return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
		break;
		case '#navigation_top_align':
			$stickyStyle 		= "{position:'fixed','z-index':'99999',top:'0px',width:'100%',left:'auto',background:'#fff'}";
			$normalStyle 		= "{position:'relative',top:'0',width:'604px'}";
			$navhiddenHeight	= "0";
			$navhiddenMargin	= "0";
			return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
		break;	
		case '#navigation_centred':
			$stickyStyle 	= "{position:'fixed','z-index':'99999',top:'0px',width:'100%',left:'auto',background:'#FFF url(/wp-content/plugins/pootlepress-menu-pack/styles/images/stroke.gif) repeat-x 1px 29px'}";
			$normalStyle 	= "{position:'relative',top:'0',width:'inherit'}";
			$navhiddenHeight	= "37px";
			$navhiddenMargin	= "20px";
			return array($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin);
		break;
		
		default:
			return false;
		break;
	}
}

function stickyjs(){ 
	$_menupackstyle = get_option('pootlepress-menu-pack-menu-style');
	if ($_menupackstyle == '' || $_menupackstyle == 'none') $_navid = '#navigation';
	else $_navid = '#navigation_'.$_menupackstyle;
	if (is_array(menuPackOptions($_navid))) list($stickyStyle, $normalStyle, $navhiddenHeight, $navhiddenMargin) = menuPackOptions($_navid);
	
	?>
	<script type="text/javascript">
	jQuery(function ($) {
		$(document).ready(function() {		
			var navhiddenHeight = '<? echo $navhiddenHeight; ?>';
			var navhiddenMargin = '<? echo $navhiddenMargin; ?>';
			var navid = '<? echo $_navid; ?>';
			var stickNavOffset = $(navid).offset().top;
			var contentWidth;
			contentWidth = $('#inner-wrapper').width();
							
			function stickyNav() {
				if ($(window).scrollTop() > stickNavOffset) {
					//Sticky Nav
					$('#navhidden').css({'min-height':navhiddenHeight,'margin-bottom':navhiddenMargin});
					$('#navhidden').show();
					$(navid).css(<? echo $stickyStyle; ?>);
				} else {
					//Normal Nav
					$('#navhidden').hide();
					$(navid).css(<? echo $normalStyle; ?>);
				}
			}
			function stickyNavFull() {
				var innerWidth = contentWidth-45;
				if ($(window).scrollTop() > stickNavOffset) {
					$('#navhidden').show();
					$('#nav-container').css({position:'fixed',width:innerWidth,'min-height':'44px',left:'auto',top:'0','z-index':99999});
				} else {
					$('#navhidden').hide();
					$('#nav-container').css({position:'relative','min-height':'44px',left:'auto'});
				}
			}

			$(window).scroll(function() {
				contentWidth = $('#inner-wrapper').width();
				if (($(window).width() > 768)&&(contentWidth <= 960)) {
					stickyNav();
				} else if (contentWidth > 959) {
					stickyNavFull();
				} 
			});				
		});
	});
	
	//Scroll to top. Prevents any browser caching scroll position
	window.onload = function () {
		window.scrollTo(0,0);
	};
	</script>
<?
}

function navBefore() {
	echo "<div id=\"navhidden\" style=\"display: none;\">&nbsp;</div>
	";
}
?>