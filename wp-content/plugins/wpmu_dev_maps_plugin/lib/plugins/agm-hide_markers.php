<?php
/*
Plugin Name: Hide auto-created Markers
Description: Cleans up your maps by hiding the map markers for maps auto-generated from custom fields.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Hacm_UserPages {
	
	private function __construct () {}
	
	public static function serve () {
		$me = new Agm_Hacm_UserPages;
		$me->_add_hooks();
	}
	
	private function _add_hooks () {
		add_filter('agm_google_maps-autogen_map-shortcode_attributes', array($this, 'autogen_hide'));
		add_filter('agm_google_map-shortcode-attributes_defaults', array($this, 'attributes_defaults'));
		add_filter('agm_google_map-shortcode-overrides_process', array($this, 'overrides_process'), 10, 2);
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));
	}
	
	function load_scripts () {
		wp_enqueue_script('agm-marker-hacm', AGM_PLUGIN_URL . '/js/hide_markers.js');
	}

	function autogen_hide ($args) {
		$args['hide_map_markers'] = 'true';
		return $args;
	}

	function attributes_defaults ($defaults) {
		$defaults['hide_map_markers'] = false;
		return $defaults;
	}

	function overrides_process ($overrides, $atts) {
		if (isset($atts['hide_map_markers']) && in_array($atts['hide_map_markers'], agm_positive_values())) $overrides['hide_map_markers'] = true;
		return $overrides;
	}
}

if (!is_admin()) Agm_Hacm_UserPages::serve();
