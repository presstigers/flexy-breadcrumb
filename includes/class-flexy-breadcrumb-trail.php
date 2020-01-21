<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/**
 * Flexy_Breadcrumb_Trail Class
 *
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://presstigers.com
 * @since      1.0.0
 * @since      1.0.1    Replaced site_url() function with home_url().
 * @since      1.0.2    Removed post ID from the active single post trail.
 * @since      1.0.3    Added space between the <a> attributes.
 * @since      1.1.0    Added breadcrumb trail for default posts page.
 * @since      1.1.0    Removed archive link for custom post type if archive parameter false.
 * @since      1.1.0    Display category in post detail page having highest count.
 * @since      1.1.0    Fixed Google Structued Schema for Breadcrumbs
 *
 * @package    Flexy_Breadcrumb
 * @subpackage Flexy_Breadcrumb/includes
 * @author     PressTigers <support@presstigers.com>
 */

class Flexy_Breadcrumb_Trail {

    /**
     * Breadcrumb Strings/Words limit mehtod
     * 
     * @return  string    
     */
    public function fbc_limit_strings($string, $fbc_limit_style, $fbc_text_limit, $fbc_end_text) {

        // Get FBC Settings Options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];

        // On Word Limit
        if ($fbc_limit_style == 'word') {

            // If limit is not a number then use defualt 4 words limit
            if (!is_numeric($fbc_text_limit)) {
                $fbc_text_limit = 4;
            }

            $retval = $string;  //    Just in case of a problem
            $array = explode(" ", $string);
            if (count($array) <= $fbc_text_limit) {
                $retval = $string;
            } else {
                array_splice($array, $fbc_text_limit);
                $retval = implode(" ", $array) . $fbc_end_text;
            }

            return $retval;
        } /* On Character Limit */ elseif ($fbc_limit_style == 'character') {

            // If limit is not a number then use defualt 25 limit
            if (!is_numeric($fbc_text_limit)) {
                $fbc_text_limit = 25;
            }
            if (strlen($string) > $fbc_text_limit) {
                $string = substr($string, 0, $fbc_text_limit);
                return $string . $fbc_end_text;
            } else {
                return $string;
            }
        }
    }

    /**
     * Breadcrumb separator template
     * 
     * @return  string   breadcrumb separator template 
     */
    public function fbc_separator_template() {

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_separator = $fbc_settings_options['breadcrumb_separator'];

        // Return Separator template
        return $fbc_separator;
    }

    /**
     * A Breadcrumb trail for day archive.
     * 
     * @since   1.0.0
     */
    public function fbc_day_archive_template() {

        // Separator template
        $separator_template = $this->fbc_separator_template();

        $year = get_the_time('Y');
        $month = get_the_time('m');
        $day = get_the_time('d');

        // Year link
        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("") . '"><a itemprop="item" href="' . esc_url(get_year_link(get_the_time('Y'))) . '" title="' . esc_attr(get_the_time('Y')) . '"><span itemprop="name">' . esc_attr(get_the_time('Y')) . '</span></a><meta itemprop="position" content="2"></li>';
        echo $separator_template;

        // Month link
        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("") . '"><a itemprop="item" href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '" title="' . esc_attr(get_the_time('M')) . '"><span itemprop="name">' . esc_attr(get_the_time('F')) . '</span></a><meta itemprop="position" content="3"></li>';
        echo $separator_template;

        // Day link
        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("") . '" class=" item-current item-' . esc_attr(get_the_time('j')) . '"><a itemprop="item" href="' . esc_url(get_day_link($year, $month, $day)) . '" title="' . esc_attr(get_the_time('M')) . '"><span itemprop="name">' . esc_attr(get_the_time('j')) . '</span></a><meta itemprop="position" content="4"></li>';
    }

