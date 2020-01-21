<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://presstigers.com
 * @since             1.0.0
 * @package           Flexy_Breadcrumb
 *
 * @wordpress-plugin
 * Plugin Name:       Flexy Breadcrumb
 * Plugin URI:        https://wordpress.org/plugins/flexy-breadcrumb
 * Description:       Flexy Breadcrumb is a super light weight & easy to navigate through current page hierarchy.
 * Version:           1.1.1
 * Author:            PressTigers
 * Author URI:        http://presstigers.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       flexy-breadcrumb
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/**
 *  Show FBC Upgrade Notice
 */
function fbc_showUpgradeNotification( $currentPluginMetadata, $newPluginMetadata ) {

    // check "upgrade_notice"
    if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0) {
        echo '<div style="background-color: #d54e21; padding: 10px; color: #f9f9f9; margin-top: 10px"><strong>Important Upgrade Notice:</strong> ' . esc_html($newPluginMetadata->upgrade_notice) . '</div>';
    }
}

// Show FBC Upgrade Notice
add_action('in_plugin_update_message-flexy-breadcrumb/flexy-breadcrumb.php', 'fbc_showUpgradeNotification', 10, 2);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flexy-breadcrumb-activator.php
 */
function activate_flexy_breadcrumb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flexy-breadcrumb-activator.php';
	Flexy_Breadcrumb_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flexy-breadcrumb-deactivator.php
 */
function deactivate_flexy_breadcrumb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flexy-breadcrumb-deactivator.php';
	Flexy_Breadcrumb_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flexy_breadcrumb' );
register_deactivation_hook( __FILE__, 'deactivate_flexy_breadcrumb' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flexy-breadcrumb.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flexy_breadcrumb() {

	$plugin = new Flexy_Breadcrumb();
	$plugin->run();
}

run_flexy_breadcrumb();