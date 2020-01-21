# Flexy Breadcrumb

Flexy Breadcrumb by <a href="https://www.presstigers.com">PressTigers</a> is one of the simple and robust breadcrumb menu system available for the WordPress site. By using this plugin, you can display breadcrumb navigation anywhere in your website via [flexy_breadcrumb] shortcode.
With the help of this plugin you can style and format the text, links and separators of breadcrumbs according to your own taste.

### Plugin Features

* SEO Friendly(added schema structure).
* Use via [flexy_breadcrumb] shortcode. 
* Allow users to change breadcrumb separator.
* Set Home text and End text.
* Set Word/Character limit for navigation menu.
* Font Awesome icon picker for Home.
* Color options for text, link, separator and background through global settings.
* Set font size of breadcrumb trail.

### Shortcode
``` [flexy_breadcrumb] ```

### Submitting Patches =
If you’ve identified a bug and have a fix, we’d welcome it at our [GitHub page for Flexy Breadcrumb](https://github.com/presstigers/flexy-breadcrumb/). Simply submit a pull request so we can review and merge into the codebase if appropriate from there.Happy coding!

## Installation

1. Upload flexy-breadcrumb.zip to the /wp-content/plugins/ directory to your web server.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Add [flexy_breadcrumb] shortcode in the editor or appropriate file(header.php) to display the breadcrumb on front-end.

## Frequently Asked Questions

= How to use Flexy Breadcrumb Shortcode? =
There are several methods but in general, you need to add the following line of code to your theme. This goes somewhere near the bottom of your theme's header.php template. However, you can add it anywhere you want in your theme, and it'll work.

```<?php echo do_shortcode( '[flexy_breadcrumb]'); ?>```

## Credits

* [Google Fonts] (https://fonts.google.com)
* [jQuery UI] (https://jqueryui.com)
* [WP Color Picker Alpha] (https://github.com/23r9i0/wp-color-picker-alpha)
* [Font Awesome Icon Picker](https://github.com/itsjavi/fontawesome-iconpicker)
 
## Changelog

= 1.1.1 =
* Fix - Added slash at the end of the URL - Google Recommendation
* Fix - Wrapping the admin style to avoid from any style conflict.
* Tweak - Update the schema links to https.
