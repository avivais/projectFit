<?php
/*
Plugin Name: BuddyPress profile maps
Description: Automatically creates a Map for BuddyPress profiles from a profile address field (if you don't already have such a field, you will have to create one). <br /><b>Requires BuddyPress with extended profiles enabled</b>.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

/**
 * Admin pages handler.
 */
class Agm_Bp_Pm_AdminPages {

	private $_db;
	private $_data;

	private function __construct () {
		global $wpdb;
		$this->_db = $wpdb;
	}

	public static function serve () {
		$me = new Agm_Bp_Pm_AdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
	}

	function register_settings () {
		add_settings_section('agm_google_bp_profile_fields', __('BuddyPress profile fields support', 'agm_google_maps'), array($this, 'create_dependencies_box'), 'agm_google_maps_options_page');
		if (!defined('BP_VERSION')) {
			return false;
		}
		if (!function_exists('xprofile_get_field_id_from_name')) {
			return false;
		}
		add_settings_field('agm_google_maps_bp_profile_address', __('Address profile field', 'agm_google_maps'), array($this, 'create_address_field_mapping_box'), 'agm_google_maps_options_page', 'agm_google_bp_profile_fields');
		add_settings_field('agm_google_maps_bp_profile_show_in_profile', __('Show map in user profile', 'agm_google_maps'), array($this, 'create_show_in_profile_box'), 'agm_google_maps_options_page', 'agm_google_bp_profile_fields');
		add_settings_field('agm_google_maps_bp_profile_show_in_members_list', __('Show map in members list', 'agm_google_maps'), array($this, 'create_show_in_members_list_box'), 'agm_google_maps_options_page', 'agm_google_bp_profile_fields');
		add_settings_field('agm_google_maps_bp_profile_avatars_as_markers', __('Use avatars as map markers', 'agm_google_maps'), array($this, 'create_avatars_as_markers_box'), 'agm_google_maps_options_page', 'agm_google_bp_profile_fields');
	}

	function create_dependencies_box () {
		if (!defined('BP_VERSION') || !function_exists('xprofile_get_field_id_from_name')) {
			echo '<p>' . __('You need BuddyPress plugin with extended profiles enabled for this to work', 'agm_google_maps') . '</p>';
		}
	}

	function create_address_field_mapping_box () {
		$fields = $this->_db->get_results("SELECT id, name FROM {$this->_db->base_prefix}bp_xprofile_fields", ARRAY_A);
		$fields = $fields ? $fields : array();
		if (!$fields) return false;

		$address = $this->_get_options('address_field');

		echo '<label for="agm-bp_profile_maps-address_field">' . __('This profile field holds the address of my users:', 'agm_google_maps') . '</label> ';
		echo '<select name="agm_google_maps[bp_profile_maps-address_field]" id="agm-bp_profile_maps-address_field">';
		echo '<option value="">' . __('Please, select a field', 'agm_google_maps') . '&nbsp;</option>';
		foreach ($fields as $field) {
			$selected = ($field['id'] == $address) ? 'selected="selected"' : '';
			echo '<option value="' . (int)$field['id'] . '" ' . $selected . '>' . esc_html($field['name']) . '&nbsp;</option>';
		}
		echo '</select>';
	}

	function create_show_in_profile_box () {
		$show_in_profile = $this->_get_options('show_in_profile');
		$values = array(
			'' => __('Do not show in profile', 'agm_google_maps'),
			'before' => __('Show map before profile fields', 'agm_google_maps'),
			'after' => __('Show map after profile fields', 'agm_google_maps'),
		);
		foreach ($values as $key=>$val) {
			$checked = ($show_in_profile == $key) ? 'checked="checked"' : '';
			echo '' .
				"<input type='radio' name='agm_google_maps[bp_profile_maps-show_in_profile]' value='{$key}' id='agm-bp_profile_maps-show_in_profile-{$key}' {$checked} /> " .
				"<label for='agm-bp_profile_maps-show_in_profile-{$key}'>{$val}</label>" .
			'<br />';
		}
	}

	function create_show_in_members_list_box () {
		$show_in_members_list = $this->_get_options('show_in_members_list');
		$values = array(
			'' => __('Do not show in members list', 'agm_google_maps'),
			'before' => __('Show map before members list', 'agm_google_maps'),
			'after' => __('Show map after members list', 'agm_google_maps'),
		);
		foreach ($values as $key=>$val) {
			$checked = ($show_in_members_list == $key) ? 'checked="checked"' : '';
			echo '' .
				"<input type='radio' name='agm_google_maps[bp_profile_maps-show_in_members_list]' value='{$key}' id='agm-bp_profile_maps-show_in_members_list-{$key}' {$checked} /> " .
				"<label for='agm-bp_profile_maps-show_in_members_list-{$key}'>{$val}</label>" .
			'<br />';
		}
	}

