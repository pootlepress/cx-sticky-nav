<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Pootlepress_Sticky_Nav Class
 *
 * Base class for the Pootlepress Sticky Nav.
 *
 * @package WordPress
 * @subpackage Pootlepress_Sticky_Nav
 * @category Core
 * @author Pootlepress
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * public $token
 * public $version
 * private $_menu_style
 *
 * - __construct()
 * - add_theme_options()
 * - load_localisation()
 * - check_plugin()
 * - load_plugin_textdomain()
 * - activation()
 * - register_plugin_version()
 * - load_sticky_nav()
 */
class Pootlepress_Sticky_Nav {
    public $token = 'pootlepress-sticky-nav';
    public $version;
    private $file;
    private $_menu_style;

    /**
     * Constructor.
     * @param string $file The base file of the plugin.
     * @access public
     * @since  1.0.0
     * @return  void
     */
    public function __construct ( $file ) {
        $this->file = $file;
        $this->load_plugin_textdomain();
        add_action( 'init', array( &$this, 'load_localisation' ), 0 );

        // Run this on activation.
        register_activation_hook( $file, array( &$this, 'activation' ) );

        // Add the custom theme options.
        $this->add_theme_options();

        // Lood for a method/function for the selected style and load it.
        add_action('init', array( &$this, 'load_sticky_nav' ) );
    } // End __construct()

    /**
     * Add theme options to the WooFramework.
     * @access public
     * @since  1.0.0
     * @param array $o The array of options, as stored in the database.
     */
    public function add_theme_options ( ) {
        $o = array();

        $o[] = array(
            'name' => 'Sticky Nav',
            'type' => 'heading'
        );

        $o[] = array(
            'name' => 'Sticky Nav Settings',
            'type' => 'subheading'
        );
        $o[] = array(
            'id' => 'pootlepress-sticky-nav-option',
            'name' => __( 'Sticky Nav', 'pootlepress-sticky-nav' ),
            'desc' => __( 'Enable sticky nav', 'pootlepress-sticky-nav' ),
            'std' => 'true',
            'type' => 'checkbox'
        );
        $o[] = array(
            'id' => 'pootlepress-sticky-nav-wpadminbar',
            'name' => __( 'Wordpress Admin Bar', 'pootlepress-sticky-nav' ),
            'desc' => __( 'Disable the Wordpress Admin Bar (so the Wordpress admin bar will not hide the sticky nav).', 'pootlepress-sticky-nav' ),
            'std' => 'true',
            'type' => 'checkbox'
        );

        $afterName = 'Map Callout Text';
        $afterType = 'textarea';

        global $PCO;
        $PCO->add_options($afterName, $afterType, $o);

    } // End add_theme_options()

    /**
     * Load the plugin's localisation file.
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function load_localisation () {
        load_plugin_textdomain( $this->token, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
    } // End load_localisation()

    /**
     * Load the plugin textdomain from the main WordPress "languages" folder.
     * @access public
     * @since  1.0.0
     * @return  void
     */
    public function load_plugin_textdomain () {
        $domain = $this->token;
        // The "plugin_locale" filter is also used in load_plugin_textdomain()
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( $this->file ) ) . '/lang/' );
    } // End load_plugin_textdomain()

    /**
     * Run on activation.
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function activation () {
        $this->register_plugin_version();
    } // End activation()

    /**
     * Register the plugin's version.
     * @access public
     * @since 1.0.0
     * @return void
     */
    private function register_plugin_version () {
        if ( $this->version != '' ) {
            update_option( $this->token . '-version', $this->version );
        }
    } // End register_plugin_version()

    /**
     * Load the sticky nav files
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function load_sticky_nav () {
        $_stickyenabled  = get_option('pootlepress-sticky-nav-option');
        $_wpadminbarhide = get_option('pootlepress-sticky-nav-wpadminbar');

        if ($_stickyenabled == '') $enabled = 'true';
        if ($_stickyenabled == 'true') {
            add_action('wp_head', 'stickycss');
            add_action('wp_footer', 'stickyjs', 8);
            add_action('woo_nav_before', 'navBefore');
        }
        if ($_wpadminbarhide == 'true') {
            add_filter('show_admin_bar', '__return_false');
        }
    } // End load_sticky_nav()


} // End Class


