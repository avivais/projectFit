<?php
/*
Plugin Name: Fix and Repair
Description: Tools for repairing your Google Maps installation.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Far_FormRenderer {
	function create_fixes_section () {
		_e('Use options in this section to repair your Google Maps installation. <em>You may want to back up your WordPress database first.</em>', 'agm_google_maps');
	}
	function create_reset_options_box () {
		$please_wait = esc_attr(__("Please, wait...", 'agm_google_maps'));
		$click_here = esc_attr(__('Click here to reset all options to their defaults on this blog', 'agm_google_maps'));
		$success = esc_attr(__('Success', 'agm_google_maps'));
		$failure = esc_attr(__('Failure', 'agm_google_maps'));
		$stand_by = esc_attr(__('Your page will automatically reload in 3s', 'agm_google_maps'));
		echo '<a href="#fix_reset_options" id="agm-reset_options">' . $click_here . '</a>';
		echo '&nbsp;<span id="agm-reset_options-result"></span>';
		echo '<div><small>' . __("All individual map options will be preserved.", 'agm_google_maps') . '</small></div>';
		echo <<<EORoptJs
<script type="text/javascript">
(function ($) {
$(function () {
	$("#agm-reset_options").click(function () {
		$("#agm-reset_options").text("{$please_wait}");
		$("#agm-reset_options-result").html('');
		$.post(ajaxurl, {"action": "agm_reset_options"}, function (data) {
			var status = false;
			try {
				if (parseInt(data.status)) status = true;
			} catch (e) {}
			$("#agm-reset_options-result").html(
				(status ? "{$success}! {$stand_by}" : "{$failure}")
			);
			$("#agm-reset_options").text("{$click_here}");
			if (status) {
				setTimeout(function () {
					window.location = window.location;
				}, 3000);
			}
		});
		return false;
	});
});
})(jQuery);
</script>
EORoptJs;
	}

	function create_missing_tables_box () {
		$please_wait = esc_attr(__("Please, wait...", 'agm_google_maps'));
		$click_here = esc_attr(__('Click here to fix missing tables on this blog', 'agm_google_maps'));
		$success = esc_attr(__('Success', 'agm_google_maps'));
		$failure = esc_attr(__('Failure', 'agm_google_maps'));
		echo '<a href="#fix_missing_table" id="agm-fix_missing_table">' . $click_here . '</a>';
		echo '&nbsp;<span id="agm-fix_missing_table-result"></span>';
		echo '<div><small>' . __("Use this option if you suspect that your database table is missing", 'agm_google_maps') . '</small></div>';
		echo <<<EOFmtJs
<script type="text/javascript">
(function ($) {
$(function () {
	$("#agm-fix_missing_table").click(function () {
		$("#agm-fix_missing_table").text("{$please_wait}");
		$("#agm-fix_missing_table-result").html('');
		$.post(ajaxurl, {"action": "agm_fix_missing_table"}, function (data) {
			var status = false;
			try {
				if (parseInt(data.status)) status = true;
			} catch (e) {}
			$("#agm-fix_missing_table-result").html(
				(status ? "{$success}" : "{$failure}")
			);
			$("#agm-fix_missing_table").text("{$click_here}");
		});
		return false;
	});
});
})(jQuery);
</script>
EOFmtJs;
	}

	function create_empty_tables_box () {
		$are_you_sure = esc_attr(__('This will delete ALL maps on this blog. Are you definitely sure you want to do this?', 'agm_google_maps'));
		$please_wait = esc_attr(__("Please, wait...", 'agm_google_maps'));
		$click_here = esc_attr(__('Click here to delete ALL maps on this blog', 'agm_google_maps'));
		$success = esc_attr(__('Success', 'agm_google_maps'));
		$failure = esc_attr(__('Failure', 'agm_google_maps'));
		echo '<a href="#clear_table" id="agm-clear_table">' . $click_here . '</a>';
		echo '&nbsp;<span id="agm-clear_table-result"></span>';
		echo '<div><small>' . __("Use this option to remove ALL tables from this blog <em>(<b>Warning:</b> this option is irreversible)</em>", 'agm_google_maps') . '</small></div>';
		echo <<<EOEtJs
<script type="text/javascript">
(function ($) {
$(function () {
	$("#agm-clear_table").click(function () {
		if (!confirm("{$are_you_sure}")) return false;
		$("#agm-clear_table").text("{$please_wait}");
		$("#agm-clear_table-result").html('');
		$.post(ajaxurl, {"action": "agm_clear_table"}, function (data) {
			var status = false;
			try {
				if (parseInt(data.status)) status = true;
			} catch (e) {}
			$("#agm-clear_table-result").html(
				(status ? "{$success}" : "{$failure}")
			);
			$("#agm-clear_table").text("{$click_here}");
		});
		return false;
	});
});
})(jQuery);
</script>
EOEtJs;
	}

	function create_rebuild_maps_box () {
		$click_here = esc_attr(__('Click here to rebuild your BuddyPress members profile maps', 'agm_google_maps'));
		$description = __('Rebuild all profile maps <em>(<b>Warning:</b> this will take a while)</em>', 'agm_google_maps');
		$drop_buffer = esc_attr(__('Please hold on while the old location buffers are purged', 'agm_google_maps'));
		$processing_profiles = esc_attr(__('Processing profiles... ', 'agm_google_maps'));
		$all_done = esc_attr(__('All done!', 'agm_google_maps'));
		echo <<<EoBpRmJs
<a href="#rebuild_maps" id="agm-bp-rebuild_maps">{$click_here}</a>&nbsp;<span id="agm-bp-rebuild_maps-result"></span>
<div><small>{$description}</small></div>
<script type="text/javascript">
(function ($) {
$(function () {
	$("#agm-bp-rebuild_maps").click(function () {
		var result = $("#agm-bp-rebuild_maps-result");
		result.text("{$drop_buffer}");
		$.post(ajaxurl, {"action": "agm_bp_drop_buffered_locations"}, function (data) {
			var done = 0, total = 0, status = false;
			try { status = parseInt(data.status) } catch (e) { status = false; }
			try { total = parseInt(data.total) } catch (e) { total = false; }
			
			if (!status || !total) { // Bail out
				window.location.reload();
				return false;
			}
			
			function send_process_request () {
				if (done == total) { // We're done here
					result.text("{$all_done}");
					return false;
				}
				result.text("{$processing_profiles} " + (done+1) + "/" + total);
				$.post(ajaxurl, {"action": "agm_bp_rebuild_profile_map"}, function () {
					done++;
					send_process_request();
				});
			}
			send_process_request();
		});
		return false;
	});
});
})(jQuery);
</script>
EoBpRmJs;
	}
}

class Agm_FixAndRepair {

	public static function serve () {
		$me = new Agm_FixAndRepair;
		$me->_add_hooks();
	}

	function register_settings () {
		if (is_multisite() && !current_user_can('manage_network_options')) return false; // On multisite, only allow this to Network Admins
		$form = Agm_Far_FormRenderer;

		add_settings_section('agm_google_maps_repairs', __('Advanced: fixes and repairs', 'agm_google_maps'), array($form, 'create_fixes_section'), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_reset_options', __('Reset all options to defaults', 'agm_google_maps'), array($form, 'create_reset_options_box'), 'agm_google_maps_options_page', 'agm_google_maps_repairs');
		add_settings_field('agm_google_maps_create_table', __('Fix missing table', 'agm_google_maps'), array($form, 'create_missing_tables_box'), 'agm_google_maps_options_page', 'agm_google_maps_repairs');
		add_settings_field('agm_google_maps_empty_table', __('Clear the table', 'agm_google_maps'), array($form, 'create_empty_tables_box'), 'agm_google_maps_options_page', 'agm_google_maps_repairs');
		
		if (class_exists('Agm_Bp_Pm_UserPages')) {
			add_settings_field('agm_google_maps-bp-rebuild_maps', __('Rebuild BuddyPress profile maps', 'agm_google_maps'), array($form, 'create_rebuild_maps_box'), 'agm_google_maps_options_page', 'agm_google_maps_repairs');
		}
	}

	function json_fix_missing_table () {
		$status = false;
		if (current_user_can('manage_options')) {
			$installer = new AgmPluginInstaller;
			if (!$installer->has_database_table()) {
				$installer->create_database_table();
				$status = $installer->has_database_table();
			} else $status = true;
		}
		header('Content-type: application/json');
		echo json_encode(array(
			'status' => $status ? 1 : 0,
		));
		exit();
	}

	function json_reset_options () {
		$status = false;
		if (current_user_can('manage_options')) {
			$installer = new AgmPluginInstaller;
			$installer->set_default_options();
			$status = true;
		}
		header('Content-type: application/json');
		echo json_encode(array(
			'status' => $status ? 1 : 0,
		));
		exit();
	}

	function json_clear_table () {
		$status = false;
		if (current_user_can('manage_options')) {
			$model = new AgmMapModel();
			$status = $model->clear_table();
		}
		header('Content-type: application/json');
		echo json_encode(array(
			'status' => $status ? 1 : 0,
		));
		exit();
	}
	
	function json_bp_drop_buffered_locations () {
		if (!class_exists('Agm_Bp_Pm_UserPages')) die(-1); 
		global $wpdb;
		header('Content-type: application/json');
		$total = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->users}");
		$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key='agm-bp-profile_maps-location'");
		die(json_encode(array(
			'status' => 1,
			'total' => $total,
		)));
	}
	
	function json_bp_rebuild_profile_map () {
		if (!class_exists('Agm_Bp_Pm_UserPages')) die(-1);
		global $wpdb;
		header('Content-type: application/json');
		$sql = "SELECT DISTINCT ID FROM {$wpdb->users} WHERE ID NOT IN (SELECT DISTINCT ID FROM {$wpdb->users} as user, {$wpdb->usermeta} as meta WHERE user.ID=meta.user_id AND meta_key='agm-bp-profile_maps-location') LIMIT 1";
		$user_id = (int)$wpdb->get_var($sql);
		
		$model = new AgmMapModel;
		$opts = apply_filters('agm_google_maps-options-bp_profile_maps', get_option('agm_google_maps'));
		$address = bp_get_profile_field_data(array(
			'field' => @$opts['bp_profile_maps-address_field'],
			'user_id' => $user_id,
		));
		// Skip this guy
		if (!$address) die(json_encode(array(
			'user_id' => $user_id,
		)));
		
		$location = $model->_address_to_marker($address);
		if ($location) {
			$location['body'] = Agm_Bp_Pm_UserPages::get_location_body($user_id, $address);
		}
		update_user_meta($user_id, 'agm-bp-profile_maps-location', $location);
		die(json_encode(array(
			'user_id' => $user_id,
		)));
	}

	private function _add_hooks () {
		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
		// Fixing options AJAX handlers
		add_action('wp_ajax_agm_fix_missing_table', array($this, 'json_fix_missing_table'));
		add_action('wp_ajax_agm_reset_options', array($this, 'json_reset_options'));
		add_action('wp_ajax_agm_clear_table', array($this, 'json_clear_table'));

		add_action('wp_ajax_agm_bp_drop_buffered_locations', array($this, 'json_bp_drop_buffered_locations'));
		add_action('wp_ajax_agm_bp_rebuild_profile_map', array($this, 'json_bp_rebuild_profile_map'));
	}
}

if (is_admin) Agm_FixAndRepair::serve();