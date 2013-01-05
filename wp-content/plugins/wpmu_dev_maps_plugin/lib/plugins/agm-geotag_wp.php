<?php
/*
Plugin Name: Geotag my posts
Description: Allows you to add location context to your posts, pages or custom post types.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_GwpAdminPages {

	private $_data;

	private function __construct () {
		$this->_data = new Agm_GwpModel;
	}

	public static function serve () {
		$me = new Agm_GwpAdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));

		add_action('add_meta_boxes', array($this, 'register_metabox'));
		add_action('save_post', array($this, 'save_metabox_values'));
	}

	function register_settings () {
		add_settings_section('agm_google_maps_gwp', __('Geotag my posts', 'agm_google_maps'), create_function('', ''), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_fbnf_fb', __('Geotagging metabox', 'agm_google_maps'), array($this, 'create_post_types_box'), 'agm_google_maps_options_page', 'agm_google_maps_gwp');
		//add_settings_field('agm_google_maps_fbnf_scope', __('Scope', 'agm_google_maps'), array($this, 'create_scope_box'), 'agm_google_maps_options_page', 'agm_google_maps_gwp');
	}

	function create_post_types_box () {
		$selected_types = $this->_data->get_option('post_types');
		$selected_types = $selected_types ? $selected_types : array();
		$post_types = get_post_types(array("public" => true), 'objects');
		echo '<label for="agm-gwp-post_types">' . __('Show geotagging metabox for', 'agm_google_maps') . ':</label><br />';
		foreach ($post_types as $type => $obj) {
			$checked = in_array($type, $selected_types) ? 'checked="checked"' : '';
			echo '<input type="checkbox" id="agm-gwp-post_type-' . $type . '" name="agm_google_maps[gwp-post_types][]" value="' . esc_attr($type) . '" ' . $checked . '>' .
				' <label for="agm-gwp-post_type-' . $type . '">' . $obj->label . '</label><br />' .
			'';
		}
	}

	function register_metabox () {
		$post_types = $this->_data->get_option('post_types');
		$post_types = $post_types ? $post_types : array();

		foreach ($post_types as $type) {
			add_meta_box(
				'',
				__('Location', 'agm_google_maps'),
				array($this, 'render_metabox'),
				$type,
				'side',
				'default'
			);
		}

		// Enqueue dependencies
		global $post, $pagenow;
		if (!in_array($pagenow, array('post.php', 'post-new.php'))) return false; // Editor pages, double-check
		if (!is_object($post) || !isset($post->post_type) || !in_array($post->post_type, $post_types)) return false; // Invalid post type
		
		wp_enqueue_script('agm-gwp-admin', AGM_PLUGIN_URL . '/js/gwp-admin.js', array('jquery'));
	}

	function render_metabox () {
		global $post;
		$post_id = wp_is_post_revision($post->ID);
		$post_id = $post_id ? $post_id : $post->ID;
		
		$lat = get_post_meta($post_id, '_agm_latitude', true);
		$lng = get_post_meta($post_id, '_agm_longitude', true);
		echo '<div id="agm-gwp-location_root">' .
			'<label for="agm-address">Address: <input type="text" name="agm-address" id="agm-address" value="" /></label>' .
			'<input type="hidden" name="agm-latitude" id="agm-latitude" value="' . esc_attr($lat) . '" />' .
			'<input type="hidden" name="agm-longitude" id="agm-longitude" value="' . esc_attr($lng) . '" />' .
		'</div>';
	}
	
	function save_metabox_values () {
		if (defined('DOING_AJAX')) return false; // autosave
		
		global $post;
		$post_id = wp_is_post_revision($post->ID);
		$post_id = $post_id ? $post_id : $post->ID;

		$post_types = $this->_data->get_option('post_types');
		$post_types = $post_types ? $post_types : array();

		if (!in_array($post->post_type, $post_types)) return false;

		$address = !empty($_POST['agm-address']) ? wp_strip_all_tags($_POST['agm-address']) : false;
		$lat = !empty($_POST['agm-latitude']) ? (float)$_POST['agm-latitude'] : false;
		$lng = !empty($_POST['agm-longitude']) ? (float)$_POST['agm-longitude'] : false;

		if ($lat && $lng) return $this->_update_post_geotag($post_id, $lat, $lng);

		if (!$address) return false;
		$result = $this->_data->geocode_address($address);
		return $this->_update_post_geotag(
			$post_id, 
			$result->geometry->location->lat,
			$result->geometry->location->lng
		);
	}

	private function _update_post_geotag ($post_id, $lat, $lng) {
		update_post_meta($post_id, '_agm_latitude', $lat);
		update_post_meta($post_id, '_agm_longitude', $lng);
	}

}


class Agm_GwpModel {

	private $_data;
	private $_db;
	private $_model;

	public function __construct () {
		global $wpdb;
		$this->_db = $wpdb;
		$this->_data = apply_filters('agm_google_maps-options-gwp', get_option('agm_google_maps'));
		$this->_model = new AgmMapModel;
	}

	public function geocode_address ($address) { return $this->_model->geocode_address($address); }

	public function get_option ($key, $default=false) {
		$key = "gwp-{$key}";
		return isset($this->_data[$key]) ? $this->_data[$key] : $default;
	}

	public function get_all_geolocated_posts ($limit=false) {
		$limit = (int)$limit;
		$limit = $limit ? "LIMIT {$limit}" : '';

		$sql = "SELECT * FROM {$this->_db->posts} AS p, {$this->_db->postmeta} AS pm " .
			"WHERE pm.meta_key = '_agm_longitude' " .
			"AND p.ID = pm.post_id " .
		"{$limit}";
		return $this->_db->get_results($sql);
	}

	public function get_all_geolocated_posts_as_markers ($limit=false) {
		$result = array();
		$posts = $this->get_all_geolocated_posts($limit);
		foreach ($posts as $post) {
			$result[] = $this->post_to_marker($post);
		}
		return $result;
	}

	public function get_the_excerpt ($post) {
		$str = $post->post_excerpt
			? $post->post_excerpt 
			: preg_replace('/(.{0,247}).*/um', '$1', preg_replace('/\r|\n/', ' ', strip_shortcodes(wp_strip_all_tags($post->post_content)))) . '...'
		;
		return apply_filters('get_the_excerpt', $str);
	}

	public function post_to_marker ($post) {
		$excerpt = $this->get_the_excerpt($post);
		$body = '<p>' . $excerpt . '</p>' .
			'<a href="' . get_permalink($post->ID) . '">' . __('Read more', 'agm_google_maps') . '</a>'
		;
		return array(
			'title' => $post->post_title,
			'body' => $body,
			'icon' => 'marker.png',
			'position' => array (
				get_post_meta($post->ID, '_agm_latitude', true),
				get_post_meta($post->ID, '_agm_longitude', true),
			),
			'disposition' => 'post_marker'
		);
	}
	
	public function find_nearby_posts ($lat, $lng, $distance) {
		list($min_lat, $max_lat, $min_lng, $max_lng) = $this->_model->find_bounding_coordinates($lat, $lng, $distance);
		// Dude!
		$sql = "
SELECT DISTINCT post_id
FROM (
	SELECT latitude, longitude, t1.post_id FROM
	(SELECT post_id, meta_value as longitude FROM {$this->_db->postmeta} WHERE meta_key='_agm_longitude') as t1
	LEFT JOIN 
	(SELECT post_id, meta_value as latitude FROM {$this->_db->postmeta} WHERE meta_key='_agm_latitude') as t2
	ON t1.post_id=t2.post_id
) as meta
WHERE 
longitude+0.0 > {$min_lng}
AND longitude+0.0 < {$max_lng} 
AND 
latitude+0.0 > {$min_lat}
AND latitude+0.0 < {$max_lat}
";
		$post_ids = $this->_db->get_col($sql);
		if (!$post_ids) return array();
		$post_ids = join(',', $post_ids);
		return $this->_db->get_results("SELECT * FROM {$this->_db->posts} WHERE ID IN ({$post_ids})");
	}
}


