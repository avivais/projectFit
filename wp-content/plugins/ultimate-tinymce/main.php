<?php
/**
 * @package Ultimate TinyMCE
 * @version 4.0.2
 */
/*
Plugin Name: Ultimate TinyMCE
Plugin URI: http://www.plugins.joshlobe.com/
Description: Beef up your visual tinymce editor with a plethora of advanced options.
Author: Josh Lobe
Version: 4.0.2
Author URI: http://joshlobe.com

*/

/*  Copyright 2011  Josh Lobe  (email : joshlobe@joshlobe.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details and information.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include WP_CONTENT_DIR . '/plugins/ultimate-tinymce/includes/defaults.php';
include WP_CONTENT_DIR . '/plugins/ultimate-tinymce/includes/uninstall.php';
include WP_CONTENT_DIR . '/plugins/ultimate-tinymce/options_functions.php';
include WP_CONTENT_DIR . '/plugins/ultimate-tinymce/options_callback_functions.php';
include WP_CONTENT_DIR . '/plugins/ultimate-tinymce/admin_functions.php';
include WP_CONTENT_DIR . '/plugins/ultimate-tinymce/includes/import_export.php';


//
//
// Let's fix the BBPress issue with tinymce set to "teeny"
/*
******************************************************
Used for BBPress.  Editor breaks in BBPress otherwise.
******************************************************
*/
function jwl_ult_bbpress_mceallow( $args = array() ) {
    $args['teeny'] = false;
    return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'jwl_ult_bbpress_mceallow' );


// Let's check for options which throw errors if not set... If they are not set, let's set them!
// Check if tinycolor (used for changing color of post/page editor) is empty.. if so, set to true
$options_mce_css = get_option('jwl_options_group3');
$options_empty_mce_css = isset($options_mce_css['jwl_tinycolor_css_field_id']['tinycolor']);
if (empty($options_empty_mce_css)) {	
	$opts_mce_css = get_option('jwl_options_group3');	
	$opts_mce_css['jwl_tinycolor_css_field_id']['tinycolor'] = 'Default';
	update_option('jwl_options_group3', $opts_mce_css);
}

//  Add settings link to plugins page menu
//  This can be duplicated to add multiple links
function jwl_add_ultimatetinymce_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
		if ($file == $this_plugin){
		$settings_link = '<a href="admin.php?page=ultimate-tinymce" title="Plugin Settings Page">'.__("Settings",'jwl-ultimate-tinymce').'</a>';
		$settings_link2 = '<a href="http://forum.joshlobe.com/member.php?action=register&referrer=1" title="Dedicated Ultimate Tinymce Free Support Forum">'.__("Support Forum",'jwl-ultimate-tinymce').'</a>';
		array_unshift($links, $settings_link, $settings_link2);
		}
	return $links;
}
add_filter('plugin_action_links', 'jwl_add_ultimatetinymce_settings_link', 10, 2 );

// Change the CSS for active plugin on admin plugins page
function jwl_admin_style() {
	global $pagenow;
	if ($pagenow == "plugins.php") {
		require('includes/style.php');
	}
}
add_action('admin_print_styles', 'jwl_admin_style');
$options = get_option('jwl_options_group4');
$jwl_pluginslist = isset($options['jwl_pluginslist_css']);
if ($jwl_pluginslist == "1"){
	remove_action('admin_print_styles', 'jwl_admin_style');
}

// Donate link on manage plugin page
function jwl_execphp_donate_link($links, $file) { 
	if ($file == plugin_basename(__FILE__)) { 
		$donate_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=A9E5VNRBMVBCS" target="_blank" title="Donate via Paypal">Donate</a>'; 
		$addons_link = '<a target="_blank" href="http://www.plugins.joshlobe.com" title="View Premium Ultimate Tinymce Addons">Premium Addons</a>';
		$support_link = '<a target="_blank" href="http://forum.joshlobe.com/member.php?action=register&referrer=1" class="jwl_support" title="Dedicated Ultimate Tinymce Free Support Forum"></a>';
		$fbook_link = '<a target="_blank" href="http://www.facebook.com/joshlobe" class="jwl_fbook" title="Connect with me on Facebook"></a>';
		$twitter_link = '<a target="_blank" href="http://twitter.com/#!/joshlobe" class="jwl_twitt" title="Follow me on twitter"></a>';
		$ultimate_pro = '<span style="margin-left:20px;"><a target="_blank" href="http://ultimatetinymcepro.com">Ultimate Tinymce PRO</a></span>';
			$options = get_option('jwl_options_group4');
			$jwl_pluginslinks = isset($options['jwl_pluginslist_css']);
			if ($jwl_pluginslinks == "0"){
				$links[] = $donate_link . ' | ' . $addons_link . ' | ' . $support_link . ' | ' . $fbook_link . ' | ' . $twitter_link . $ultimate_pro; 
			}
	} 
	return $links; 
} add_filter('plugin_row_meta', 'jwl_execphp_donate_link', 10, 2);

// Check WP Version and generate update notice if necessary
if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '3.3.1', '<') ) { // if less than ...
	?>
	<div class="error" style="margin-top:30px;">
	<p><?php _e('This plugin requires WordPress version 3.3.1 or newer. Please upgrade your WordPress installation or download an', 'jwl-ultimate-tinymce'); ?> <a href="http://wordpress.org/extend/plugins/ultimate-tinymce/developers/"><?php _e('older version of the plugin.', 'jwl-ultimate-tinymce'); ?></a></p>
	</div>
	<?php

	return;
}

// Functions for QR Code
$options = get_option('jwl_options_group4');
$jwl_qr_code = isset($options['jwl_qr_code']);
if ($jwl_qr_code == "1") {

	function jwl_qr_code( $content ) {
		if( is_single() ) {
			
			$options2 = get_option('jwl_options_group4');
	
			$content .= '<div class="jwl_qr_code" style="border:1px solid #ddd;margin-top:30px;"><div style="height:18px;border:1px solid #ddd;padding:5px;background:#'.$options2['jwl_qr_code_bg'].';color:#'.$options2['jwl_qr_code_text'].';" id="qr_header">';
			$content .= '<span style="font-weight:bold;font-size:18px;margin-left:10px;">QR Code - Take this post Mobile!</span>';
			$content .= '</div><div id="qr_main" style="padding:10px;background:#'.$options2['jwl_qr_code_bg_main'].';color:#'.$options2['jwl_qr_code_text'].';">';
			$content .= '<div style="float:left;margin-right:20px;width:20%;"><script type="text/javascript">var uri=window.location.href;document.write("<img src=\'http://api.qrserver.com/v1/create-qr-code/?data="+encodeURI(uri)+"&size=75x75\'/>");</script></div>';
			$content .= '<div style="float:left;width:75%;">'.$options2['jwl_qr_code_content'].'</div>';
			$content .= '<div style="clear:both;"></div>';
			$content .= '</div></div>';
		}
		return wpautop($content);
	}
	add_filter('the_content', 'jwl_qr_code');
}

