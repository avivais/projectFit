<?php
/*
Plugin Name: Map loading message
Description: Gives your maps a customizable loading message.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Mlm_Pages {

	private $_data;

	private function __construct () {
		$this->_data = $this->_get_options();
	}

	public static function serve () {
		$me = new Agm_Mlm_Pages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('agm_google_map-shortcode-attributes_defaults', array($this, 'attribute_defaults'));
		add_filter('agm_google_maps-autogen_map-shortcode_attributes', array($this, 'autogen_attributes'));
		add_filter('agm_google_map-shortcode-overrides_process', array($this, 'process_overrides'), 10, 2);
		add_filter('agm_google_maps-bp_profile_map-all_users_overrides', array($this, 'bp_profiles_attributes'));
		
		add_filter('agm_google_maps-shortcode-tag_content', array($this, 'apply_loading_message'), 10, 2);

		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
	}

	function apply_loading_message ($msg, $map) {
		if (isset($map['loading_message'])) return $this->_create_loading_message($map['loading_message']);
		return $msg;
	}

	function attribute_defaults ($args) {
		$args['loading_message'] = false;
		return $args;
	}

	function bp_profiles_attributes ($args) {
		$args['loading_message'] = $this->_data['bp_profile'];
		return $args;
	}

	function process_overrides ($overrides, $args) {
		if (isset($args['loading_message']) && $args['loading_message']) $overrides['loading_message'] = $args['loading_message'];
		else if ($this->_data['all']) $overrides['loading_message'] = $this->_data['all'];
		return $overrides;
	}

	function autogen_attributes ($args) {
		$args['loading_message'] = $this->_data['autogen'];
		return $args;
	}

	function register_settings () {
		add_settings_section('agm_google_maps_mlm', __('Map loading message', 'agm_google_maps'), array($this, 'create_section_notice'), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_mlm_auto_assign', __('Add loading message to these maps', 'agm_google_maps'), array($this, 'create_auto_assign_box'), 'agm_google_maps_options_page', 'agm_google_maps_mlm');
	}

	function create_section_notice () {
		echo '<em>' .
			__('You add the loading message in your shortcodes with <code>loading_message="My message"</code> shortcode attribute. You can also specify loading messages for your other maps here.', 'agm_google_maps') .
		'</em>';
	}

	function create_auto_assign_box () {

		echo '' .
			'<label for="agm-mlm-autogen">' . __('Auto-generated maps loading message', 'agm_google_maps') . '</label>' .
			'<input type="text" class="widefat" id="agm-mlm-autogen" name="agm_google_maps[mlm][autogen]" value="' . esc_attr($this->_data['autogen']) . '" />' .
		'<br />';
		if (class_exists('Agm_Bp_Pm_AdminPages') && defined('BP_VERSION')) {
			echo '' .
				'<label for="agm-mlm-bp_profile">' . __('BuddyPress member directory map', 'agm_google_maps') . '</label>' .
				'<input type="text" class="widefat" id="agm-mlm-bp_profile" name="agm_google_maps[mlm][bp_profile]" value="' . esc_attr($this->_data['bp_profile']) . '" />' .
			'<br />';
		}
		echo '' .
			'<label for="agm-mlm-all">' . __('All maps loading message', 'agm_google_maps') . '</label>' .
			'<input type="text" class="widefat" id="agm-mlm-all" name="agm_google_maps[mlm][all]" value="' . esc_attr($this->_data['all']) . '" />' .
		'<br />';
	}

	private function _get_options () {
		$default_msg = __('Map is loading, please hold on', 'agm_google_maps');
		$opts = apply_filters('agm_google_maps-options-mlm', get_option('agm_google_maps'));
		$opts = isset($opts['mlm']) && $opts['mlm'] ? $opts['mlm'] : array();
		return wp_parse_args($opts, array(
			'autogen' => $default_msg,
			'bp_profile' => $default_msg,
			'all' => $default_msg,
		));
	}

	private function _create_loading_message ($str) {
		if (!$str) return false;
		return '<div class="agm_google_maps-loading_message">' . $str . '</div>';
	}
}
Agm_Mlm_Pages::serve();