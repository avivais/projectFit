<?php
/*
Plugin Name: KML Overlay
Description: Allows you to overlay a KML URL over your map (e.g. from Flickr: <code>http://api.flickr.com/services/feeds/geo/Zrenjanin?tags=sunflower&amp;lang=en-us&amp;format=kml_nl</code>). Also adds support for new <code>kml_url</code> attribute to your shortcodes. You can use the new attribute either standalone, or combined with your existing maps.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Kml_AdminPages {

	private function __construct () {}

	public static function serve () {
		$me = new Agm_Kml_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		// Basic KML overlay funcitonality
		add_action('agm_google_maps-load_admin_scripts', array($this, 'load_scripts'));
		add_action('agm_google_maps-prepare_for_save-map_options', array($this, 'prepare_for_save'), 10, 2);
		add_action('agm_google_maps-prepare_map-options', array($this, 'prepare_for_load'), 10, 2);
	}

	function load_scripts () {
		wp_enqueue_script('kml_overlay-admin', AGM_PLUGIN_URL . '/js/kml_overlay-admin.js', array('jquery'));
	}

	function prepare_for_save ($options, $raw) {
		if (isset($raw['kml_url'])) $options['kml_url'] = $raw['kml_url'];
		return $options;
	}

	function prepare_for_load ($options, $raw) {
		if (isset($raw['kml_url'])) $options['kml_url'] = $raw['kml_url'];
		return $options;
	}
}

class Agm_Kml_UserPages {

	private function __construct () {}

	public static function serve () {
		$me = new Agm_Kml_UserPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		// Basic KML overlay funcitonality
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));
		add_action('agm_google_maps-prepare_map-options', array($this, 'prepare_for_load'), 10, 2);

		// KML in shortcode attribute
		add_filter('agm_google_map-shortcode-attributes_defaults', array($this, 'attributes_defaults'));
		add_filter('agm_google_map-shortcode-attributes_process', array($this, 'attributes_process'));
		add_filter('agm_google_map-shortcode-overrides_process', array($this, 'overrides_process'), 10, 2);
		add_filter('agm_google_maps-shortcode-create_tag', array($this, 'create_map_tag_from_shortcode'), 10, 2);
	}

	function load_scripts () {
		wp_enqueue_script('kml_overlay-user', AGM_PLUGIN_URL . '/js/kml_overlay-user.js', array('jquery'));
	}

	function prepare_for_load ($options, $raw) {
		if (isset($raw['kml_url'])) $options['kml_url'] = $raw['kml_url'];
		return $options;
	}

	function attributes_defaults ($defaults) {
		$defaults['kml_url'] = false;
		return $defaults;
	}

	function overrides_process ($overrides, $atts) {
		if (@$atts['kml_url']) $overrides['kml_url'] = $atts['kml_url'];
		return $overrides;
	}

	function attributes_process ($atts) {
		if (!$atts['id'] && !$atts['query'] && $atts['kml_url']) $atts['id'] = md5($atts['kml_url']);
		return $atts;
	}

	function create_map_tag_from_shortcode ($map, $overrides) {
		if ($map) return $map;
		if (!@$overrides['kml_url']) return $map;

		$model = new AgmMapModel;
		$defaults = $model->get_map_defaults();
		$map = array(
			'id' => md5($overrides['kml_url']),
			'defaults' => $defaults,
			'markers' => array(),
			'show_map' => 1,
			'show_markers' => 0,
			'show_images' => 0,
			'zoom' => $defaults['zoom'],
		);
		return $map;
	}
}


if (is_admin()) Agm_Kml_AdminPages::serve();
else Agm_Kml_UserPages::serve();