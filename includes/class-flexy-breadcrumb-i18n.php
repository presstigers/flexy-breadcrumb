<?php
/**
 * Flexy_Breadcrumb_i18n Class
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/includes
 * @author     PressTigers <support@presstigers.com>
 */
class Flexy_Breadcrumb_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
				'flexy-breadcrumb', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

}