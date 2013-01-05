<?php
/*
Plugin Name: Nearby Facebook Friends
Description: Shows a list of nearby facebook friends.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Fbnf_AdminPages {

	private $_help;
	
	private function __construct () {
		$this->_help = Agm_AdminHelp::get_instance();
	}
	
	public static function serve () {
		$me = new Agm_Fbnf_AdminPages;
		$me->_add_hooks();
	}
	
	private function _add_hooks () {
		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
	}

	function register_settings () {
		add_settings_section('agm_google_maps_facebook', __('Nearby Facebook Friends', 'agm_google_maps'), create_function('', ''), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_fbnf_fb', __('Facebook App', 'agm_google_maps'), array($this, 'create_fb_app_box'), 'agm_google_maps_options_page', 'agm_google_maps_facebook');
		add_settings_field('agm_google_maps_fbnf_scope', __('Scope', 'agm_google_maps'), array($this, 'create_scope_box'), 'agm_google_maps_options_page', 'agm_google_maps_facebook');

		$this->_add_help();
	}

	private function _add_help () {
		$this->_help->add_tab(
			'agm_google_maps-fbnf',
			__('Setting Facebook API settings', 'agm_google_maps'),
			'<p>' . __('Follow these steps to set up <em>App ID/API key</em> field', 'agm_google_maps') . '</p>' .
			'<ol>' .
				'<li>' . __('<a target="_blank" href="https://developers.facebook.com/apps">Create a Facebook Application</a>', 'agm_google_maps') . '</li>' .
				'<li>' . sprintf(__('Your Facebook App setup should look similar to this:<br /><img src="%s" />', 'agm_google_maps'), AGM_PLUGIN_URL . '/img/system/fb-setup.png') . '</li>' .
			'</ol>'
		);
	}
	
	function create_fb_app_box () {
		$fb_app_id = $this->_get_options('fb_app_id');
		echo '<label for="agm-fbnf-fb_app_id">' . __('App ID', 'agm_google_maps') . ':</label> ';
		echo '<input type="text" class="widefat" id="agm-fbnf-fb_app_id" name="agm_google_maps[fbnf-fb_app_id]" value="' . esc_attr($fb_app_id) . '">';
		echo '<a href="#help" data-agm_contextual_trigger="#tab-link-agm_google_maps-fbnf">' . __('For more help on setting this up, click here', 'agm_google_maps') . '</a>';
	}

	function create_scope_box () {
		$radius = $this->_get_options('radius');
		$radius = (int)$radius ? (int)$radius : 1000;
		echo '<label for="agm-fbnf-radius">' . __('Check for friends within ', 'agm_google_maps') . '</label> ';
		echo '<input type="text" id="agm-fbnf-radius" size="6" name="agm_google_maps[fbnf-radius]" value="' . (int)$radius . '"> meters';

		echo '<br />';

		$months = $this->_get_options('months');
		$months = (int)$months ? (int)$months : 4;
		echo '<label for="agm-fbnf-months">' . __('Search for friends in updates and photos for friends within last ', 'agm_google_maps') . '</label> ';
		echo '<input type="text" id="agm-fbnf-months" size="6" name="agm_google_maps[fbnf-months]" value="' . (int)$months . '"> months';
	}

	private function _get_options ($key) {
		$opts = apply_filters('agm_google_maps-options-fbnf', get_option('agm_google_maps'));
		return @$opts['fbnf-' . $key];
	}
}


class Agm_Fbnf_PublicPages {
	
	private function __construct () {}
	
	public static function serve () {
		$me = new Agm_Fbnf_PublicPages;
		$me->_add_hooks();
	}
	
	private function _add_hooks () {
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));
		add_action('agm_google_maps-add_javascript_data', array($this, 'add_javascript_data'));
	}
	
	function add_javascript_data () {
		$fb_app_id = $this->_get_options('fb_app_id');
		$radius = $this->_get_options('radius');
		$radius = (int)$radius ? (int)$radius : 1000;
		$months = $this->_get_options('months');
		$months = (int)$months ? (int)$months : 4;
		printf(
			'<script type="text/javascript">if (typeof(_agmFbnf) == "undefined") _agmFbnf={
				"fb_app_id": "%s",
				"radius": %d,
				"months": %d
			};</script>',
			$fb_app_id,
			$radius,
			$week
		);
	}
	
	function load_scripts () {
		wp_enqueue_script('fb-nearby_friends-user', AGM_PLUGIN_URL . '/js/fb-nearby_friends-user.js', array('jquery'));
	}

	private function _get_options ($key) {
		$opts = apply_filters('agm_google_maps-options-fbnf', get_option('agm_google_maps'));
		return @$opts['fbnf-' . $key];
	}
}

if (is_admin()) Agm_Fbnf_AdminPages::serve();
else Agm_Fbnf_PublicPages::serve();