$options2 = get_option('jwl_options_group4');
$jwl_qr_code_pages = isset($options2['jwl_qr_code_pages']); 
if ($jwl_qr_code_pages == "1") {

	function jwl_qr_code_pages( $content ) {
		if( is_page() ) {
			
			$options3 = get_option('jwl_options_group4');
	
			$content .= '<div class="jwl_qr_code" style="border:1px solid #ddd;margin-top:30px;"><div style="height:18px;border:1px solid #ddd;padding:5px;background:#'.$options3['jwl_qr_code_bg'].';color:#'.$options3['jwl_qr_code_text'].';" id="qr_header">';
			$content .= '<span style="font-weight:bold;font-size:18px;margin-left:10px;">QR Code - Take this post Mobile!</span>';
			$content .= '</div><div id="qr_main" style="padding:10px;background:#'.$options3['jwl_qr_code_bg_main'].';color:#'.$options3['jwl_qr_code_text'].';">';
			$content .= '<div style="float:left;margin-right:20px;width:20%;"><script type="text/javascript">var uri=window.location.href;document.write("<img src=\'http://api.qrserver.com/v1/create-qr-code/?data="+encodeURI(uri)+"&size=75x75\'/>");</script></div>';
			$content .= '<div style="float:left;width:75%;">'.$options3['jwl_qr_code_content'].'</div>';
			$content .= '<div style="clear:both;"></div>';
			$content .= '</div></div>';
		}
		return $content;
	}
	add_filter('the_content', 'jwl_qr_code_pages');
}

/*
 * Here we are generating the admin options page.
 * This will allow us to include all scripts and code to mimic the main dashboard admin page.
*/
// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

define('JWL_ADMIN_PAGE_NAME', 'ultimate-tinymce');

//class that reperesents the plugins complete admin options page.
class jwl_metabox_admin {

		//constructor of class, PHP4 compatible construction for backward compatibility
		function jwl_metabox_admin() {
			//register callback for admin menu  setup
			add_action('admin_menu', array(&$this, 'jwl_on_admin_menu')); 
			//register the callback been used if options of page been submitted and needs to be processed
			add_action('admin_post_save_ultimate-tinymce-general', array(&$this, 'jwl_on_save_changes'));
		}
		
		
		//extend the admin menu
		function jwl_on_admin_menu() {
			//add our own option page, you can also add it to different sections or use your own one
			$this->pagehook = add_options_page('Ultimate TinyMCE Plugin Page',  __('Ultimate TinyMCE','jwl-ultimate-tinymce'), 'manage_options', JWL_ADMIN_PAGE_NAME, array(&$this, 'jwl_options_page'));
			//register  callback gets call prior your own page gets rendered
			
			add_action('load-'.$this->pagehook, array(&$this, 'jwl_on_load_page'));
			add_action("load-{$this->pagehook}",array(&$this,'jwl_help_screen'));
			add_action('admin_print_styles-'.$this->pagehook, array(&$this, 'jwl_admin_register_head_styles'));
			add_action('admin_print_scripts-'.$this->pagehook, array(&$this, 'jwl_admin_register_head_scripts'));

		}
		
		// Register (and Enqueue) our styles only for admin settings page
		function jwl_admin_register_head_styles() {
    		wp_register_style('jwl-admin-panel-css', plugins_url('css/admin_panel.css', __FILE__), array(), '1.0.0', 'all');  // CSS for admin panel presentation
    		wp_enqueue_style('jwl-admin-panel-css');
			echo "<link href='http://fonts.googleapis.com/css?family=Unlock' rel='stylesheet' type='text/css'>"; // Added for title font
		}
		// Register our scripts only for admin settings page
		function jwl_admin_register_head_scripts() {			
			wp_register_script('jwl-color-picker', plugin_dir_url( __FILE__ ) . 'js/jscolor/jscolor.js', array(), '1.0.0', 'all' );  // Javascript color picker
			wp_enqueue_script('jwl-color-picker');
			
			wp_register_script('jwl-popup-help', plugin_dir_url( __FILE__ ) . 'js/pop-up.js', array(), '1.0.0', 'all' ); // Popup help windows
			wp_enqueue_script('jwl-popup-help');
			
			wp_register_script('jwl-admin-panel-js', plugin_dir_url( __FILE__ ) . 'js/admin_panel.js', array(), '1.0.0', 'all' ); // All admin panel javascript
			wp_enqueue_script('jwl-admin-panel-js');
			
		}
		// Creates the help tab at the top right of the admin settings page
		function jwl_help_screen() {
			/** 
			 * Create the WP_Screen object against your admin page handle
			 * This ensures we're working with the right admin page
			 */
			$this->admin_screen = WP_Screen::get($this->pagehook);
			// Content specified inline
			$this->admin_screen->add_help_tab( array( 'title' => __('Help Documentation','jwl-ultimate-tinymce'), 'id' => 'help_tab', 'content' => '<div class="help_wrapper"><p>'.__('<ul><li class="help_tab_list_image">The best resource for expedited help is my <a target="_blank" href="http://www.forum.joshlobe.com/">Support Forum</a>.</li><li class="help_tab_list_image">You can also visit the <a target="_blank" href="http://www.plugins.joshlobe.com/">Plugin Page</a> to read user comments.</ul>','jwl-ultimate-tinymce').'</p></div>', 'callback' => false ));
			$this->admin_screen->add_help_tab( array( 'title' => __('Settings Page Tips','jwl-ultimate-tinymce'), 'id' => 'help_tab2', 'content' => '<div class="help_wrapper"><p>'.__('Here are some important items to remember regarding the new settings page.<br /><ul><li class="help_tab_list_image">Each option has a dedicated help icon.  Clicking the help icon (blue question mark) for a specific option will open a new window with a unique help file.</li><li class="help_tab_list_image">Boxes can be opened/closed and sorted by clicking and dragging the box headers.  Boxes can also be enabled/disabled via the "Screen Options" tab in the upper-right corner.</li><li class="help_tab_list_image">Set your screen layout to two columns (via Screen Options) for best results.</li><li class="help_tab_list_image">The "Row Selection" button allows you to choose which row of the visual editor the button will appear.</ul>','jwl-ultimate-tinymce').'</p></div>', 'callback' => false ));
			/**
			 * Content generated by callback
			 * The callback fires when tab is rendered - args: WP_Screen object, current tab
			 */
			//$this->admin_screen->add_help_tab(
				//array( 'title' => 'Info on this Page', 'id' => 'page_info', 'content' => '', 'callback' => create_function('','echo "<p>This is my generated content.</p>";' )));
			$this->admin_screen->set_help_sidebar( '<p>'.__('Ultimate Tinymce Help<br /><br /><a target="_blank" href="http://www.forum.joshlobe.com/">Support Forum</a>','jwl-ultimate-tinymce').'</p>' );
			//$this->admin_screen->add_option( 'per_page', array( 'label' => 'Entries per page', 'default' => 20, 'option' => 'edit_per_page' ));
			//$this->admin_screen->add_option( 'layout_columns', array( 'default' => 3, 'max' => 5 ));
			// This option will NOT show up
			//$this->admin_screen->add_option( 'invisible_option', array( 'label'	=> 'I am a custom option', 'default' => 'wow', 'option' => 'my_option_id' ));
			/**
			 * But old-style metaboxes still work for creating custom checkboxes in the option panel
			 * This is a little hack-y, but it works
			 */
			//add_meta_box( 'jwl_help_meta_id', 'Help Metabox', array(&$this,'create_my_metabox'), $this->admin_page );
		}
		
