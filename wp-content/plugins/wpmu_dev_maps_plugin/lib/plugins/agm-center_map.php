<?php
/*
Plugin Name: Center map on location
Description: Adds a <code>center</code> shortcode attribute that will force your map to open centered on your comma-separated latitude/longitude pair.<br />E.g. <code>[map id="12" center="45.359865,20.412598"]</code>
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Cm_PublicPages {
	
	private function __construct () {}
	
	public static function serve () {
		$me = new Agm_Cm_PublicPages;
		$me->_add_hooks();
	}
	
	private function _add_hooks () {
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));
		add_filter('agm_google_map-shortcode-attributes_defaults', array($this, 'set_attribute_defaults'));
		add_filter('agm_google_map-shortcode-overrides_process', array($this, 'process_overrides'), 10, 2);
	}
	
	function load_scripts () {
		wp_enqueue_script('center_map-user', AGM_PLUGIN_URL . '/js/center_map-user.js', array('jquery'));
	}
	
	function set_attribute_defaults ($atts) {
		$atts['center'] = false;
		return $atts;
	}
	
	function process_overrides ($overrides, $atts) {
		if (@$atts['center']) $overrides['center'] = $this->_convert_to_point($atts['center']);
		return $overrides;
	}
	
	function _convert_to_point ($src) {
		if (!$src) return false;		

		$lat = $lng = false;
		list($lat,$lng) = explode(',', $src, 2);

		// Validate pair
		// ...

		return array(
			'latitude' => trim($lat),
			'longitude' => trim($lng),
		);		
	}
}

if (!is_admin()) Agm_Cm_PublicPages::serve();
