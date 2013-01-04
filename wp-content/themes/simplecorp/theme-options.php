<?php
/*********************************************************************************************

Initalize Framework Settings

*********************************************************************************************/
if ( !function_exists( 'optionsframework_init' ) ) {

define('OPTIONS_FRAMEWORK_URL',  get_template_directory_uri() . '/admin/');
define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory() . '/admin/');


require (OPTIONS_FRAMEWORK_DIRECTORY . 'options-framework.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/contentvalidation.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/customfunctions.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/custompostypes.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/featured.metabox.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/portfolio.metabox.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/page.metabox.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/pagination.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/widgets.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/wphooks.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/wpnavmenu.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/customizer.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'inc/ajax-thumbnail-rebuild.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'shortcodes/shortcodes.php');
require (OPTIONS_FRAMEWORK_DIRECTORY . 'shortcodes/shortcodespanel.php');
}

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */

if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = false) {
    $optionsframework_settings = get_option('optionsframework');
    // Gets the unique option id
    $option_name = $optionsframework_settings['id'];
    if ( get_option($option_name) ) {
        $options = get_option($option_name);
    }
    if ( isset($options[$name]) ) {
        return $options[$name];
    } else {
        return $default;
    }
}
}

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(get_stylesheet_directory() . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );

	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {

	$shortname = "sc";
	
	$portfolio_array = array(
    "fullwidth" => "Full Width",
    "withsidebar" => "With Sidebar"
    );

    $sliders_array = array(
    "none" => "None",
    //"nivo" => "Nivo Slider",
    "flex" => "Flex Slider"
    );
	
	$slidersfx_array = array(
    "fade" => "fade",
	"slide" => "slide"
    );

	$sliders = get_categories('taxonomy=sliders&type=featured'); 
	$sliders_tags_array[''] = 'Select a Slider';
	foreach ($sliders as $slider) {
    	$sliders_tags_array[$slider->cat_ID] = $slider->cat_name;
	}

    $numberofs_array = array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10", "11" => "11", "12" => "12", "13" => "13", "14" => "14", "15" => "15", "16" => "16", "17" => "17", "18" => "18", "19" => "19", "20" => "20");

	// Multicheck Array

    $robots_array = array(
    "none" => "none",
    "index,follow" => "index,follow",
    "index, follow" => "index, follow",
    "index,nofollow" => "index,nofollow",
    "index,all" => "index,all",
    "index,follow,archive" => "index,follow,archive",
    "noindex,follow" => "noindex,follow",
    "noindex,nofollow" => "noindex,nofollow"
    );


	// Background Defaults

	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');


	// Theme Color Schemes

	$colorschemes = array(
		'light-blue'=>'Light Blue',
		'light-blue-dark'=>'Light Blue Dark',
		'light-green'=>'Light Green',
		'light-gray'=>'Light Gray',
		'light-orange'=>'Light Orange',
		'light-purple'=>'Light Purple',
		'light-red'=>'Light Red',
		'light-yellow'=>'Light Yellow',

		'dark-blue'=>'Dark Blue',
		'dark-blue-dark' => 'Dark Blue Dark',
		'dark-green'=>'Dark Green',
		'dark-green-dark'=>'Dark Green Dark',
		'dark-pink'=>'Dark Pink',
		'dark-purple'=>'Dark Purple',
		'dark-red'=>'Dark Red',
		'dark-yellow'=>'Dark Yellow'

	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	$options_categories[''] = 'All Categories';
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . 'admin/images/';
	$blogpath  =  get_template_directory_uri() . '';

	$options = array();


/*********************************************************************************************

Initalize Theme Options

*********************************************************************************************/
if ( !function_exists( 'optionsframeworks_init' ) ) {

define('OPTIONS_URL', get_template_directory_uri() . '/admin/options/');
define('OPTIONS_DIRECTORY', get_template_directory() . '/admin/options/');

require( OPTIONS_DIRECTORY . 'general.php' );
require( OPTIONS_DIRECTORY . 'typography.php' );
require( OPTIONS_DIRECTORY . 'homepage.php');
require( OPTIONS_DIRECTORY . 'slider.php' );
require( OPTIONS_DIRECTORY . 'blog.php' );
require( OPTIONS_DIRECTORY . 'portfolio.php' ); 
require( OPTIONS_DIRECTORY . 'contact.php' );
require( OPTIONS_DIRECTORY . 'social.php' );
require( OPTIONS_DIRECTORY . 'meta.php' );
require( OPTIONS_DIRECTORY . 'footer.php' );
require( OPTIONS_DIRECTORY . 'css.php' );
require( OPTIONS_DIRECTORY . 'thumbnails.php' );

}

	return $options;
}


/*********************************************************************************************

Upload Mime-types

*********************************************************************************************/
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
 $existing_mimes['ico'] = 'application/x-ico';
 return $existing_mimes;
}
?>