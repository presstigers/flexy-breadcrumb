<?php
/**
 * Flexy_Breadcrumb_Public Class
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/public
 * @author     PressTigers <support@presstigers.com>
 */
class Flexy_Breadcrumb_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $flexy_breadcrumb, $version ) {

		$this->flexy_breadcrumb = $flexy_breadcrumb;
		$this->version = $version;
		
		/** 
         * This file is responsible for the calender and breadcrumb typography/css.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-flexy-breadcrumb-typography.php';
		
		/**
		 * The class responsible for defining Flexy Breadcrumb shortcode structure
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexy-breadcrumb-trail.php';
		
		/**
		 * The class responsible for defining Flexy Breadcrumb shortcode
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flexy-breadcrumb-shortcode.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flexy_Breadcrumb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flexy_Breadcrumb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->flexy_breadcrumb, plugin_dir_url( __FILE__ ) . 'css/flexy-breadcrumb-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->flexy_breadcrumb . "-font-awesome", plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), '4.7.0', 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flexy_Breadcrumb_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flexy_Breadcrumb_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->flexy_breadcrumb, plugin_dir_url( __FILE__ ) . 'js/flexy-breadcrumb-public.js', array( 'jquery' ), $this->version, TRUE );
	}

}