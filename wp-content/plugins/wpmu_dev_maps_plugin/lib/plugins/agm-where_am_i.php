<?php
/*
Plugin Name: Where am I?
Description: Adds visitor's location marker to the map in supporting browsers, automatically or via shortcode attribute.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Wmi_AdminPages {

	public static function serve () {
		$me = new Agm_Wmi_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
	}

	function register_settings () {
		add_settings_section('agm_google_wmi_fields', __('Where am I?', 'agm_google_maps'), create_function('', ''), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_wmi_auto', __('Behavior', 'agm_google_maps'), array($this, 'create_auto_add_box'), 'agm_google_maps_options_page', 'agm_google_wmi_fields');
		add_settings_field('agm_google_maps_wmi_marker', __('Appearance', 'agm_google_maps'), array($this, 'create_marker_options_box'), 'agm_google_maps_options_page', 'agm_google_wmi_fields');
	}

	function create_auto_add_box () {
		$shortcode_only = $this->_get_options('shortcode_only');
		$no = $shortcode_only ? 'checked="checked"' : false;
		$yes = $shortcode_only ? false : 'checked="checked"';

		echo '<input type="radio" id="agm-wmi-auto-yes" name="agm_google_maps[wmi-shortcode_only]" value="" ' . $yes . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-auto-yes">' . __('I want to automatically show visitor locations on all my maps', 'agm_google_maps') . '</label>' .
			'<div><small>' . __('Visitor location will be automatically added to all your maps', 'agm_google_maps') . '</small></div>' .
		'<br />';
		echo '<input type="radio" id="agm-wmi-auto-no" name="agm_google_maps[wmi-shortcode_only]" value="1" ' . $no . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-auto-no">' . __('I want to specify which maps will show visitor location using a shortcode attribute', 'agm_google_maps') . '</label>' .
			'<div><small>' . __('You can display visitor location on your maps by adding <code>visitor_location=&quot;yes&quot;</code> to your shortcodes', 'agm_google_maps') . '</small></div>' .
		'<br />';

		$center = $this->_get_options('auto_center');
		$center = $center ? 'checked="checked"' : '';
		echo '<br />';
		echo '<input type="hidden" name="agm_google_maps[wmi-auto_center]" value="" />' .
			'<input type="checkbox" id="agm-wmi-auto_center" name="agm_google_maps[wmi-auto_center]" value="1" ' . $center . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-auto_center">' . __('Automatically center map to visitor location', 'agm_google_maps') . '</label>' .
		'<br />';

		$marker = $this->_get_options('marker');
		$marker = $marker ? 'checked="checked"' : '';
		echo '<input type="hidden" name="agm_google_maps[wmi-marker]" value="" />' .
			'<input type="checkbox" id="agm-wmi-marker" name="agm_google_maps[wmi-marker]" value="1" ' . $marker . ' />' .
			'&nbsp' .
			'<label for="agm-wmi-marker">' . __('Automatically add visitor location to the marker list for my map', 'agm_google_maps') . '</label>' .
			'<div><small>' . __('Default behavior is to add the visitor location to the map only. Enable this option if you want to include it in the marker list as well.', 'agm_google_maps') . '</small></div>' .
		'<br />';
	}

	function create_marker_options_box () {
		$label = $this->_get_options('label');
		$label = $label ? $label : __('This is you', 'agm_google_maps');
		echo '<label for="agm-wmi-label">' . __('Visitor marker label', 'agm_google_maps') . '</label>' .
			'&nbsp;' .
			'<input type="text" class="widefat" id="agm-wmi-label" name="agm_google_maps[wmi-label]" value="' . esc_attr($label) . '" />' .
		'<br />';

		$icon = $this->_get_options('icon');
		echo '<label for="agm-wmi-icon">' . __('Visitor marker icon', 'agm_google_maps') . '</label>' .
			$this->_create_icons_box() .
			'<input type="text" class="widefat" id="agm-wmi-icon" name="agm_google_maps[wmi-icon]" value="' . esc_attr($icon) . '" />' .
			'<div><small>' . __('Leave empty to use default icon', 'agm_google_maps') . '</small></div>' .
		'<br />';		
	}

	private function _create_icons_box () {
		$out = '';
		$icons = glob(AGM_PLUGIN_BASE_DIR . '/img/*.png');
		foreach ($icons as $k=>$v) {
			$icon = AGM_PLUGIN_URL . '/img/' . basename($v);
			$out .= "<a href='#select'><img src='{$icon}' /></a> ";
		}
		$out = '<div id="agm_google_maps-wmi-preset_icons">' . $out . '</div>';
		$out .= <<<EOMarkerSelectJs
<script type="text/javascript">
(function ($) {
$(function () {
	$("#agm_google_maps-wmi-preset_icons a").click(function () {
		$("#agm-wmi-icon").val($(this).find("img").attr("src"));
		return false;
	});
});
})(jQuery);
</script>
EOMarkerSelectJs;
		return $out;
	}

	private function _get_options ($key='shortcode_only') {
		$opts = apply_filters('agm_google_maps-options-wmi', get_option('agm_google_maps'));
		return @$opts['wmi-' . $key];
	}
}

class Agm_Wmi_UserPages {
	
	private function __construct () {}
	
	public static function serve () {
		$me = new Agm_Wmi_UserPages;
		$me->_add_hooks();
	}
	
	private function _add_hooks () {
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));
		add_action('agm_google_maps-add_javascript_data', array($this, 'add_wmi_data'));

		// Shortcode attribute
		add_filter('agm_google_map-shortcode-attributes_defaults', array($this, 'attributes_defaults'));
		add_filter('agm_google_map-shortcode-overrides_process', array($this, 'overrides_process'), 10, 2);
	}

	function attributes_defaults ($defaults) {
		$defaults['visitor_location'] = false;
		return $defaults;
	}

	function overrides_process ($overrides, $atts) {
		if (@$atts['visitor_location']) $overrides['visitor_location'] = $atts['visitor_location'];
		return $overrides;
	}
	
	function load_scripts () {
		wp_enqueue_script('agm-marker_cluster-user', AGM_PLUGIN_URL . '/js/where_am_i-user.js', array('jquery'));
	}

	function add_wmi_data () {
		$label = $this->_get_options('label');
		$label = $label ? $label : __('This is you', 'agm_google_maps');
		printf(
			'<script type="text/javascript">if (typeof(_agmWmi) == "undefined") _agmWmi={
				"add_marker": %d,
				"shortcode_only": %d,
				"auto_center": %d,
				"marker_label": "%s",
				"icon": "%s"
			};</script>',
			(int)$this->_get_options('marker'),
			(int)$this->_get_options('shortcode_only'),
			(int)$this->_get_options('auto_center'),
			esc_js($label),
			esc_js($this->_get_options('icon'))
		);
	}

	private function _get_options ($key='shortcode_only') {
		$opts = apply_filters('agm_google_maps-options-wmi', get_option('agm_google_maps'));
		return @$opts['wmi-' . $key];
	}
}

if (is_admin()) Agm_Wmi_AdminPages::serve();
else Agm_Wmi_UserPages::serve();
