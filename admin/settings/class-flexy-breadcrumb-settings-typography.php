<?php
/**
 * Flexy_Breadcrumb_Admin_Settings_Typography Class
 * 
 * Define Tab for Typography Settings
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/admin
 * @author     PressTigers <support@presstigers.com>
 */
class Flexy_Breadcrumb_Admin_Settings_Typography {

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     */
    public function __construct() {

        // Filter -> Add Typography Tab
        add_filter('fbc_settings_tab_menus', array($this, 'add_settings_tab'), 20);

        // Action -> Add Settings Typography Section 
        add_action('fbc_typography_settings', array($this, 'add_typography_settings_section'), 20);

        // Action -> Save Settings Typography Section 
        add_action('fbc_save_setting_sections', array($this, 'save_settings_section'));
    }

    /**
     * Add Typography Tab.
     *
     * @since    1.0.0
     * 
     * @param    array  $tabs  Settings Tab
     * @return   array  $tabs  Merge array of Settings with Typography Tab.
     */
    public function add_settings_tab($tabs) {
        $tabs['typography'] = esc_html__( 'Typography', 'flexy-breadcrumb' );
        return $tabs;
    }

    /**
     * Typography Section.
     * 
     * @since    1.0.0
     * 
     *  @global   array    $fbc_settings_options  WP options Settings for Flexy Breadcrumb. 
     */
    public function add_typography_settings_section() {
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbcObj = new Flexy_Breadcrumb();
        
        // Enqueue Alpha Color Picker Script
        wp_enqueue_script( $fbcObj->get_plugin_name() . '-wp-color-picker-alpha' );
        ?>

        <!-- Typography Header -->
        <div class="theme-header">
            <h1><?php esc_html_e('Typography', 'flexy-breadcrumb'); ?></h1>
        </div>

        <!-- Typography Section --> 
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Breadcrumb Text Color', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                <input type="text" name="fbc_settings_options[breadcrumb_text_color]" id="breadcrumb-text-color" class="fbc-color-picker" data-alpha="true" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_text_color']); ?>" data-default-color="#27272a" />
                <label><?php esc_html_e('Breadcrumb Text Color', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Breadcrumb Link Color', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                <input type="text" name="fbc_settings_options[breadcrumb_link_color]" id="breadcrumb-link-color" class="fbc-color-picker" data-alpha="true" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_link_color']); ?>" data-default-color="#337ab7" />
                <label><?php esc_html_e('Breadcrumb Link Color', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Breadcrumb Separator Color', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                <input type="text" name="fbc_settings_options[breadcrumb_separate_color]" id="breadcrumb-separate-color" class="fbc-color-picker" data-alpha="true" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_separate_color']); ?>" data-default-color="#cccccc" />
                <label><?php esc_html_e('Breadcrumb Separator Color', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Breadcrumb Background Color', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                <input type="text" name="fbc_settings_options[breadcrumb_background_color]" id="breadcrumb-background-color" class="fbc-color-picker" data-alpha="true" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_background_color']); ?>" data-default-color="#edeff0" />
                <label><?php esc_html_e('Breadcrumb Background Color', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Breadcrumb Font Size', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                <input type="text" name="fbc_settings_options[breadcrumb_font_size]" id="breadcrumb-font-size" placeholder="12px" class="fbc-breadcrumb-font-size numbers-only" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_font_size']); ?>"  />
                <span class="add-on"><?php esc_html_e('px', 'flexy-breadcrumb'); ?></span>
                <label><?php esc_html_e('Breadcrumb Font Size', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <div class="clear"></div> 

        <?php
    }
}
new Flexy_Breadcrumb_Admin_Settings_Typography();