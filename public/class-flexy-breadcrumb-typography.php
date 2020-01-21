<?php
/**
 * Flexy_Breadcrumb_Typography Class
 * 
 * This is used to implement typography settings. This class applying custom 
 * typography on event listing and calendar page.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/public
 * @author     PressTigers <support@presstigers.com>
 */
class Flexy_Breadcrumb_Typography {

    /**
     * Initialize the class and set its properties.
     * 
     * @since   1.0.0
     */
    public function __construct() {

        // Hook -> Trigger user defined styles in head section
        add_action('wp_head', array($this, 'breadcrumb_typography'));
    }

    /**
     * This function implementing style changes on calender and event listing
     * 
     * @since  1.1.0
     */
    public function breadcrumb_typography() {
        if (!is_front_page()) {

            $fbc_settings_options = get_option('fbc_settings_options');

            /* Typography Styling */
            $fbc_text_color = $fbc_settings_options['breadcrumb_text_color'];

            $fbc_link_color = $fbc_settings_options['breadcrumb_link_color'];

            $fbc_separate_color = $fbc_settings_options['breadcrumb_separate_color'];

            $fbc_background_color = $fbc_settings_options['breadcrumb_background_color'];

            $fbc_font_size = $fbc_settings_options['breadcrumb_font_size'];
            ?>

            <style type="text/css">              
                
                /* Background color */
                .fbc-page .fbc-wrap .fbc-items {
                    background-color: <?php echo esc_attr( $fbc_background_color ); ?>;
                }
                /* Items font size */
                .fbc-page .fbc-wrap .fbc-items li {
                    font-size: <?php echo intval( $fbc_font_size ); ?>px;
                }
                
                /* Items' link color */
                .fbc-page .fbc-wrap .fbc-items li a {
                    color: <?php echo esc_attr( $fbc_link_color ); ?>;                    
                }
                
                /* Seprator color */
                .fbc-page .fbc-wrap .fbc-items li .fbc-separator {
                    color: <?php echo esc_attr( $fbc_separate_color ); ?>;
                }
                
                /* Active item & end-text color */
                .fbc-page .fbc-wrap .fbc-items li.active span,
                .fbc-page .fbc-wrap .fbc-items li .fbc-end-text {
                    color: <?php echo esc_attr( $fbc_text_color ); ?>;
                    font-size: <?php echo intval($fbc_font_size); ?>px;
                }
            </style>

            <?php
        }
    }
}
new Flexy_Breadcrumb_Typography();