<?php
/**
 * Flexy_Breadcrumb_Admin_Settings_General Class
 * 
 * Define Tab for General Settings
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/admin
 * @author     PressTigers <support@presstigers.com>
 */
class Flexy_Breadcrumb_Admin_Settings_General {

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     */
    public function __construct() {

        // Filter -> Add Settings General Tab
        add_filter('fbc_settings_tab_menus', array($this, 'add_settings_tab'), 20);

        // Action -> Add Settings General Section 
        add_action('fbc_general_settings', array($this, 'add_settings_section'), 20);

        // Action -> Save Settings General Section 
        add_action('fbc_save_setting_sections', array($this, 'save_settings_section'));
    }

    /**
     * Add Settings General Tab.
     *
     * @since    1.0.0
     * 
     * @param    array  $tabs  Settings Tab
     * @return   array  $tabs  Merge array of Settings Tab with General Tab.
     */
    public function add_settings_tab($tabs) {
        $tabs['general'] = esc_html__('General', 'flexy-breadcrumb');
        return $tabs;
    }

    /**
     * General Section.
     *
     * @since   1.0.0
     *
     * @global  array  $fbc_settings_options  WP options Settings for Flexy Breadcrumb.
     */
    public function add_settings_section() {
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbcObj = new Flexy_Breadcrumb();

        // Enqueue Alpha Color Picker Script
        wp_enqueue_script($fbcObj->get_plugin_name() . '-fontawesome-icon-picker');
        ?>

        <!-- General Header -->
        <div class="theme-header">
            <h1><?php esc_html_e('General Settings', 'flexy-breadcrumb'); ?></h1>
        </div>

        <!-- General Section Fields -->
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Home Text', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                <input type="text" id="breadcrumb-front-text" name="fbc_settings_options[breadcrumb_front_text]" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_front_text']); ?>" />
                <label><?php esc_html_e('Breadcrumb Front Text', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Home Icon', 'flexy-breadcrumb') ?></label>
            </li>
            <li class="element-field">
                <input type="text" id="breadcrumb-home-icon" class="fbc-home-icon" name="fbc_settings_options[breadcrumb_home_icon]" placeholder="fa fa-home" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_home_icon']); ?>" />
                <span class="input-group-addon"></span>
                <label><?php esc_html_e('Add any Font Awesome Icon', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Breadcrumb Separator', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                <input type="text" id="breadcrumb-separator" name="fbc_settings_options[breadcrumb_separator]" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_separator']); ?>" />
                <label><?php esc_html_e('Link Separator, ex: », Alt Code', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Set title limit in Words/Character', 'flexy-breadcrumb') ?></label>
            </li>
            <li class="element-field">
                <label>
                    <input type="radio" id="fbc-words-limit" name="fbc_settings_options[breadcrumb_limit_style]" value="word"<?php checked('word', esc_attr($fbc_settings_options['breadcrumb_limit_style'])); ?>/>
                    <span><?php esc_html_e('Words', 'flexy-breadcrumb'); ?></span>
                </label>
                <label>
                    <input type="radio" id="fbc-characters-limit" name="fbc_settings_options[breadcrumb_limit_style]" value="character"<?php checked('character', esc_attr($fbc_settings_options['breadcrumb_limit_style'])); ?>/>
                    <span><?php esc_html_e('Characters', 'flexy-breadcrumb'); ?></span>
                </label>
                <input type="text" id="breadcrumb-end-text" name="fbc_settings_options[breadcrumb_text_limit]" placeholder="25" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_text_limit']); ?>" maxlength="2" />
                <label><?php esc_html_e('Limit Your Title Strings', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field">
                
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Ending Character', 'flexy-breadcrumb') ?></label>
            </li>
            <li class="element-field">
                <input type="text" id="breadcrumb-end-text" name="fbc_settings_options[breadcrumb_end_text]" value="<?php echo esc_attr($fbc_settings_options['breadcrumb_end_text']); ?>" maxlength="3" />
                <label><?php esc_html_e('Up to 3 Characters', 'flexy-breadcrumb'); ?></label>
            </li>
        </ul>
        <ul class="form-elements">
            <li class="field-label">
                <label><?php esc_html_e('Post Types Hierarchy', 'flexy-breadcrumb'); ?></label>
            </li>
            <li class="element-field-radio">
                <label>
                    <input type="radio" id="fbc-post-category" name="fbc_settings_options[post_hierarchy]" value="post-category"<?php checked('post-category', esc_attr($fbc_settings_options['post_hierarchy'])); ?>/>
                    <span><?php esc_html_e('Category', 'flexy-breadcrumb'); ?></span>
                </label>
                <label>
                    <input type="radio" id="fbc-post-dates" name="fbc_settings_options[post_hierarchy]" value="post-date"<?php checked('post-date', esc_attr($fbc_settings_options['post_hierarchy'])); ?>/>
                    <span><?php esc_html_e('Date', 'flexy-breadcrumb'); ?></span>
                </label>
                <label>
                    <input type="radio" id="fbc-post-tags" name="fbc_settings_options[post_hierarchy]" value="post-tags"<?php checked('post-tags', esc_attr($fbc_settings_options['post_hierarchy'])); ?>/>
                    <span><?php esc_html_e('Tags', 'flexy-breadcrumb'); ?></span>
                </label>               
            </li>
        </ul>
        <div class="clear"></div>
        <?php
    }
}

new Flexy_Breadcrumb_Admin_Settings_General();