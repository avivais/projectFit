<?php
/*
Plugin Name: Responsive maps
Description: Allows your maps to be full width and contract/expand with your page size.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Rmaps_Pages {

	private function __construct () {}

	public static function serve () {
		$me = new Agm_Rmaps_Pages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));
		add_action('agm_google_map-shortcode-attributes_defaults', array($this, 'attribute_defaults'));

		add_filter('agm_google_map-shortcode-overrides_process', array($this, 'process_overrides'), 10, 2);		
		add_filter('agm_google_maps-autogen_map-shortcode_attributes', array($this, 'autogen_attributes'));
		add_filter('agm_google_maps-bp_profile_map-all_users_overrides', array($this, 'bp_profiles_attributes'));

		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
	}

	function attribute_defaults ($args) {
		$args['is_responsive'] = false;
		return $args;
	}

	function process_overrides ($overrides, $args) {
		$_yes = agm_positive_values();
		$_no = agm_negative_values();
		if (isset($args['is_responsive']) && in_array($args['is_responsive'], $_yes)) $overrides['is_responsive'] = true;
		else if (isset($args['is_responsive']) && in_array($args['is_responsive'], $_no)) $overrides['is_responsive'] = false;
		else {
			$opts = $this->_get_options();
			if (isset($opts['auto_assign-all']) && $opts['auto_assign-all']) {
				$overrides['is_responsive'] = true;
				if (isset($opts['auto_assign-respect_width']) && $opts['auto_assign-respect_width']) $overrides['responsive_respect_width'] = true;
			} else $overrides['is_responsive'] = false;
		}
		return $overrides;
	}

	function autogen_attributes ($args) {
		if (isset($args['is_responsive'])) return $args;
		$opts = $this->_get_options();
		if (isset($opts['auto_assign-autogen']) && $opts['auto_assign-autogen']) {
			$args['is_responsive'] = "true";
		}
		return $args;
	}

	function bp_profiles_attributes ($args) {
		$opts = $this->_get_options();
		if (isset($opts['auto_assign-bp_profile']) && $opts['auto_assign-bp_profile']) {
			$args['is_responsive'] = "true";
		}
		return $args;
	}

	function load_scripts () {
		wp_enqueue_script('rmaps-user', AGM_PLUGIN_URL . '/js/rmaps-user.js', array('jquery'));
	}

	function register_settings () {
		add_settings_section('agm_google_maps_rmaps', __('Responsive maps', 'agm_google_maps'), array($this, 'create_section_notice'), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_auto_assign', __('Make these maps responsive', 'agm_google_maps'), array($this, 'create_auto_assign_box'), 'agm_google_maps_options_page', 'agm_google_maps_rmaps');
	}

	function create_section_notice () {
		echo '<em>' .
			__('You can toggle the responsive flag in your shortcodes with <code>is_responsive="yes|no"</code> shortcode attribute. You can also specify which ones of your maps should always be responsive.', 'agm_google_maps') .
		'</em>';
	}

	function create_auto_assign_box () {
		$opts = $this->_get_options();

		$autogen = isset($opts['auto_assign-autogen']) && $opts['auto_assign-autogen'] ? 'checked="checked"' : '';
		echo '' .
			'<input type="hidden" name="agm_google_maps[rmaps][auto_assign-autogen]" value="" />' .
			'<input type="checkbox" id="agm-rmaps-auto_assign-autogen" name="agm_google_maps[rmaps][auto_assign-autogen]" value="1" ' . $autogen . ' />' .
			'&nbsp;' .
			'<label for="agm-rmaps-auto_assign-autogen">' . __('Auto-generated maps', 'agm_google_maps') . '</label>' .
		'<br />';
		if (class_exists('Agm_Bp_Pm_AdminPages') && defined('BP_VERSION')) {
			$bp_profile = isset($opts['auto_assign-bp_profile']) && $opts['auto_assign-bp_profile'] ? 'checked="checked"' : '';
			echo '' .
				'<input type="hidden" name="agm_google_maps[rmaps][auto_assign-bp_profile]" value="" />' .
				'<input type="checkbox" id="agm-rmaps-auto_assign-bp_profile" name="agm_google_maps[rmaps][auto_assign-bp_profile]" value="1" ' . $bp_profile . ' />' .
				'&nbsp;' .
				'<label for="agm-rmaps-auto_assign-bp_profile">' . __('BuddyPress member directory map', 'agm_google_maps') . '</label>' .
			'<br />';
		}
		$all = isset($opts['auto_assign-all']) && $opts['auto_assign-all'] ? 'checked="checked"' : '';
		echo '' .
			'<input type="hidden" name="agm_google_maps[rmaps][auto_assign-all]" value="" />' .
			'<input type="checkbox" id="agm-rmaps-auto_assign-all" name="agm_google_maps[rmaps][auto_assign-all]" value="1" ' . $all . ' />' .
			'&nbsp;' .
			'<label for="agm-rmaps-auto_assign-all">' . __('All my maps <small>(except the ones I manually exclude with <code>is_responsive="no"</code> shortcode attribute)</small>', 'agm_google_maps') . '</label>' .
		'<br />';

		$respect_width = isset($opts['auto_assign-respect_width']) && $opts['auto_assign-respect_width'] ? 'checked="checked"' : '';
		echo '<br />' .
			'<input type="hidden" name="agm_google_maps[rmaps][auto_assign-respect_width]" value="" />' .
			'<input type="checkbox" id="agm-rmaps-auto_assign-respect_width" name="agm_google_maps[rmaps][auto_assign-respect_width]" value="1" ' . $respect_width . ' />' .
			'&nbsp;' .
			'<label for="agm-rmaps-auto_assign-respect_width">' . __('Respect shortcode width attribute, if set', 'agm_google_maps') . '</label>' .
			'<div><small>' . __('If set, this option will force your maps with width shortcode attribute set to scale relative to their original size, rather then expanding the full width of parent element.', 'agm_google_maps') . '</small></div>' .
		'<br />';
	}

	private function _get_options () {
		$opts = apply_filters('agm_google_maps-options-rmaps', get_option('agm_google_maps'));
		$opts = isset($opts['rmaps']) && $opts['rmaps'] ? $opts['rmaps'] : array();
		return $opts;
	}
}
Agm_Rmaps_Pages::serve();