	function create_avatars_as_markers_box () {
		$avatars_as_markers = $this->_get_options('avatars_as_markers');
		$values = array(
			'' => __('No', 'agm_google_maps'),
			'1' => __('Yes', 'agm_google_maps'),
		);
		foreach ($values as $key=>$val) {
			$checked = ($avatars_as_markers == $key) ? 'checked="checked"' : '';
			echo '' .
				"<input type='radio' name='agm_google_maps[bp_profile_maps-avatars_as_markers]' value='{$key}' id='agm-bp_profile_maps-avatars_as_markers-{$key}' {$checked} /> " .
				"<label for='agm-bp_profile_maps-avatars_as_markers-{$key}'>{$val}</label>" .
			'<br />';
		}
	}

	private function _get_options ($key) {
		$opts = apply_filters('agm_google_maps-options-bp_profile_maps', get_option('agm_google_maps'));
		return @$opts['bp_profile_maps-' . $key];
	}

}

/**
 * Public pages handler.
 */
class Agm_Bp_Pm_UserPages {

	private $_model;

	public function __construct () {
		$this->_model = new AgmMapModel;
	}

	public static function serve () {
		$me = new Agm_Bp_Pm_UserPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		// Important
		add_action('xprofile_data_after_save', array($this, 'remap_user_location'));

		// Cosmetics
		$positions = array('before', 'after');

		$show_in_profile = $this->_get_options('show_in_profile');
		if ($show_in_profile && in_array($show_in_profile, $positions)) {
			add_action("bp_{$show_in_profile}_profile_loop_content", array($this, 'show_current_user_on_map'));
		}
		$show_in_members_list = $this->_get_options('show_in_members_list');
		if ($show_in_members_list && in_array($show_in_members_list, $positions)) {
			add_action("bp_{$show_in_members_list}_directory_members_list", array($this, 'show_all_users_on_map'));
		}

		add_shortcode('agm_all_profiles_map', array($this, 'handle_all_profiles_shortcode'));
	}

	/**
	 * Handle the profiles shortcode.
	 */
	function handle_all_profiles_shortcode ($atts, $content='') {
		$limit = $atts && isset($atts['limit']) ? (int)$atts['limit'] : false;
		return agm_bp_profiles_map($atts, $limit);
	}

	/**
	 * Re-map user location on field save.
	 */
	function remap_user_location ($xfield) {
		if (!$xfield) return false;
		$address_field = $this->_get_options('address_field');
		if ($xfield->field_id != $address_field) return false;

		// Reset location mapping
		update_user_meta($xfield->user_id, 'agm-bp-profile_maps-location', ''); // Clear user location
		$this->_get_member_location($xfield->user_id); // Auto-populate user location
	}

	/**
	 * Shows current users address on a map.
	 */
	function show_current_user_on_map () {
		if (!function_exists('xprofile_get_field_id_from_name')) return false;
		global $bp;
		$user_id = $bp->displayed_user->id;

		$address = $this->_get_user_address($user_id);
		if (!$address) return false;

		$location = $this->_address_to_location($user_id, $address);
		if (!$location) return false;

		echo '<div id="agm-bp-profile_map">' . $this->_create_map(array($location)) . '</div>';
	}

	/**
	 * Shows all displayed users on a map.
	 */
	function show_all_users_on_map () {
		$member_ids = array();
		$limit = apply_filters('agm_google_maps-bp_profile_map-user_limit', AGM_BP_PROFILE_MAP_USER_LIMIT);
		$overrides = apply_filters('agm_google_maps-bp_profile_map-all_users_overrides', array());

		// Get member ids
		if (bp_has_members(array('per_page' => $limit))) while (bp_members()) {
			bp_the_member();
			$member_ids[] = bp_get_member_user_id();
		}
		bp_rewind_members();

		echo $this->show_users_on_map($member_ids, $overrides);
	}

	/**
	 * Creates the actual map markup from an array of user IDs.
	 */
	function show_users_on_map ($member_ids, $overrides=array()) {
		// Get members' locations
		$markers = array();
		foreach ($member_ids as $member_id) {
			$location = $this->_get_member_location($member_id);
			if ($location) {
				if ($this->_get_options('avatars_as_markers')) $location['icon'] = $this->_get_member_avatar($member_id, false);
				$markers[] = $location;
			}
		}
		if (!$markers) return false;

		return '<div id="agm-bp-profiles_map">' . $this->_create_map($markers, false, $overrides) . '</div>';
	}

	/**
	 * Creates a map from a list of markers.
	 */
	private function _create_map ($markers, $id=false, $overrides=array()) {
		if (!$markers) return false;
		$id = $id ? $id : md5(time() . rand());

		$map = $this->_model->get_map_defaults();
		$map['defaults'] = $this->_model->get_map_defaults();
		$map['id'] = $id;
		$map['show_map'] = 1;
		$map['show_markers'] = 0;
		$map['markers'] = $markers;

		$codec = new AgmMarkerReplacer;
		return $codec->create_tag($map, $overrides);
	}