		//will be executed if wordpress core detects this page has to be rendered
		function jwl_on_load_page() {
			//ensure, that the needed javascripts been loaded to allow drag/drop, expand/collapse and hide/show of boxes
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');
		
			//add metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
			// Can use 'normal', 'side', or 'additional' when defining metabox positions
			
			add_meta_box('jwl_metabox1', __('Buttons Group 1','jwl-ultimate-tinymce'), array(&$this, 'jwl_buttons_group_1'), $this->pagehook, 'normal', 'core');
			add_meta_box('jwl_metabox2', __('Buttons Group 2','jwl-ultimate-tinymce'), array(&$this, 'jwl_buttons_group_2'), $this->pagehook, 'normal', 'core');
			add_meta_box('jwl_metabox9', __('Other Plugins\' Buttons','jwl-ultimate-tinymce'), array(&$this, 'jwl_buttons_group_9'), $this->pagehook, 'normal', 'core');
			add_meta_box('jwl_metabox4', __('Miscellaneous Features','jwl-ultimate-tinymce'), array(&$this, 'jwl_buttons_group_3'), $this->pagehook, 'normal', 'core');
			add_meta_box('jwl_metabox5', __('Admin Options','jwl-ultimate-tinymce'), array(&$this, 'jwl_buttons_group_4'), $this->pagehook, 'normal', 'core');
			add_meta_box('jwl_metabox8', __('Content Editor (Tinymce) Over-rides','jwl-ultimate-tinymce'), array(&$this, 'jwl_buttons_group_8'), $this->pagehook, 'normal', 'core');
		}
		
