<?php
/*
Plugin Name: Pootlepress Sticky Nav
Plugin URI: http://pootlepress.co.uk/
Description: Hi, I'm a custom sticky nav, brought to you by Pootlepress. I can attach the navigation meny to the top of the screen in the Canvas theme by WooThemes.
Version: 1.0.0
Author: Pootlepress
Author URI: http://pootlepress.co.uk/
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
/*  Copyright 2012  Pootlepress  (email : jamie@pootlepress.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	require_once( 'pootlepress-sticky-nav-functions.php' );
	require_once( 'classes/class-pootlepress-sticky-nav.php' );

    $GLOBALS['pootlepress_sticky_nav'] = new Pootlepress_Sticky_Nav( __FILE__ );
    $GLOBALS['pootlepress_sticky_nav']->version = '1.0.0';
	
?>