	private function _get_options ($key) {
		$opts = apply_filters('agm_google_maps-options-bp_profile_maps', get_option('agm_google_maps'));
		return @$opts['bp_profile_maps-' . $key];
	}

	/**
	 * Maps a user by ID to a map marker.
	 */
	private function _get_member_location ($user_id) {
		$address = $this->_get_user_address($user_id);
		if (!$address) return false;

		$location = $this->_address_to_location($user_id, $address);
		return $location ? $location : false;
	}

	/**
	 * Maps users ID to actual address by querying
	 * the address xprofile field.
	 */
	private function _get_user_address ($user_id) {
		if (!function_exists('bp_get_profile_field_data')) return false;

		$address_field = $this->_get_options('address_field');
		if (!$address_field) return false;

		$address = bp_get_profile_field_data(array(
			'field' => $address_field,
			'user_id' => $user_id,
		));
		// Allows using multiple Xprofile fields for address construction.
		$address = apply_filters('agm_google_maps-bp_profile_map-user_address', $address, $user_id);
		return $address ? $address : false;
	}

	/**
	 * Maps address to a map marker location.
	 * Caches data in user meta table to save time on future requests.
	 */
	private function _address_to_location ($user_id, $address) {
		$location = get_user_meta($user_id, 'agm-bp-profile_maps-location', true);
		if ($location) return $location;

		// We still don't have location for this guy.
		// Lets geotag him
		$location = $this->_model->_address_to_marker($address);
		if ($location) {
			$location['body'] = $this->get_location_body($user_id, $address);
		}
		update_user_meta($user_id, 'agm-bp-profile_maps-location', $location);
		return $location;
	}

	/**
	 * Gets member avatar.
	 */
	private function _get_member_avatar ($user_id, $as_html=true, $size='icon') {
		$width = $height = 32;
		switch ($size) {
			case 'medium':
				$width = $height = 48;
				break;
			case 'large':
				$width = $height = 64;
				break;
		}
		return bp_core_fetch_avatar(array(
			'object' => 'user',
			'item_id' => $user_id,
			'width' => $width,
			'height' => $height,
			'html' => $as_html,
		));
	}

	/**
	 * Creates map marker body.
	 * Used to cache map markers as user meta.
	 */
	public static function get_location_body ($user_id, $address) {
		$name = bp_core_get_user_displayname($user_id);
		$url = bp_core_get_user_domain($user_id);
		return apply_filters('agm_google_maps-bp_profile_map-location_markup', "
<div>
	<p class='agm-bp-profiles_map-user_link-container'><a class='agm-bp-profiles_map-user_link' href='{$url}' title='{$name}'>{$name}</a></p>
	<p class='agm-bp-profiles_map-user_address'>{$address}</p>
</div>
		");
	}

}

/* ----- Template tags ----- */

/**
 * Show all users on one large map.
 */
function agm_bp_profiles_map ($overrides=array(), $limit=false) {
	global $wpdb;
	$limit = (int)$limit ? (int)$limit : AGM_BP_PROFILE_MAP_USER_LIMIT;
	$limit = apply_filters('agm_google_maps-bp_profile_map-user_limit', $limit);
	$handler = new Agm_Bp_Pm_UserPages;
	$user_ids = $wpdb->get_col("SELECT ID from {$wpdb->users} LIMIT {$limit}");
	return $handler->show_users_on_map($user_ids, $overrides);
}

// Set initial user limit to 1k. Overridable in wp-config.php
if (!defined('AGM_BP_PROFILE_MAP_USER_LIMIT')) define('AGM_BP_PROFILE_MAP_USER_LIMIT', 1000, true);

// Allow simple-case address overrides in a define. Overridable in wp-config.php
if (defined('AGM_BP_PROFILE_MAP_USE_ADDRESS_FIELDS') && AGM_BP_PROFILE_MAP_USE_ADDRESS_FIELDS) {
	function agm_bp_profiles_map_address_override ($old_address, $user_id) {
		$src = explode(',', AGM_BP_PROFILE_MAP_USE_ADDRESS_FIELDS);
		if (!$src) return $old_address;

		$data = array();
		foreach ($src as $val) {
			$data[] = bp_get_profile_field_data(array(
				'field' => trim($val), // Field name or ID
				'user_id' => $user_id,
			));
		}
		$address = trim(join(", ", array_filter($data)));
		return $address ? $address : $old_address;
	}
	add_filter("agm_google_maps-bp_profile_map-user_address", "agm_bp_profiles_map_address_override", 10, 2);
}

if (is_admin()) Agm_Bp_Pm_AdminPages::serve();
else Agm_Bp_Pm_UserPages::serve();