class Agm_GwpUserPages {
	
	private $_data;
	private $_model;

	private function __construct () {
		$this->_data = new Agm_GwpModel;
		$this->_model = new AgmMapModel;
	}

	public static function serve () {
		$me = new Agm_GwpUserPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));

		add_shortcode('agm_gwp_geocoded_posts', array($this, 'all_geocoded_posts_map'));

		// Nearby posts argument
		add_filter('agm_google_map-shortcode-attributes_defaults', array($this, 'set_attribute_defaults'));
		add_filter('agm_google_map-shortcode-overrides_process', array($this, 'process_overrides'), 10, 2);
		add_filter('agm_google_maps-shortcode-create_tag', array($this, 'process_tag'), 10, 2);
	}

	function set_attribute_defaults ($args) {
		$args['nearby_posts'] = false;
		$args['nearby_boundaries'] = false;
		$args['nearby_posts_in_list'] = false;
		$args['nearby_within'] = 1000;
		return $args;
	}

	function process_overrides ($overrides, $args) {
		$_yes = array('yes', 'true', 'on');
		if (isset($args['nearby_posts'])) $overrides['nearby_posts'] = in_array($args['nearby_posts'], $_yes);
		if (isset($args['nearby_boundaries'])) $overrides['nearby_boundaries'] = in_array($args['nearby_boundaries'], $_yes);
		if (isset($args['nearby_posts_in_list'])) $overrides['nearby_posts_in_list'] = in_array($args['nearby_posts_in_list'], $_yes);
		if (isset($args['nearby_within'])) $overrides['nearby_within'] = (int)$args['nearby_within'];
		return $overrides;
	}

	function process_tag ($map, $overrides) {
		if (empty($overrides['nearby_posts'])) return $map;
		$within = (int)$overrides['nearby_within'] ? (int)$overrides['nearby_within'] : 1000;
		$seen = $to_add = array();

		foreach ($map['markers'] as $marker) {
			$posts = $this->_data->find_nearby_posts($marker['position'][0], $marker['position'][1], $within);
			foreach ($posts as $post) {
				if (in_array($post->ID, $seen)) continue; // Only add once
				$to_add[] = $this->_data->post_to_marker($post);
				$seen[] = $post->ID;
			}
		}
		$map['markers'] = array_merge($to_add, $map['markers']); // Reverse the order, so not to skew map default centering
		return $map;
	}

	function all_geocoded_posts_map ($args=array(), $content='') {
		$_yes = array('yes', 'true', 'on');
		$args = is_array($args) ? $args : array();

		$map = $this->_model->get_map_defaults();
		$map['defaults'] = $this->_model->get_map_defaults();
		$map['id'] = 'geocoded_posts-' . md5(microtime());
		$map['show_map'] = 1;
		$map['markers'] = $this->_data->get_all_geolocated_posts_as_markers(6);

		foreach ($args as $key => $arg) {
			if (in_array($arg, $_yes)) $args[$key] = 1;
		}
		$args['nearby_posts'] = false; // ... or the world explodes
		if (!empty($args['show_markers']) && empty($args['nearby_posts_in_list'])) {
			$args['nearby_posts_in_list'] = true; // Auto-set if not explicitly requested otherwise
		}

		$codec = new AgmMarkerReplacer;
		return $codec->create_tag($map, $args);
	}

	function load_scripts () {
		wp_enqueue_script('gwp-user', AGM_PLUGIN_URL . '/js/gwp-user.js');
	}
}

if (is_admin()) Agm_GwpAdminPages::serve();
else Agm_GwpUserPages::serve(); 