		//executed to show the plugins complete admin page
		function jwl_options_page() {
			//we need the global screen column value to beable to have a sidebar in WordPress 2.8
			//global $screen_layout_columns;
			//add a 3rd content box now for demonstration purpose, boxes added at start of page rendering can't be switched on/off, 
			//may be needed to ensure that a special box is always available
			//add_meta_box('postbox_addons', 'Plugin Addons', array(&$this, 'postbox_addons'), $this->pagehook, 'side', 'core');
			//define some data can be given to each metabox during rendering
			$data = array('My Data 1', 'My Data 2', 'Available Data 1');
			?>
            
            
            
            
<div id="ultimate-tinymce-general" class="wrap">

	<?php //screen_icon('options-general'); ?>
    <span style="margin-top:10px;">
    	<img src="<?php echo plugins_url('img/settings.png', __FILE__ ) ?>" title="Ultimate Tinymce Settings Page" style="margin-top:10px;margin-bottom:-10px;"/>
        <span style="margin-left:20px;color:#FAC46D;font-size:32px;font-family:'Unlock', cursive;"><?php _e('Ultimate Tinymce ','jwl-ultimate-tinymce'); ?>
        </span>
        <span style="color:#5F95EF;font-size:22px;font-family:'Unlock', cursive;"><?php _e('Admin Settings Page','jwl-ultimate-tinymce'); ?>
        </span>
    </span>
    
    <form action="admin-post.php" method="post">
    <?php wp_nonce_field('ultimate-tinymce-general'); ?>
    <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
    <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
    <input type="hidden" name="action" value="save_ultimate-tinymce_general" />
    </form>
                          
    <div id="container"> 
     
        <ul class="menu">  
            <li id="news" class="active" style="font-size:16px;"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/addon.png" style="margin-right:3px;" title="Addons" /><?php _e('Plugin Addons','jwl-ultimate-tinymce'); ?></li>
            <li id="tutorials" style="font-size:16px;"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/donate2.png" style="margin-right:3px;" title="Donate" /><?php _e('Donations','jwl-ultimate-tinymce'); ?></li>  
            <li id="spread" style="font-size:16px;"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/spread.png" style="margin-right:3px;" title="Spread the Word" /><?php _e('Spread the Word','jwl-ultimate-tinymce'); ?></li> 
            <li id="gettingstarted" style="font-size:16px;"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/started.png" style="margin-right:3px;" title="Getting Started" /><?php _e('Getting Started','jwl-ultimate-tinymce'); ?></li>
            <li id="tips" style="font-size:16px;"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/tips.png" style="margin-right:3px;" title="Admin Tips" /><?php _e('Admin Tips','jwl-ultimate-tinymce'); ?></li>
            <li id="defaults" style="font-size:16px;"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/default.png" style="margin-right:3px;" title="Load Defaults" /><?php _e('Default Settings','jwl-ultimate-tinymce'); ?></li>
            <li id="links" style="font-size:16px;"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/uninstall.png" style="margin-right:3px;" title="Uninstall" /><?php _e('Uninstall Plugin','jwl-ultimate-tinymce'); ?></li>  
        </ul>  
        <span class="clear"></span> 
         
        <div class="content news"> 
        
            <div class="main_help_wrapper">
            	<span class="content_title"><?php _e('Plugin Addons:','jwl-ultimate-tinymce'); ?>
                </span><br /><br />
                
                <span style="margin-left:10px;">
					<?php _e('These addons provide additional features for Ultimate TinyMCE.  Click the title to view the download page.','jwl-ultimate-tinymce'); ?>
                </span><br />
                
                
                
                <div id="clickme2" class="content_wrapper_addons">
					<?php _e('<a target="_blank" title="Easily Integrate Google Webfonts into your Website." href="http://www.plugins.joshlobe.com/ultimate-tinymce-google-webfonts/"><span style="font-family:\'Unlock\', cursive;">Google Webfonts</span></a>','jwl-ultimate-tinymce'); ?> 
                    <span class="span_hover"><?php _e('(Toggle)','jwl-ultimate-tinymce'); ?>
                    </span>
                    
                    <div id="me2" style="display:none;margin-top:10px;"><?php
                    if (is_plugin_active('ultimate_tinymce_google_webfonts_addon/main.php')) {
                    _e('<span style="color:green;">Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/check.png" class="activation_icons" title="This addon has been installed and activated successfully." /> <?php
                    } else {
                    _e('<span style="color:red;">Not Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/warning.png" class="activation_icons" title="This addon has NOT been activated." /><br /><br /><span class="plugin_addons"> <?php _e('Choose any combination of Google Webfonts, and add them to the font dropdown selector.<br /><br />Fonts are rendered on both the editor screen, and to all front-end viewers.','jwl-ultimate-tinymce'); ?> <br /><br /><center><img style="border:1px solid #666" src="<?php echo plugin_dir_url( __FILE__ ) ?>img/admin_webfonts.png" title="Ultimate Tinymce Google Webfonts" /></center></span> <?php
                    } 
					?>
                    </div> <!-- End Div #me2 -->
                </div> <!-- End Div #clickme2 -->
                
                
                
                <div id="clickme3" class="content_wrapper_addons">
					<?php _e('<a target="_blank" title="Easily add custom styles to your content." href="http://www.plugins.joshlobe.com/ultimate-tinymce-custom-styles/"><span style="font-family:\'Unlock\', cursive;">Custom Styles</span></a>','jwl-ultimate-tinymce'); ?> 
                    <span class="span_hover"><?php _e('(Toggle)','jwl-ultimate-tinymce'); ?>
                    </span>
                    
                    <div id="me3" style="display:none;margin-top:10px;"><?php
                    if (is_plugin_active('ultimate_tinymce_custom_styles_addon/main.php')) {
                    _e('<span style="color:green;">Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/check.png" class="activation_icons" title="This addon has been installed and activated successfully." /> <?php
                    } else {
                    _e('<span style="color:red;">Not Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/warning.png" class="activation_icons" title="This addon has NOT been activated." /><br /><br /><span class="plugin_addons"> <?php _e('Define unlimited custom styles, and add them to the styleselect dropdown list.<br /><br />Styles are rendered in both the editor screen and the front end of the website.','jwl-ultimate-tinymce'); ?> <br /><br /><center><img style="border:1px solid #666" src="<?php echo plugin_dir_url( __FILE__ ) ?>img/admin_styles.png" title="Ultimate Tinymce Custom Styles" /></center></span> <?php
                    }
                    ?>    
                	</div> <!-- End Div #me3 -->
                </div> <!-- End Div #clickme3 -->
                
                
                
                <div id="clickme4" class="content_wrapper_addons">
				<?php _e('<a target="_blank" title="Add a list of over 80 predefined styles to your editor." href="http://www.plugins.joshlobe.com/predefined-custom-styles/"><span style="font-family:\'Unlock\', cursive;">Pre-Defined Styles</span></a>','jwl-ultimate-tinymce'); ?> 
                <span class="span_hover"><?php _e('(Toggle)','jwl-ultimate-tinymce'); ?>
                </span>
                
                    <div id="me4" style="display:none;margin-top:10px;"><?php
                    if (is_plugin_active('ultimate_tinymce_predefined_styles/main.php')) {
                    _e('<span style="color:green;">Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/check.png" class="activation_icons" title="This addon has been installed and activated successfully." /> <?php
                    } else {
                    _e('<span style="color:red;">Not Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/warning.png" class="activation_icons" title="This addon has NOT been activated." /><br /><br /><span class="plugin_addons"> <?php _e('A collection of my custom styles.  No need to create your own.<br /><br />Install this plugin and have instant access to over 80 custom styles (and growing).','jwl-ultimate-tinymce'); ?> </span> <?php
                    }
                    ?>    
                    </div> <!-- End Div #me4 -->
                </div> <!-- End Div #clickme4 -->
                
                
                
                <div id="clickme5" class="content_wrapper_addons">
				<?php _e('<a target="_blank" title="Apply six unique color settings to your admin panel." href="http://www.plugins.joshlobe.com/wp-admin-colors/"><span style="font-family:\'Unlock\', cursive;">WP Admin Colors</span></a>','jwl-ultimate-tinymce'); ?> 
                <span class="span_hover"><?php _e('(Toggle)','jwl-ultimate-tinymce'); ?>
                </span>
                
                    <div id="me5" style="display:none;margin-top:10px;"><?php
                    if (is_plugin_active('wp-admin-colors/main.php')) {
                    _e('<span style="color:green;">Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/check.png" class="activation_icons" title="This addon has been installed and activated successfully." /> <?php
                    } else {
                    _e('<span style="color:red;">Not Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/warning.png" class="activation_icons" title="This addon has NOT been activated." /><br /><br /><span class="plugin_addons"> <?php _e('Here is a compliment to the color selection for the tinymce editor. This addon will provide a choice of six unique stylesheets to apply to the entire admin panel dashboard.','jwl-ultimate-tinymce'); ?> </span> <?php
                    }
                    ?>    
                    </div> <!-- End Div #me5 -->
                </div> <!-- End Div #clickme5 -->
                
                
                
                <br />
                <div style="clear:both"></div>
                <br />
                
                
                <div id="clickme" class="content_wrapper_addons" style="margin-top:-10px;">
				<?php _e('<a target="_blank" title="Take powerful control over the visual tinymce editor." href="http://www.plugins.joshlobe.com/ultimate-tinymce-advanced-configuration/"><span style="font-family:\'Unlock\', cursive;">Advanced Configuration</span></a>','jwl-ultimate-tinymce'); ?> 
                <span class="span_hover"><?php _e('(Toggle)','jwl-ultimate-tinymce'); ?>
                </span>
                
                    <div id="me" style="display:none;margin-top:10px;"><?php
                    if (is_plugin_active('ultimate-tinymce-advanced-configuration/main.php')) {
                    _e('<span style="color:green;">Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/check.png" class="activation_icons" title="This addon has been installed and activated successfully." /> <?php
                    } else {
                    _e('<span style="color:red;">Not Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/warning.png" class="activation_icons" title="This addon has NOT been activated." /><br /><br /><span class="plugin_addons"> <?php _e('Take advanced control over your visual tinymce editor.  Control settings such as button placement, font sizes, date and time formats, and more.','jwl-ultimate-tinymce'); ?> </span> <?php
                    }
                    ?>    
                    </div> <!-- End Div #me -->
                </div> <!-- End Div #clickme -->
                
                
                
                
                <div id="clickme6" class="content_wrapper_addons" style="margin-top:-10px;">
				<?php _e('<a target="_blank" title="Take powerful control over the visual tinymce editor." href="http://www.plugins.joshlobe.com/ultimate-tinymce-classes-and-ids/"><span style="font-family:\'Unlock\', cursive;">Classes and IDs</span></a>','jwl-ultimate-tinymce'); ?> 
                <span class="span_hover"><?php _e('(Toggle)','jwl-ultimate-tinymce'); ?>
                </span>
                
                    <div id="me6" style="display:none;margin-top:10px;"><?php
                    if (is_plugin_active('ultimate-tinymce-classes-ids/main.php')) {
                    _e('<span style="color:green;">Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/check.png" class="activation_icons" title="This addon has been installed and activated successfully." /> <?php
                    } else {
                    _e('<span style="color:red;">Not Activated</span>','jwl-ultimate-tinymce');
                    ?> <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/warning.png" class="activation_icons" title="This addon has NOT been activated." /><br /><br /><span class="plugin_addons"> <?php _e('Ultimate Tinymce Classes and IDs is a plugin for WordPress TinyMCE which enables the usage of CSS classes and CSS ids on any HTML element within TinyMCE.','jwl-ultimate-tinymce'); ?><br /><br /><?php _e('Together with an external CSS file, Ultimate Tinymce Classes and IDs bridges the (visual) gap between the content entered through TinyMCE and the actual output.','jwl-ultimate-tinymce'); ?> </span> <?php
                    }
                    ?>    
                    </div> <!-- End Div #me6 -->
                </div> <!-- End Div #clickme6 -->
                
                
                
                <div id="clickme7" class="content_wrapper_addons" style="margin-top:-10px;">
				<?php _e('<a target="_blank" title="Ultimate Tinymce PRO" href="http://ultimatetinymcepro.com"><span style="font-family:\'Unlock\', cursive;">Ultimate Tinymce PRO</span></a>','jwl-ultimate-tinymce'); ?> 
                <span class="span_hover"><?php _e('(Toggle)','jwl-ultimate-tinymce'); ?>
                </span>
                <span style="color:green;margin-left:10px;"><?php _e('New!','jwl-ultimate-tinymce'); ?>
                </span>
                
                    <div id="me7" style="display:none;margin-top:10px;">
					<?php _e('Are you using the most advanced WP visual editor available? Get it today!','jwl-ultimate-tinymce');
                    ?>
                    <br /><br />
                    <center>
                    	<a target="_blank" href="http://ultimatetinymcepro.com">
                        	<img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/pro.gif" />
                           </a>
                    </center>
                    </div> <!-- End Div #me7 -->
                </div> <!-- End Div #clickme7 -->
                
                <br />
                <div style="clear:both"></div>
                    
             </div> <!-- End Div .main_help_wrapper -->
        </div> <!-- End Div .content news -->
        
        
        
        <div class="content tutorials">
        
        	<div class="main_help_wrapper">
            <span class="content_title">
			<?php _e('Donations:','jwl-ultimate-tinymce'); ?></span><br /><br />
            
            	<div class="content_wrapper_tips">
                <span class="content_wrapper_title">
					<?php _e('Support the Developer','jwl-ultimate-tinymce'); ?>
                </span><br />
				<?php _e('Developing this awesome plugin took a lot of effort and time; months and months of continuous voluntary unpaid work.','jwl-ultimate-tinymce'); ?>
                <br /><br />
                <center>
                     <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                     <input type="hidden" name="cmd" value="_s-xclick">
                     <input type="hidden" name="hosted_button_id" value="A9E5VNRBMVBCS">
                     <input type="image" src="<?php echo plugin_dir_url( __FILE__ ) ?>img/donate.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                     <img alt="PayPal - The safer, easier way to pay online!" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                     </form>
                </center><br />
                
                <?php _e('If you like this plugin or if you are using it for commercial websites, please consider a donation to the author to help support future updates and development.','jwl-ultimate-tinymce'); ?>
                
            	</div> <!-- End Div .content_wrapper_tips -->
        
                <div class="content_wrapper_tips">
                <?php _e('<span class="content_wrapper_title">Main uses of Donations</span><ul class="help_tab_list_image"><li>Web Hosting Fees</li><li>Cable Internet Fees</li><li>Time/Value Reimbursement</li><li>Motivation for Continuous Improvements</li><li>Sunday Father-Daughter Day</li></ul>','jwl-ultimate-tinymce'); ?>
                </div> <!-- End Div .content_wrapper_tips -->
                
                <div class="content_wrapper_tips">
                <span class="content_wrapper_title"><?php _e('Donate Securely via Paypal','jwl-ultimate-tinymce'); ?></span><br />
                	<center><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                     <input type="hidden" name="cmd" value="_s-xclick">
                     <input type="hidden" name="hosted_button_id" value="A9E5VNRBMVBCS">
                     <input type="image" src="<?php echo plugin_dir_url( __FILE__ ) ?>img/paypal.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="margin-top:30px;">
                     <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                     </form>
                     </center>
                </div> <!-- End Div .content_wrapper_tips -->
        	</div> <!-- End Div .main_help_wrapper -->
        </div> <!-- End Div .content tutorials -->
        
        
        
        <div class="content spread">
        
        	<div class="main_help_wrapper">
            <span class="content_title">
			<?php _e('Spread the Word:','jwl-ultimate-tinymce'); ?></span><br /><br />
            
            	<div class="content_wrapper_tips">
                <span class="content_wrapper_title">
                <?php _e('Blog about this Plugin','jwl-ultimate-tinymce'); ?>
                </span><br />
                
                	<div class="blog_image">
                    <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/blog.png" />
                    </div> <!-- End Div .blog_image -->
                    
                    <div style="float:left;width:67%;">
                <?php _e('<ul class="help_tab_list_image"><li>Do you like this plugin, and use it regularly on your site?</li><li>Why not write a brief article recommending it to your readers and other wordpress blogger buddies?</li><li>Include a link to the plugin download page to make it easy for your readers to access.</li></ul>','jwl-ultimate-tinymce'); ?>
                	</div> <!-- End Div -->
                </div> <!-- End Div .content_wrapper_tips -->
                
                <div class="content_wrapper_tips">
                <span class="content_wrapper_title">
                <?php _e('Vote and Click Works','jwl-ultimate-tinymce'); ?>
                </span><br />
                	<div class="vote_image">
                    <img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/works.png" />
                    </div> <!-- End Div .vote_image -->
                    <div style="float:left;width:60%;">
                <?php _e('Please take a few moments to visit the plugin download page to vote and click "Works".  You must have an account on wordpress to rate and vote. Signing up is quick and easy.<br /><br />Also, each time a new plugin update is available, it resets the "Works" count.  So, please do this each time you update the plugin.<br /><br /><a target="_blank" href="http://wordpress.org/extend/plugins/ultimate-tinymce/">Ultimate Tinymce Wordpress Page</a>','jwl-ultimate-tinymce'); ?>
                	</div> <!-- End Div -->
                </div> <!-- End Div .content_wrapper_tips -->
                
                <div class="content_wrapper_tips">
                <span class="content_wrapper_title">
                <?php _e('Twitter & Facebook','jwl-ultimate-tinymce'); ?>
                </span><br />
                	<div style="float:left;width:100%;margin-top:20px;">
                    <center>
                    <a target="_blank" href="https://twitter.com/"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/twitter.png" /></a><br />
                    <a target="_blank" href="https://www.facebook.com/"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/facebook.png" /></a>
                    </center>
                    </div> <!-- End Div -->
                </div> <!-- End Div .content_wrapper_tips -->
            </div> <!-- End Div .main_help_wrapper -->
        </div> <!-- End Div .content spread -->
        
        
        
        <div class="content gettingstarted">
        
        	<div class="main_help_wrapper">
            <span class="content_title">
			<?php _e('Getting Started:','jwl-ultimate-tinymce'); ?></span><br /><br />
            
            	<div class="content_wrapper_tips" style="width:50%;">
                <span class="content_wrapper_title">
                <?php _e('Setting up the Admin Settings Page','jwl-ultimate-tinymce'); ?>
                </span><br />
                 	<iframe width="420" height="315" src="http://www.youtube.com/embed/wymClsVjkFY" frameborder="0" allowfullscreen></iframe>
                </div> <!-- End Div .content_wrapper_tips -->
            </div> <!-- End Div .main_help_wrapper -->
        </div> <!-- End Div .content gettingstarted -->
        
        
        <div class="content tips"> 
        
        	<div class="main_help_wrapper">
            <span class="content_title"><?php _e('Tips and Tricks for the Admin Panel', 'jwl-ultimate-tinymce'); ?></span><br /><br />
            
            	<div class="content_wrapper_tips">
            	<?php _e('<span class="content_wrapper_title">Screen Options:</span><ul class="help_tab_list_image"><li>Click the "Screen Options" tab in the upper-right corner to enable further customization.</li><li>Set the Screen Columns to "2" for best results.</li><li>Decide which Meta-Boxes to show or hide.</li><li>Selections are saved in the database.</li></ul>','jwl-ultimate-tinymce'); ?>
                </div> <!-- End Div .content_wrapper_tips -->
                
                <div class="content_wrapper_tips">
            	<?php _e('<span class="content_wrapper_title">Meta Boxes:</span><ul class="help_tab_list_image"><li>Each Meta-Box can be clicked to collapse/expand the contents of the box.</li><li>Boxes can be sorted by clicking and dragging the title area to a new location.</li><li>Open/Closed status and sorting arrangement are saved in the database.  So each time the page is visited; the last chosen layout remains.</li></ul>','jwl-ultimate-tinymce'); ?>
                </div> <!-- End Div .content_wrapper_tips -->
                
                <div class="content_wrapper_tips">
            	<?php _e('<span class="content_wrapper_title">Button Row Selection:</span><ul class="help_tab_list_image"><li>Each button from this plugin can be assigned to one of the four rows of the editor.</li><li>For suggested best results, set all buttons used in "Group One Buttons" to Row 3 and set all buttons used in "Group Two Buttons" to Row 4.  <em>This is only recommended, and not mandatory.</em></li><li>If the buttons scroll off the editor screen, come back here and select a different row for those buttons.</li></ul>','jwl-ultimate-tinymce'); ?>
                </div> <!-- End Div .content_wrapper_tips -->
            </div> <!-- End Div .main_help_wrapper -->
        </div> <!-- End Div .content tips -->
        
        
        <div class="content defaults"> 
        	<div class="main_help_wrapper"><span class="content_title"><?php _e('Load developers suggested settings.', 'jwl-ultimate-tinymce'); ?></span><br /><br />
                <div class="content_wrapper_tips" style="width:60%;">
                <?php jwl_ultimate_tinymce_load_defaults(); ?>
                </div> <!-- End Div .content_wrapper_tips -->
        	</div> <!-- End Div .main_help_wrapper -->
        </div> <!-- End Div .content defaults -->
        
        
        
        <div class="content links"> 
        	<div class="main_help_wrapper">
            <span class="content_title"><?php _e('Uninstall Plugin & Delete Database Entries:', 'jwl-ultimate-tinymce'); ?></span><br /><br />
            	<div class="content_wrapper_tips">
            	<?php jwl_ultimate_tinymce_form_uninstall(); ?>
                </div> <!-- End Div .content_wrapper_tips -->
                <div class="content_wrapper_tips" style="height:318px;">
                <center><img src="<?php echo plugin_dir_url( __FILE__ ) ?>img/uninstall1.png" style="margin-top:120px;" /></center>
                </div> <!-- End Div .content_wrapper_tips -->
            </div> <!-- End Div .main_help_wrapper -->
        </div> <!-- End Div .content links -->
        
    </div> <!-- End Div #container -->
    
    
    
          	
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">
    
        <div id="side-info-column" class="inner-sidebar">                        
            
            <div class="jwl_support_sidebar">
            	<h3><?php _e('Need Support?', 'jwl-ultimate-tinymce'); ?></h3>
                <p><a target="_blank" href="http://forum.joshlobe.com/member.php?action=register&referrer=1"><?php _e('Dedicated Support Forum', 'jwl-ultimate-tinymce'); ?></a></p>
                <p><a target="_blank" href="http://www.plugins.joshlobe.com/contact/"><?php _e('Contact Me', 'jwl-ultimate-tinymce'); ?></a></p>
            </div> <!-- End Div .jwl_support_sidebar -->
            
            <div class="jwl_follow_sidebar">
            	<h3><?php _e('Like to Follow?', 'jwl-ultimate-tinymce'); ?></h3>
                <p><a target="_blank" href="http://www.facebook.com/joshlobe"><?php _e('Facebook', 'jwl-ultimate-tinymce'); ?><img src="<?php echo plugin_dir_url( __FILE__ ) ?>css/img/facebook_sidebar.png" style="margin-bottom:-13px;margin-left:5px;width:30px;height:30px;" /></a></p>
                <p><a target="_blank" href="http://twitter.com/#!/joshlobe"><?php _e('Twitter', 'jwl-ultimate-tinymce'); ?><img src="<?php echo plugin_dir_url( __FILE__ ) ?>css/img/twitter_sidebar.png" style="margin-bottom:-13px;margin-left:23px;" /></a></p>
                <p><a target="_blank" href="http://www.youtube.com/user/kygirlhighlands"><?php _e('YouTube', 'jwl-ultimate-tinymce'); ?><img src="<?php echo plugin_dir_url( __FILE__ ) ?>css/img/youtube_sidebar.png" style="margin-bottom:-13px;margin-left:10px;" /></a></p>
            </div> <!-- End Div .jwl_follow_sidebar -->
            
            <div class="jwl_rate_sidebar">
            	<h3><?php _e('New Rating System!', 'jwl-ultimate-tinymce'); ?></h3>
                <p><a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/ultimate-tinymce"><img src="<?php echo plugin_dir_url( __FILE__ ) ?>css/img/fivestars.png" style="margin-left:-3px;margin-bottom:-10px;" /></a></p>
                <p><?php _e('<a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/ultimate-tinymce"><strong>Ultimate Tinymce Ratings Page</strong></a><br /><br />Wordpress has implemented a new plugin ratings system.  Comments are now required to "justify" a rating.<br /><br />Please visit the link above and leave a rating and a comment to help others in the future.', 'jwl-ultimate-tinymce'); ?></p>
            </div> <!-- End Div .jwl_rate_sidebar -->
            
            <div class="jwl_signup_sidebar">
            	<h3><?php _e('Want to Signup?', 'jwl-ultimate-tinymce'); ?></h3>
                <form method="post" action="http://ymlp.com/subscribe.php?id=gjwmeubgmgb" target="signup_popup" onsubmit="window.open( 'http://ymlp.com/subscribe.php?id=gjwmeubgmgb','signup_popup','scrollbars=yes,width=600,height=450'); return true;">
                	<?php $jwl_admin_email = get_option('admin_email'); $jwl_blog_title = get_bloginfo('name'); ?>
                    <br /><br />
                    <table cellpadding="5" cellspacing="0" align="center" border="0">
                    <tbody>
                    <tr>
                    <td colspan="2"><span style="font-size:14px;font-weight:bold;"><?php _e('Subscribe to the Ultimate Tinymce Newsletter!', 'jwl-ultimate-tinymce'); ?></span></td>
                    </tr>
                    <tr>
                    <td valign="top"><span style="font-family: &quot;verdana&quot;, &quot;geneva&quot;; font-size: 10pt;"><?php _e('Name:', 'jwl-ultimate-tinymce'); ?></span></td>
                    <td valign="top"><input value="<?php echo $jwl_blog_title; ?>" size="30" name="YMP1" type="text" /></td>
                    </tr>
                    <tr>
                    <td valign="top"><span style="font-family: &quot;verdana&quot;, &quot;geneva&quot;; font-size: 10pt;"><?php _e('Email:', 'jwl-ultimate-tinymce'); ?></span></td>
                    <td valign="top"><input value="<?php echo $jwl_admin_email; ?>" size="30" name="YMP0" type="text" /></td>
                    </tr>
                    <tr>
                    <td colspan="2"><input checked="checked" value="subscribe" name="action" type="radio" /> <span style="font-family: &quot;verdana&quot;, &quot;geneva&quot;; font-size: 10pt;"><?php _e('Subscribe', 'jwl-ultimate-tinymce'); ?></span><input style="margin-left:20px;" value="unsubscribe" name="action" type="radio" /> <span style="font-family: &quot;verdana&quot;, &quot;geneva&quot;; font-size: 10pt;"><?php _e('Unsubscribe', 'jwl-ultimate-tinymce'); ?></span></td>
                    </tr>
                    <tr>
                    <td colspan="2"><input class="button-primary" value="Submit" type="submit" />&nbsp;</td>
                    </tr>
                    </tbody>
                    </table>
                </form>
                <p><?php _e('Receive news about new features, links to tutorials and videos, and other "first-response" emails regarding this plugin.', 'jwl-ultimate-tinymce'); ?></p>
            </div> <!-- End Div .jwl_signup_sidebar -->
            
            <div class="jwl_pro_sidebar">
            	<h3><?php _e('Enjoy PRO Features?', 'jwl-ultimate-tinymce'); ?></h3>
                <p><a target="_blank" href="http://ultimatetinymcepro.com"><?php _e('Ultimate Tinymce PRO', 'jwl-ultimate-tinymce'); ?></a></p>
            </div> <!-- End Div .jwl_pro_sidebar -->
            
        </div> <!-- End Div #side-info-column -->
        
        
        <div id="post-body" class="has-sidebar">
        
            <div id="post-body-content" class="has-sidebar-content">
            
            <?php
            global $current_user ;
		    $user_id = $current_user->ID;
			
			?><strong><?php _e('Quick Navigation:', 'jwl-ultimate-tinymce'); ?></strong><?php
			?><br />
            <a href="#buttons1"><?php _e('Buttons Group 1', 'jwl-ultimate-tinymce'); ?></a> | 
            <a href="#buttons2"><?php _e('Buttons Group 2', 'jwl-ultimate-tinymce'); ?></a> | 
            <a href="#buttonsother"><?php _e('Other Buttons', 'jwl-ultimate-tinymce'); ?></a> | 
            <a href="#misc"><?php _e('Misc. Features', 'jwl-ultimate-tinymce'); ?></a> | 
            <a href="#adminopts"><?php _e('Admin Options', 'jwl-ultimate-tinymce'); ?></a> | 
            <a href="#override"><?php _e('Editor Over-rides', 'jwl-ultimate-tinymce'); ?></a><br /><br />
			<?php
                do_meta_boxes($this->pagehook, 'normal', $data);
                //do_meta_boxes($this->pagehook, 'additional', $data); 
			?>
            </div> <!-- End Div #post-body-content -->
        </div> <!-- End Div #post-body -->
        <br class="clear"/>		
        
   </div> <!-- End Div #poststuff -->	
   <a href="#" class="scrollToTop"><?php _e('Scroll To Top', 'jwl-ultimate-tinymce'); ?></a>

</div> <!-- End Div #ultimate-tinymce-general -->

<script type="text/javascript" language="javascript">
//<![CDATA[
jQuery(document).ready( 
	function($) { 
		$(".if-js-closed").removeClass("if-js-closed").addClass("closed"); 
		postboxes.add_postbox_toggles("<?php echo $this->pagehook; ?>"); 
	}
);
//]]>
</script>

<?php
		}
		
		// Executed if the post arrives initiated by pressing the submit button of form
		function jwl_on_save_changes() {
			//user permission check
			if ( !current_user_can('manage_options') )
				wp_die( __('Cheatin&#8217; uh?') );			
			//cross check the given referer
			check_admin_referer('ultimate-tinymce-general');
		
			//process here your on $_POST validation and / or option saving
		
			//lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
			wp_redirect($_POST['_wp_http_referer']);		
		}
		
		// Below you will find for each registered metabox the callback method, that produces the content inside the boxes
		function jwl_buttons_group_1($data) { // Buttons Group One
			sort($data);
			?><a name="buttons1"> </a><form action="options.php" method="post" name="jwl_main_options1"><?php
			do_settings_sections('jwl_options_group1');
			settings_fields('jwl_options_group1'); ?>
            
            <span style="margin-left:15px;"><input class="button-primary" type="submit" name="Save" style="padding-left:40px;padding-right:40px;margin-top:40px;" value="<?php _e('Update Buttons Group One Options','jwl-ultimate-tinymce'); ?>" id="submitbutton" /></span>
            </form>
            <div class="bottom_options_content" style="margin-top:30px;padding:10px;background-color:#E6EFEF;border:1px solid #000;border-radius:5px;width:300px;">
                <span style="padding-left:5px;"><strong><?php _e('Buttons Group One Master Controls:','jwl-ultimate-tinymce'); ?></strong></span><br />
                <select id="masterBox" name="masterBox" style="width:80px;">
                <option value="Row 1">Row 1</option><option value="Row 2">Row 2</option>
                <option value="Row 3">Row 3</option><option value="Row 4">Row 4</option>
                </select>
                <input type="button" id="allsts" value="Check All"><input type="button" id="nosts" value="UnCheck All">
            </div>
            <div class="bottom_options_content" style="margin-top:30px;padding:10px;background-color:#E6EFEF;border:1px solid #000;border-radius:5px;width:450px;">  
				<?php
                // Form for import/export group 1 settings
                ?><strong><?php _e( 'Export', 'jwl-ultimate-tinymce' ); ?></strong><?php
                echo '<form method="post">';
                _e( 'Export Buttons Group One Settings:', 'jwl-ultimate-tinymce' );
				echo '<span style="margin-left:5px;">';
                printf("<input type='submit' class='button' name='jwl_utmce_export' value='%s' />", __( 'Export Settings', 'jwl-ultimate-tinymce') );
				echo '</span>';
                echo '</form>';
                // Export logic, and import html form and logic
                jwl_export_group1();
                echo jwl_import_group1();
                ?>  
            </div>
			<?php
		}
		
		function jwl_buttons_group_2($data) { // Buttons Group Two
			sort($data);
			?><a name="buttons2"> </a><form action="options.php" method="post" name="jwl_main_options2"><?php
			do_settings_sections('jwl_options_group2');
			settings_fields('jwl_options_group2'); ?>
            <span style="margin-left:15px;"><input class="button-primary" type="submit" name="Save" style="padding-left:40px;padding-right:40px;margin-top:40px;" value="<?php _e('Update Buttons Group Two Options','jwl-ultimate-tinymce'); ?>" id="submitbutton" /></span>
            </form>
            <div class="bottom_options_content" style="margin-top:30px;padding:10px;background-color:#E6EFEF;border:1px solid #000;border-radius:5px;width:300px;">
                <span style="padding-left:5px;"><strong><?php _e('Buttons Group Two Master Controls:','jwl-ultimate-tinymce'); ?></strong></span><br />
                <select id="masterBox2" name="masterBox2" style="width:80px;">
                <option value="Row 1">Row 1</option><option value="Row 2">Row 2</option>
                <option value="Row 3">Row 3</option><option value="Row 4">Row 4</option>
                </select>
                <input type="button" id="allsts2" value="Check All"><input type="button" id="nosts2" value="UnCheck All">
            </div>
            <div class="bottom_options_content" style="margin-top:30px;padding:10px;background-color:#E6EFEF;border:1px solid #000;border-radius:5px;width:450px;">  
				<?php
                // Form for import/export group 1 settings
                ?><strong><?php _e( 'Export', 'jwl-ultimate-tinymce' ); ?></strong><?php
                echo '<form method="post">';
                _e( 'Export Buttons Group Two Settings:', 'jwl-ultimate-tinymce' );
				echo '<span style="margin-left:5px;">';
                printf("<input type='submit' class='button' name='jwl_utmce_export2' value='%s' />", __( 'Export Settings', 'jwl-ultimate-tinymce') );
				echo '</span>';
                echo '</form>';
                // Export logic, and import html form and logic
                jwl_export_group2();
                echo jwl_import_group2();
                ?>  
            </div>
            
			<?php
			if (isset($_POST['testing2']) || isset($_POST['jwl_group2_save'])) {
				$group2_testing = $_POST['testing2'];
				$group2_testing2 = stripslashes($group2_testing);
				$group2_testing3 = unserialize($group2_testing2);
				update_option('jwl_options_group2', $group2_testing3);
			}
		}
		function jwl_buttons_group_8($data) { // Content Editor Over-rides
			sort($data);
			?><a name="override"> </a><form action="options.php" method="post" name="jwl_main_options8"><?php
			do_settings_sections('jwl_options_group8');
			settings_fields('jwl_options_group8');
			?>
			<center><input class="button-primary" type="submit" name="Save" style="padding-left:40px;padding-right:40px;margin-top:40px;" value="<?php _e('Update Tinymce Options','jwl-ultimate-tinymce'); ?>" id="submitbutton" /></center>
            </form>
			<?php
		}
		function jwl_buttons_group_9($data) { // Other Plugins Buttons
			sort($data);
			?><a name="buttonsother"> </a><form action="options.php" method="post" name="jwl_main_options9"><?php
			do_settings_sections('jwl_options_group9');
			settings_fields('jwl_options_group9'); ?>
            <div class="bottom_options_content" style="margin-top:30px;padding:10px;background-color:#E6EFEF;border:1px solid #000;border-radius:5px;width:300px;float:left;">
            <span style="padding-left:5px;"><strong><?php _e('Other Plugins Buttons Master Controls:','jwl-ultimate-tinymce'); ?></strong></span><br />
            <select id="masterBox3" name="masterBox3" style="width:80px;">
            <option value="Row 1">Row 1</option><option value="Row 2">Row 2</option>
            <option value="Row 3">Row 3</option><option value="Row 4">Row 4</option>
            </select>
            </span>
			<span style="padding-left:10px;margin-top:20px;"><input type="button" id="allsts3" value="Check All"><input type="button" id="nosts3" value="UnCheck All">
            </div><div style="float:left;margin-top:70px;">
            <span style="margin-left:60px;"><input class="button-primary" type="submit" name="Save" style="padding-left:40px;padding-right:40px;" value="<?php _e('Update Other Plugins Buttons Options','jwl-ultimate-tinymce'); ?>" id="submitbutton" /></span></span></div><div style="clear:both;"></div>
            </form>
			<?php
		}
		function jwl_buttons_group_3($data) { // Miscellaneous Options and Features
			sort($data);
			?><a name="misc"> </a><form action="options.php" method="post" name="jwl_main_options3"><?php
			do_settings_sections('jwl_options_group3');
			settings_fields('jwl_options_group3');
			
			$options = get_option('jwl_options_group3');
			if (isset($options['jwl_signoff_field_id'])) {
			wp_editor( $options["jwl_signoff_field_id"], 'signoff-id', array( 'textarea_name' => 'jwl_options_group3[jwl_signoff_field_id]', 'media_buttons' => false ) );
			} else {
			wp_editor( 'Setup your signoff text here...', 'signoff-id', array( 'textarea_name' => 'jwl_options_group3[jwl_signoff_field_id]', 'media_buttons' => false ) );
			}
			
			?>
			<center><input class="button-primary" type="submit" name="Save" style="padding-left:40px;padding-right:40px;margin-top:40px;" value="<?php _e('Update Miscellaneous Options','jwl-ultimate-tinymce'); ?>" id="submitbutton" /></center>
            </form>
			<?php
		}
		function jwl_buttons_group_4($data) { // Admin Options
			sort($data);
			?><a name="adminopts"> </a><form action="options.php" method="post" name="jwl_main_options4"><?php
			do_settings_sections('jwl_options_group4');
			settings_fields('jwl_options_group4');
			
			
			echo '<div class="jwl_hide">';
			$options = get_option('jwl_options_group4');
			if (isset($options['jwl_qr_code_content'])) {
				wp_editor( $options["jwl_qr_code_content"], 'content-id', array( 'textarea_name' => 'jwl_options_group4[jwl_qr_code_content]', 'media_buttons' => false, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_adv', 'theme_advanced_buttons3' => '', 'theme_advanced_buttons4' => '' ) ) );
				} else {
				wp_editor( 'Use this unique QR (Quick Response) code with your smart device. The code will save the url of this webpage to the device for mobile sharing and storage.', 'content-id', array( 'textarea_name' => 'jwl_options_group4[jwl_qr_code_content]', 'media_buttons' => false, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_adv', 'theme_advanced_buttons3' => '', 'theme_advanced_buttons4' => '' ) ) );
			}
			echo '</div>';
			
			?>
			<center><input class="button-primary" type="submit" name="Save" style="padding-left:40px;padding-right:40px;" value="<?php _e('Update Admin Options','jwl-ultimate-tinymce'); ?>" id="submitbutton" /></center>
			</form>
			<?php
		}
		
}
$my_jwl_metabox_admin = new jwl_metabox_admin();



global $pagenow;
if ( 'plugins.php' === $pagenow )
{
    // Better update message
    $file   = basename( __FILE__ );
    $folder = basename( dirname( __FILE__ ) );
    $hook = "in_plugin_update_message-{$folder}/{$file}";
    add_action( $hook, 'jwl_update_message_cb', 20, 2 );
}

function jwl_update_message_cb( /* $plugin_data, $r */ )
{
    // readme contents
    //$data       = file_get_contents( 'http://plugins.trac.wordpress.org/browser/ultimate-tinymce/trunk/readme.txt?format=txt' );

    // assuming you've got a Changelog section
    // @example == Changelog ==
    //$changelog  = stristr( $data, '== Changelog ==' );

    // assuming you've got a Screenshots section
    // @example == Screenshots ==
    //$changelog  = stristr( $changelog, '== Screenshots ==', true );

    // only return for the current & later versions
	//$plugin_data = get_plugin_data( __FILE__ );
    //$curr_ver = $plugin_data['Version'];
    //$curr_ver   = get_plugin_data('Version');

    // assuming you use "= v" to prepend your version numbers
    // @example = v0.2.1 =
    //$changelog  = stristr( $changelog, "= {$curr_ver} =" );

    // uncomment the next line to var_export $var contents for dev:
    # echo '<pre>'.var_export( $plugin_data, false ).'<br />'.var_export( $r, false ).'</pre>';

    // echo stuff....
    $output = '<span style="margin-left:10px;color:#FF0000;">Please Read Changelog Details Before Upgrading.</span>';
	
    return print $output;
}

?>