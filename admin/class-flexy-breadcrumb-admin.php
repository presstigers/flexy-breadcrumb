<?php
/**
 * Flexy_Breadcrumb_Admin Class
 * 
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/admin
 * @author     PressTigers <support@presstigers.com>
 */
class Flexy_Breadcrumb_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $flexy_breadcrumb    The ID of this plugin.
     */
    private $flexy_breadcrumb;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $flexy_breadcrumb       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($flexy_breadcrumb, $version) {

        $this->flexy_breadcrumb = $flexy_breadcrumb;
        $this->version = $version;

        /**
         * The class responsible for defining all the plugin settings that occur in the front end area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-flexy-breadcrumb-settings-init.php';

        // Filter -> Footer Branding - with PressTigers Logo
        add_filter( 'admin_footer_text', array( $this, 'admin_powered_by' ) );
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        
        // Enqueue Flexy Breadcrumb Google Fonts
        wp_enqueue_style( $this->flexy_breadcrumb . "-open-sans", 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i', array(), 'all' );
        
        // Enqueue Fontawesome CSS File
        wp_enqueue_style( $this->flexy_breadcrumb . "-fontawesome", plugin_dir_url(__FILE__) . 'css/font-awesome.min.css', array(), '4.7.0', 'all' );
        
        // Enqueue Flexy Breadcrumb Admin Core CSS File
        wp_enqueue_style( $this->flexy_breadcrumb . "-admin", plugin_dir_url(__FILE__) . 'css/flexy-breadcrumb-admin.css', array('wp-color-picker'), $this->version, 'all' );
        
        // Enqueue Fontawesome Icon Picker CSS File
        wp_enqueue_style( $this->flexy_breadcrumb . "-fontawesome-icon-picker", plugin_dir_url(__FILE__) . 'css/fontawesome-iconpicker.min.css', array(), '1.2.2', 'all' );       
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        if (is_admin()) {
            
            // Register Fontawesome JS File            
            wp_register_script( $this->flexy_breadcrumb. '-fontawesome-icon-picker', plugin_dir_url(__FILE__) . 'js/fontawesome-iconpicker.min.js', '', '1.2.2', TRUE);
                        
            // Register Core Admin JS File
            wp_register_script($this->flexy_breadcrumb. '-admin-scripts', plugin_dir_url(__FILE__) . 'js/flexy-breadcrumb-admin.js', array('jquery'), $this->version, TRUE);
			
            // Register Alpha Color Picker Script
            wp_register_script( $this->flexy_breadcrumb. '-wp-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '1.2.2', TRUE);
        }
    }

    /**
     * PressTigers Branding.
     *
     * @since    1.0.0
     */
    public function admin_powered_by($text) {
        $screen = get_current_screen();

        // FBC Admin Page Id
        $fbc_pages = array(
            'toplevel_page_flexy-breadcrumb-settings',
        );

        if ( is_admin() && ( in_array( $screen->id, apply_filters('fbc_pages', $fbc_pages) ) ) ) {
            $text = '<a href=' . esc_url( "https://www.presstigers.com/") . ' target="_blank"><img src="' . plugin_dir_url(__FILE__) . '/images/powerByIcon.png" alt="Powered by PressTigers"></a>';
        }
        return $text;
    }
}