    /**
     * Home template
     * 
     * @since   1.0.0
     */
    public function fbc_home_template() {

        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_front_text = $fbc_settings_options['breadcrumb_front_text'];
        $fbc_home_icon = $fbc_settings_options['breadcrumb_home_icon'];

        // Separator Template
        $separator_template = $this->fbc_separator_template();

        // Home Text & Icon
        if ($fbc_front_text || $fbc_home_icon) {
            ?>
            <li itemprop="itemListElement" itemscope itemtype="<?php echo esc_url(""); ?>">
                <span itemprop="name">
                    <!-- Home Link -->
                    <a itemprop="item" href="<?php echo esc_url(get_home_url()); ?>">
                        <?php if ($fbc_home_icon): ?>
                            <i class="fa <?php echo esc_attr($fbc_home_icon); ?>" aria-hidden="true"></i>

                            <?php
                        endif;

                        if ($fbc_front_text):
                            echo esc_attr($fbc_front_text);
                        endif;
                        ?>
                    </a>
                </span>
                <meta itemprop="position" content="1" /><!-- Meta Position-->
            </li>
            <?php
            echo $separator_template;
        }
    }

    /**
     * Breadcrumb trail for WP page.
     * 
     * @since   1.0.0
     */
    public function fbc_page_trail() {
        global $post;

        // Separator Template
        $separator_template = $this->fbc_separator_template();

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        $counter = (int) 2;

        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        // If parent page has child pages
        if ($post->post_parent) {

            // Get page ancestors
            $ancestors = get_post_ancestors($post->ID);

            // Reverse page ancestors's array
            $ancestors = array_reverse($ancestors);

            if ($ancestors) {

                // Retrieve ancestors & make breadcrumb trail
                foreach ($ancestors as $ancestor) {
                    echo'<li itemprop="itemListElement" itemscope itemtype="' . esc_url("") . '"><a itemprop="item" page-parent="' . esc_attr($ancestor) . '" href="' . esc_url(get_permalink($ancestor)) . '" title="' . esc_attr(get_the_title($ancestor)) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr(get_the_title($ancestor)), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>' . $separator_template . '';
                    $counter++;
                }
                if (get_query_var('paged') > 1 || get_query_var('page') > 1) {
                    // Current page
                    echo'<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" page-parent="' . esc_attr($ancestor) . '" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr(get_the_title()), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>';
                    $this->fbc_pagination_trail();
                } else {

                    // Current page
                    echo'<li class="active post-page" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name" title="' . esc_attr(get_the_title()) . '">' . $this->fbc_limit_strings(esc_attr(get_the_title()), $fbc_limit_style = 'word', $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>';
                }
            }
        } else {
            if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

                // Current page
                echo'<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url(get_permalink()) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr(get_the_title()), $fbc_limit_style = 'word', $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>';
                $this->fbc_pagination_trail();
            } else {
                echo'<li class="active" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name" title="' . esc_attr(get_the_title()) . '">' . $this->fbc_limit_strings(esc_attr(get_the_title()), $fbc_limit_style = 'word', $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>';
            }
        }
    }

    /**
     * Breadcrumb trail for the terms of custom post.
     * 
     * @since   1.0.0
     */
    public function fbc_cpt_terms_trail() {

        // Separator Template
        $separator_template = $this->fbc_separator_template();

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        $counter = (int) 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        // If custom post type
        $post_type = get_post_type();

        // Retrieve custom post type terms & make breadcrumb trail
        if ($post_type != 'post') {
            $taxonomy = get_queried_object()->taxonomy;
            $term_id = (get_queried_object()->term_id);
            $current_tax_name = get_queried_object()->name;
            $ancestors = get_ancestors($term_id, $taxonomy);

            if (!empty($ancestors)) {
                // Reverse array to put the top level ancestor first
                $ancestors = array_reverse($ancestors);

                if ($ancestors) {
                    foreach ($ancestors as $ancestor) {
                        $term = get_term($ancestor, $taxonomy);
                        echo '<li item-parent itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" cpt-term="' . esc_attr($term_id) . '" href="' . esc_url(get_term_link($ancestor)) . '" title="' . esc_attr($term->name) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr($term->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>';
                        echo $separator_template;
                    }
                }
            }

            if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

                // Current Page
                echo '<li class="item-current item-archive" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" cpt-term="' . esc_attr($term_id) . '" href="' . esc_url(get_term_link($term_id)) . '" title="' . esc_attr($current_tax_name) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr($current_tax_name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>';
                $this->fbc_pagination_trail();
            } else {

                // Current Page
                echo '<li class="active item-current item-archive" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name" title="' . esc_attr($current_tax_name) . '">' . $this->fbc_limit_strings(esc_attr($current_tax_name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter . '" /></li>';
            }
        }
    }

    /**
     * Breadcrumb for archive page.
     * 
     * @since   1.0.0
     */
    public function fbc_post_archive_trail() {

        // Separator Template
        $separator_template = $this->fbc_separator_template();
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        // If custom post type
        $post_type = get_post_type();

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

            // Current Page
            echo '<li class="item-current item-archive" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a href="' . get_post_type_archive_link($post_type) . '" title="' . esc_attr(post_type_archive_title('', '')) . '">' . post_type_archive_title('<span itemprop="name">', '') . '</span></a><meta itemprop="position" content="2" /></li>';
            $this->fbc_pagination_trail();
        } else {

            // Current Page
            echo '<li class="active item-current item-archive" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '">' . post_type_archive_title('<span itemprop="name">', '') . '</span></a><meta itemprop="position" content="2" /></li>';
        }
    }

    /**
     * Breadcrumb for custom post type detail page.
     * 
     * @since   1.0.0
     */
    public function fbc_post_detail_trail() {
        global $post;

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        // Separator Template
        $separator_template = $this->fbc_separator_template();


        $post_type = get_post_type();

        // If custom post type
        if ($post_type != 'post') {
            $post_type_object = get_post_type_object($post_type);
            $post_type_archive = get_post_type_archive_link($post_type);

            // Current Page
            if ($post_type_archive) {
                echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" fbc-' . esc_attr($post_type) . '" href="' . esc_url($post_type_archive) . '" title="' . esc_attr($post_type_object->labels->name) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr($post_type_object->labels->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
                echo $separator_template;
            }
            echo'<li class="active" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name" title="' . esc_attr(get_the_title()) . '">' . $this->fbc_limit_strings(esc_attr(get_the_title()), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }

        // For WP post
        elseif ($post_type = 'post') {
            // Post hierarchy - post category
            if ('post-category' == $fbc_settings_options['post_hierarchy']) {
                // bcn_display($return = false, $linked = true, $reverse = false, $force = false);
                $parent = array();
                $children = array();
                $selected_parents = array();
                $selected_node = array();
                $selected_children = array();
                $category = get_the_category($post->ID);
                if ($category):
                    $parent_count = 0;
                    $child_count = 0;
                    $parent = array();

                    foreach ($category as $cat):
                        //Get the cats that have parents
                        if ($cat && $cat):
                            $ancestors = array();
                            $ancestors = get_ancestors($cat->term_id, 'category');
                            $cat_children = get_term_children($cat->term_id, $cat->taxonomy);

                            if (count($ancestors) > $parent_count):
                                $parent = end($ancestors);
                                $parent_count = count($ancestors);
                                $child_count = count($cat_children);
                                $selected_children = $cat_children;
                                $selected_node = $cat;
                                $selected_parents = $ancestors;
                            elseif (count($ancestors) == $parent_count && count($cat_children) >= $child_count):
                                if ($cat->parent == 0):
                                    $parent = $cat->term_id;
                                else:
                                    $parent = end($ancestors);
                                endif;
                                $parent_count = count($ancestors);
                                $child_count = count($cat_children);
                                $selected_children = $cat_children;
                                $selected_node = $cat;
                                $selected_parents = $ancestors;
                            endif;
                        endif;
                    endforeach;
                endif;

                if ($parent):
                    $parent_term = get_term($parent);
                    $parent_term_id = $parent_term->term_id;
                    $parent_name = $parent_term->name;
                    $parent_category_link = get_category_link($parent_term);
                    $trim_cat = $this->fbc_limit_strings(esc_attr($parent_term->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text);
                    $cat_link = '<a itemprop="item" id="' . $parent_term_id . '" href="' . esc_url($parent_category_link) . '" title="' . esc_attr($parent_name) . '">' . $trim_cat . '</a>';
                    $cat_trail = '<li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem"><span itemprop="name" title="Category Name">' . $cat_link . '</span><meta itemprop="position" content="' . $counter++ . '" /></li>' . $separator_template;
                    $child_categories = get_categories(
                            array('child_of' => $parent)
                    );
                    foreach ($child_categories as $chi):
                        if (in_array($chi->term_id, $selected_parents) || $chi->term_id == $selected_node->term_id):
                            $children[] = $chi->slug;
                            $child_cat = $chi->name;
                            $child_id = $chi->term_id;
                            $category_link = get_category_link($child_id);
                            $trim_cat = $this->fbc_limit_strings(esc_attr($chi->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text);
                            $cat_link = '<a itemprop="item" id="' . $child_id . '" href="' . esc_url($category_link) . '" title="' . esc_attr($chi->name) . '">' . $trim_cat . '</a>';
                            $cat_trail .= '<li itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem"><span itemprop="name" title="' . $child_cat . '">' . $cat_link . '</span><meta itemprop="position" content="' . $counter++ . '" /></li>' . $separator_template;
                        endif;
                    endforeach;
                    echo $cat_trail;
                endif;
            }

            // Post hierarchy - post date
            if ('post-date' == $fbc_settings_options['post_hierarchy']) {
                $this->fbc_day_archive_template();
                echo $separator_template;
            }

            // Post hierarchy - post tags
            if ('post-tags' == $fbc_settings_options['post_hierarchy']) {
                $tags = get_the_tags();

                if ($tags) {
                    foreach ($tags as $tag) {
                        $trim_tag = $this->fbc_limit_strings(esc_attr($tag->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text);
                        $tag_link[] = '<a itemprop="item" href="' . esc_url(get_tag_link($tag->term_id)) . '" title="' . esc_attr($tag->name) . '">' . $trim_tag . '</a>';
                    }
                    echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><span itemprop="name">' . implode(', ', $tag_link) . '</span><meta itemprop="position" content="' . $counter++ . '" /></li>';
                    echo $separator_template;
                }
            }

            // Show seperator for non-empty tags and categories
            if (!empty($categories) || !empty($tags_list)) {
                echo $separator_template;
            }
            global $wp;
            $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

            // Current Page
            echo '<li class="active" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '">';
            echo'<span itemprop="name" title="' . esc_attr(get_the_title()) . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '">' . $this->fbc_limit_strings(esc_attr(get_the_title()), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</a></span>';
            echo'<meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    /**
     * Breadcrumb for post categories.
     * 
     * @since   1.0.0
     */
    public function fbc_category_trail() {

        // Separator Template
        $separator_template = $this->fbc_separator_template();

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        $current_cat_id = get_query_var('cat');
        $ancestor_ids = array_reverse(get_ancestors($current_cat_id, 'category'));
        $cat = get_term($current_cat_id, 'category');

        // Retrieve category ancestors
        if (is_array($ancestor_ids)) {
            foreach ($ancestor_ids as $id) {
                $parent = get_term($id, 'category');
                echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" item-cat="' . $id . '" href="' . esc_url(get_category_link($parent->term_id)) . '" title="' . esc_attr($parent->name) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr($parent->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
                echo $separator_template;
            }
        }

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

            // Current Page
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" item-cat="' . $current_cat_id . '" title="' . esc_attr($cat->name) . '" href="' . esc_url(get_category_link($current_cat_id)) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr($cat->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
            $this->fbc_pagination_trail();
        } else {

            // Current Page
            echo '<li class="active" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name" title="' . esc_attr($cat->name) . '">' . $this->fbc_limit_strings(esc_attr($cat->name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    /**
     * A Breadcrumb trail for a WP tag.
     * 
     * @since   1.0.0
     */
    public function fbc_tag_trail() {

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        // Get tag information
        $term_id = (int) get_query_var('tag_id');
        $args = array(
            'include' => $term_id,
            'hide_empty' => FALSE,
        );

        // Get terms of tags
        $terms = get_terms('post_tag', $args);
        $get_term_id = (int) $terms[0]->term_id;
        $get_term_slug = $terms[0]->slug;
        $get_term_name = $terms[0]->name;

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . '"><a itemprop="item" title="' . esc_attr($get_term_name) . ' " href="' . esc_url(get_tag_link($get_term_id)) . '"><span itemprop="name">' . $this->fbc_limit_strings(esc_attr($get_term_name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
            $this->fbc_pagination_trail();
        } else {

            // Display the tag name
            echo '<li class="active item-current item-tag-' . $get_term_id . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name" title="' . esc_attr($get_term_name) . '">' . $this->fbc_limit_strings(esc_attr($get_term_name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    /**
     * A Breadcrumb trail for day archive.
     * 
     * @since   1.0.0
     */
    public function fbc_day_archive_trail() {

        // Separator template
        $separator_template = $this->fbc_separator_template();
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));
        $year = get_the_time('Y');
        $month = get_the_time('m');
        $day = get_the_time('d');

        // Year link
        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url(get_year_link(get_the_time('Y'))) . '" title="' . esc_attr(get_the_time('Y')) . '"><span itemprop="name">' . esc_attr(get_the_time('Y')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        echo $separator_template;

        // Month link
        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '" title="' . esc_attr(get_the_time('M')) . '"><span itemprop="name">' . esc_attr(get_the_time('F')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        echo $separator_template;

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {
            // Day
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="item-' . esc_attr(get_the_time('j')) . '"><a itemprop="item" href="' . esc_url(get_day_link($year, $month, $day)) . '" title="' . esc_attr(get_the_time('M')) . '"><span itemprop="name">' . esc_attr(get_the_time('j')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
            $this->fbc_pagination_trail();
        } else {
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="active item-current item-' . esc_attr(get_the_time('j')) . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name">' . esc_attr(get_the_time('j')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    /**
     * A Breadcrumb trail for years archive.
     * 
     * @since   1.0.0
     */
    public function fbc_yearly_archive_trail() {

        // Separator template
        $separator_template = $this->fbc_separator_template();
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {
            // Year link
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url(get_year_link(get_the_time('Y'))) . '" title="' . esc_attr(get_the_time('Y')) . '"><span itemprop="name">' . esc_attr(get_the_time('Y')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
            $this->fbc_pagination_trail();
        } else {
            // Year link
            echo '<li class="active" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="item-current item-' . esc_attr(get_the_time('Y')) . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name">' . esc_attr(get_the_time('Y')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    /**
     * A Breadcrumb trail for month archive.
     * 
     * @since   1.0.0
     */
    public function fbc_monthly_archive_trail() {

        // Separator template
        $separator_template = $this->fbc_separator_template();
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        // Year link
        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url(get_year_link(get_the_time('Y'))) . '" title="' . esc_attr(get_the_time('Y')) . '">' . esc_attr(get_the_time('Y')) . '</a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        echo $separator_template;

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

            // Month link
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '" title="' . esc_attr(get_the_time('M')) . '"><span itemprop="name">' . esc_attr(get_the_time('F')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
            $this->fbc_pagination_trail();
        } else {
            // Month link
            echo '<li class="active item-current item-' . esc_attr(get_the_time('F')) . '" itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name">' . esc_attr(get_the_time('F')) . '</span></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    /**
     * A Breadcrumb trail for an author page.
     */
    public function fbc_author_page_trail() {

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        global $author;

        $author_data = get_userdata($author);

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

            // Display author name
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="item-current-' . esc_attr($author_data->user_nicename) . '"><a itemprop="item" href="' . esc_url(get_author_posts_url('', $author_data->user_nicename)) . '"><strong><span itemprop="name">' . esc_html__('Author: ', 'flexy-breadcrumb') . $this->fbc_limit_strings(esc_attr($author_data->display_name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></strong></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
            $this->fbc_pagination_trail();
        } else {
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="active item-current-' . esc_attr($author_data->user_nicename) . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><strong><span itemprop="name">' . esc_html__('Author: ', 'flexy-breadcrumb') . $this->fbc_limit_strings(esc_attr($author_data->display_name), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></strong></a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    /**
     * Breadcrumb for 404 page.
     * 
     * @since   1.0.0
     */
    public function fbc_404() {
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="active item-current"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name">' . esc_html__('Error 404', 'flexy-breadcrumb') . '</span></a><meta itemprop="position" content="2"></li>';
    }

    /**
     * Breadcrumb for paged pagination.
     * 
     * @since   1.0.0
     */
    public function fbc_pagination_trail() {

        // Separator template
        $separator_template = $this->fbc_separator_template();
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        echo $separator_template;

        // Get paged variable.
        if (get_query_var('paged') > 0) {
            $paged = (int) get_query_var('paged');
        } elseif (get_query_var('page') > 0) {
            $paged = absint(get_query_var('page'));
        }
        $count = (int) get_query_var('page');
        echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="active item-current-' . $paged . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><strong><span itemprop="name">' . esc_html__('Page', 'flexy-breadcrumb') . ' ' . $paged . '</span></strong></a><meta itemprop="position" content="' . $count . '"></li>';
    }

    /**
     * A Breadcrumb for search page.
     * 
     * @since   1.0.0
     */
    public function fbc_search_page_trail() {

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

            // Search results page
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="item-current-' . esc_attr(get_search_query()) . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name"><strong>' . __('Search: ', 'flexy-breadcrumb') . '</strong> <a itemprop="item" href="' . get_search_link() . '">' . $this->fbc_limit_strings(esc_attr(get_search_query()), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="2"></li>';
            $this->fbc_pagination_trail();
        } else {
            echo '<li itemprop="itemListElement" itemscope itemtype="' . esc_url("https://schema.org/ListItem") . '" class="active item-current-' . esc_attr(get_search_query()) . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '"><span itemprop="name"><strong>' . __('Search: ', 'flexy-breadcrumb') . '</strong>' . $this->fbc_limit_strings(esc_attr(get_search_query()), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span></a><meta itemprop="position" content="2"></li>';
        }
    }

    /**
     * A Breadcrumb for page posts.
     * 
     * @since   1.1.0
     */
    public function fbc_page_post_trail() {

        // Get FBC settings options
        $fbc_settings_options = get_option('fbc_settings_options');
        $fbc_limit_style = $fbc_settings_options['breadcrumb_limit_style'];
        $fbc_text_limit = $fbc_settings_options['breadcrumb_text_limit'];
        $fbc_end_text = $fbc_settings_options['breadcrumb_end_text'];
        $page_post_title = get_the_title(get_option('page_for_posts', true));
        $counter = 2;
        global $wp;
        $current_url = trailingslashit(home_url(add_query_arg(array(), $wp->request)));

        if (get_query_var('paged') > 1 || get_query_var('page') > 1) {

            // Current Page
            echo '<li class="active" itemprop="itemListElement" itemscope itemtype="' . esc_url("") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '">';
            echo'<span itemprop="name" title="' . esc_attr($page_post_title) . '">' . $this->fbc_limit_strings(esc_attr($page_post_title), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span>';
            echo'</a><meta itemprop="position" content="' . $counter++ . '" /></li>';
            $this->fbc_pagination_trail();
        } else {
            // Current Page
            echo '<li class="active" itemprop="itemListElement" itemscope itemtype="' . esc_url("") . '"><a itemprop="item" href="' . esc_url($current_url) . '" title="' . esc_attr(get_the_title()) . '">';
            echo'<span itemprop="name" title="' . esc_attr($page_post_title) . '">' . $this->fbc_limit_strings(esc_attr($page_post_title), $fbc_limit_style, $fbc_text_limit, $fbc_end_text) . '</span>';
            echo'</a><meta itemprop="position" content="' . $counter++ . '" /></li>';
        }
    }

    public function fbc_pick_post_term() {
        $parent = array();
        $children = array();

        $category = get_the_category($post->ID);
        if ($category):
            foreach ($category as $cat):

                //Get the cats that have parents
                if ($cat->parent != 0) :
                    $ancestors = get_ancestors(6, 'category');
                    $parent[] = end($ancestors);
                else:
                    $parent[] = $cat->term_id;
                endif;
            endforeach;
        endif;

        if ($parent):
            foreach ($parent as $par):
                $child_categories = get_categories(
                        array('child_of' => $par)
                );
                foreach ($child_categories as $chi):
                    $children[] = $chi->slug;
                    echo '/' . '<li>' . $chi->slug . '</li>';

                endforeach;
            endforeach;
        endif;
    }

}

new Flexy_Breadcrumb_Trail();
