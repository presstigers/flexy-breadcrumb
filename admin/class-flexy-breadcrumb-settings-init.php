<?php
/**
 * Flexy_Breadcrumb_Admin_Settings_Init Class
 * 
 * Flexy Breadcrumb Settings:
 * 
 * - General Options
 * - Typography Options
 *
 * @link       https://presstigers.com
 * @since      1.0.0
 *
 * @package     Flexy_Breadcrumb
 * @subpackage  Flexy_Breadcrumb/admin
 * @author      PressTigers <support@presstigers.com>
 */
class Flexy_Breadcrumb_Admin_Settings_Init {

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     */
    public function __construct() {
        /**
         * The class responsible for defining all the plugin settings that occur in the front end area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/settings/class-flexy-breadcrumb-settings-general.php';

        /**
         * The class responsible for defining all the plugin settings that occur in the front end area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/settings/class-flexy-breadcrumb-settings-typography.php';

        /**
         * Action - Add Settings Menu
         */
        add_action('admin_menu', array($this, 'fbc_admin_menu'), 13);

        /**
         * Action - Add Options Group
         */
        add_action('admin_init', array($this, 'register_settings_fields'));
    }

    /**
     * Create Admin Menu Page
     * 
     * @since   1.0.0
     */
    public function fbc_admin_menu() {
        add_menu_page(__('Flexy Breadcrumb', 'flexy-breadcrumb'), __('Flexy Breadcrumb', 'breadcrumb'), 'manage_options', 'flexy-breadcrumb-settings', array($this, 'settings_tab_menu'), plugins_url('images/admin-menu-icon.png', __FILE__) );
    }

    /**
     * Registers Settings
     * 
     * @since   1.0.0
     */
    public function register_settings_fields() {
        register_setting("fbc_breadcrumb_section", "fbc_settings_options");
    }

    /**
     * Display Settings
     * 
     * @since   1.0.0
     */
    public function settings_tab_menu() {
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbcObj = new Flexy_Breadcrumb();
		
        // Enqueue Admin Scripts Script
        wp_enqueue_script( $fbcObj->get_plugin_name() . '-admin-scripts' );
        ?> 

        <!-- Flexy Breadcrumb Settings -->
        <div class="fbc-wrap">

            <!-- Flexy Breadcrumb Settings Form -->
            <form id="fbc-options-form" method="POST" action="options.php">

                <?php
                /**
                 * Create Settings Section with followings:
                 * 
                 * - Nonce Fileds
                 * - Actions
                 * - Options Page Fields
                 * 
                 * @since 1.0.0
                 */
                settings_fields('fbc_breadcrumb_section');
                ?>

                <!-- Settings Loader -->
                <div class="loading-div">
                    <div class="loading"><i class="fa fa-spin fa-spinner"></i></div>
                </div>

                <div class="fbc-container-fluid pt-wrapper-bg">

                    <!-- Settings Tabs -->
                    <div class="fbc-col-lg-2 fbc-col-md-3 fbc-col-sm-4 fbc-col-xs-1" style="padding:0;">

                        <!-- Settings Saved Notification -->
                        <div class="success-msg"><?php esc_html_e('Setting have been saved.', 'flexy-breadcrumb'); ?></div>

                        <!-- Error Notification -->
                        <div class="error-msg"><?php esc_html_e('There is an error. Please try again later', 'flexy-breadcrumb'); ?></div>

                        <!-- Settings Tabs -->
                        <div class="fbc-sidebar">
                            <div class="branding">
                                <strong><?php echo esc_html__('Flexy Breadcrumb', 'flexy-breadcrumb'); ?></strong><br/>
                                <?php echo esc_attr( $fbcObj->get_version() ); ?>
                            </div>
                            <div class="main-nav">
                                <ul class="sub-menu categoryitems" style="display:block">
                                    <?php
                                    /**
                                     * Filter the Settings Tab Menus. 
                                     * 
                                     * @since 1.1.0 
                                     * 
                                     * @param array (){
                                     *     @type array Tab Id => Settings Tab Name
                                     * }
                                     */
                                    $settings_tabs = apply_filters('fbc_settings_tab_menus', array());
                                    $count = 1;

                                    foreach ($settings_tabs as $key => $tab_name ) {
                                        $active_tab = ( 1 === $count ) ? 'active' : '';
                                        ?>
                                        <li class="<?php echo $active_tab ?>">
                                            <a href="#<?php echo sanitize_key( $key ); ?>-settings" onClick="toggleDiv(this.hash); return false;">
                                                   <?php if ( 'General' == $tab_name ) { ?>
                                                <i class="fa fa-cogs"></i><span><?php echo esc_attr( $tab_name ); ?></span>

                                                <?php } elseif ( 'Typography' == $tab_name) { ?>
                                                    <i class="fa fa-text-height" aria-hidden="true"></i><span><?php echo esc_attr( $tab_name ); ?></span>

                                                <?php } ?>
                                            </a>
                                        </li>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Sections -->
                    <div class="fbc-col-lg-10 fbc-col-md-9 fbc-col-sm-8 fbc-col-xs-11" style="padding: 0;">
                        <div class="save-options">
                            <input type="submit" id="submit-btn" name="submit-btn" class="topbtn" value="<?php esc_html_e('Save Changes', 'flexy-breadcrumb'); ?>" />
                        </div>
                        <div class="main-content">

                            <!-- General Sections-->
                            <div id="general-settings">
                                <?php do_action('fbc_general_settings'); ?>
                            </div>

                            <!-- Typography Sections-->
                            <div id="typography-settings" style="display:none;">
                                <?php do_action('fbc_typography_settings'); ?>
                            </div>
                        </div>

                        <!-- Save Settings -->
                        <div class="save-options">
                            <input type="submit" id="submit-btn" name="submit-btn" class="topbtn" value="<?php esc_html_e('Save Changes', 'flexy-breadcrumb'); ?>" />
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }
}
new Flexy_Breadcrumb_Admin_Settings_Init();