<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
/**
 * Flexy_Breadcrumb_Shortcode Class
 *
 * This class shows the breadcrumb structure on frontend for [flexy_breabcrumb] shortcode.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 * @since      1.1.0 Added breadcrumb trail for default posts page
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/includes
 * @author     PressTigers <support@presstigers.com>
 */

class Flexy_Breadcrumb_Shortcode {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Shortcode - Flexy Breadcrumb
		add_shortcode( 'flexy_breadcrumb', array( $this, 'flexy_breadcrumb_shortcode' ) );
	}

	/**
	 * Flexy Breadcrumb Shortcode
	 *
	 * @param   array   $atts      Shortcode Attribute
	 * @param   array   $content   Shortcode Content
	 * 
	 * @return  HTML    Breadcrumb HTML
	 */
	public function flexy_breadcrumb_shortcode( $atts, $content = NULL ) {
		
		ob_start();
		global $post, $shortcode_args;

		// Shortcode Default Array
		$shortcode_args = array(
			'before' => '<p>',
			'after' => '</p>',
		);

		// Combines user shortcode attributes with known attributes
		$shortcode_args = shortcode_atts( apply_filters( 'fbc_template_params', $shortcode_args, $atts ), $atts );

		// Display breadcrumb other than front page
		if ( !is_front_page() ) {
			?>
			<!-- Flexy Breadcrumb -->
			<div class="fbc fbc-page">

				<!-- Breadcrumb wrapper -->
				<div class="fbc-wrap">

					<!-- Ordered list-->
					<ol class="fbc-items" itemscope itemtype="<?php echo esc_url( "https://schema.org/BreadcrumbList" ); ?>">
						<?php
						// Breadcrumb Trail Class Object
						$fbcObj = new Flexy_Breadcrumb_Trail();
						$fbcObj->before_wrap = $shortcode_args['before'];
						$fbcObj->after_wrap = $shortcode_args['after'];

						// Home text & icon
						$fbcObj->fbc_home_template();

						// For Posts Page
						$post_page_title = get_the_title( get_option( 'page_for_posts', true ) );

						// For WooCommerce Shop Page
						if ( class_exists( 'WooCommerce' ) && is_shop() ) {
							global $post;
							$page_id = wc_get_page_id( 'shop' );
							$post = get_post( $page_id );
							$fbcObj->fbc_page_trail();
						} elseif /* For Page and its Child Pages */ ( is_page() ) {
							$fbcObj->fbc_page_trail();
						} elseif /* For Custom Post Type Listing or Category Pages */ ( is_tax() && !is_category() && !is_tag() ) {
							$fbcObj->fbc_cpt_terms_trail();
						} elseif /* For Post Type Archive Pages */ ( is_archive() && !is_tax() && !is_category() && !is_tag() && !is_month() && !is_year() && !is_day() && !is_author() ) {
							$fbcObj->fbc_post_archive_trail();
						} elseif /* For Post Type Detail Page */ ( is_single() ) {
							$fbcObj->fbc_post_detail_trail();
						} elseif /* For Default Post Categories, Tags, Parent Categories and Child Categories */ ( is_category() ) {
							$fbcObj->fbc_category_trail();
						} elseif /* For Tag Page */ ( is_tag() ) {
							$fbcObj->fbc_tag_trail();
						} elseif /* For Day Archive Page */ ( is_day() ) {
							$fbcObj->fbc_day_archive_trail();
						} else if /* For Monthly Archive Page */ ( is_month() ) {
							$fbcObj->fbc_monthly_archive_trail();
						} elseif /* For Day Yearly Page */ ( is_year() ) {
							$fbcObj->fbc_yearly_archive_trail();
						} elseif /* For Author Page */ ( is_author() ) {
							$fbcObj->fbc_author_page_trail();
						} elseif /* For Search Page */ ( is_search() ) {
							$fbcObj->fbc_search_page_trail();
						} elseif /* For 404 Page */ ( is_404() ) {
							$fbcObj->fbc_404();
						} elseif /* For Posts Page */ ( NULL !== $post_page_title ) {
							$fbcObj->fbc_page_post_trail();
						}
						?>
					</ol>
					<div class="clearfix"></div>
				</div>
			</div>
			<?php
		}
		$html = ob_get_clean();
		return apply_filters('sep_fb_event_listing_shortcode', $html . do_shortcode($content));
	}

}

new Flexy_Breadcrumb_Shortcode();