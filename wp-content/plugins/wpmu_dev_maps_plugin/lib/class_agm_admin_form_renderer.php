<?php

/**
 * Handles rendering of form elements for plugin Options page.
 */
class AgmAdminFormRenderer {

	function _create_small_text_box ($name, $value) {
		return "<input type='text' name='agm_google_maps[{$name}]' id='{$name}' size='3' value='{$value}' />";
	}

	function create_height_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		echo $this->_create_small_text_box('height', @$opt['height']) . 'px';
	}

	function create_width_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		echo $this->_create_small_text_box('width', @$opt['width']) . 'px';
	}

	function create_image_limit_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$limit = (isset($opt['image_limit'])) ? $opt['image_limit'] : 10;
		echo $this->_create_small_text_box('image_limit', $limit);
	}

	function  create_map_type_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$items = array(
			'ROADMAP' => __('ROADMAP', 'agm_google_maps'),
			'SATELLITE' => __('SATELLITE', 'agm_google_maps'),
			'HYBRID' => __('HYBRID', 'agm_google_maps'),
			'TERRAIN' => __('TERRAIN', 'agm_google_maps'),
		);
		echo "<select id='map_type' name='agm_google_maps[map_type]'>";
		foreach($items as $item=>$lbl) {
			$selected = (@$opt['map_type'] == $item) ? 'selected="selected"' : '';
			echo "<option value='{$item}' {$selected}>{$lbl}</option>";
		}
		echo "</select>";
	}
	function  create_map_zoom_box () {
		$items = array(
			'1' => __('Earth', 'agm_google_maps'),
			'3' => __('Continent', 'agm_google_maps'),
			'5' => __('Region', 'agm_google_maps'),
			'7' => __('Nearby Cities', 'agm_google_maps'),
			'12' => __('City Plan', 'agm_google_maps'),
			'15' => __('Details', 'agm_google_maps'),
		);
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$zoom = !empty($opt['zoom']) && is_numeric($opt['zoom']) ? (int)$opt['zoom'] : 1;
		$is_advanced = (bool)(empty($opt['zoom']) || !in_array($zoom, array_keys($items)));

		$basic_visibility = $is_advanced ? 'style="display:none"' : '';
		$basic_disabled = $is_advanced ? 'disabled="disabled"' : '';
		$advanced_visibility = $is_advanced ? '' : 'style="display:none"';
		$advanced_disabled = $is_advanced ? '' : 'disabled="disabled"';

		// Basic
		echo "<div id='agm-zoom-basic-container' {$basic_visibility}>";
		echo "<select id='zoom' name='agm_google_maps[zoom]' {$basic_disabled}>";
		foreach($items as $item=>$label) {
			$selected = ($zoom == $item) ? 'selected="selected"' : '';
			echo "<option value='{$item}' {$selected}>{$label}</option>";
		}
		echo "</select>";
		echo '&nbsp;<a href="#agm-advanced_zoom" id="agm-advanced_zoom-toggler">' . __('Advanced', 'agm_google_maps') . '</a>';
		_e('<div>Please note, these titles are only approximations, but generally fit the description.</div>', 'agm_google_maps');
		echo "</div>";
		
		// Advanced
		echo "<div id='agm-zoom-advanced-container' {$advanced_visibility}>";
		echo "<input type='text' size='2' name='agm_google_maps[zoom]' value='{$zoom}' id='agm-zoom-advanced' {$advanced_disabled} />";
		echo '&nbsp;<a href="#agm-advanced_zoom" id="agm-basic_zoom-toggler">' . __('Basic mode', 'agm_google_maps') . '</a>';
		_e('<div>Please input the numeric zoom value.</div>', 'agm_google_maps');
		echo '</div>';

		// Toggling JS
		echo <<<EOZoomModeTogglingJS
<script type="text/javascript">
(function ($) {
$("#agm-advanced_zoom-toggler").on("click", function () {
	$("#agm-zoom-basic-container")
		.find("select").attr("disabled", true).end()
		.hide()
	;
	$("#agm-zoom-advanced-container")
		.find("#agm-zoom-advanced").attr("disabled", false).end()
		.show()
	;
	return false;
});
$("#agm-basic_zoom-toggler").on("click", function () {
	$("#agm-zoom-advanced-container")
		.find("#agm-zoom-advanced").attr("disabled", true).end()
		.hide()
	;
	$("#agm-zoom-basic-container")
		.find("select").attr("disabled", false).end()
		.show()
	;
	return false;
});
})(jQuery);
</script>
EOZoomModeTogglingJS;
	}

	function  create_map_units_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$items = array(
			'METRIC' => __('Metric', 'agm_google_maps'),
			'IMPERIAL' => __('Imperial', 'agm_google_maps'),
		);
		echo "<select id='zoom' name='agm_google_maps[units]'>";
		foreach($items as $item=>$label) {
			$selected = (@$opt['units'] == $item) ? 'selected="selected"' : '';
			echo "<option value='{$item}' {$selected}>{$label}</option>";
		}
		echo "</select>";
		_e('<div>These units will be used to express distances for directions</div>', 'agm_google_maps');
	}

	function  create_image_size_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$items = array(
			'small' => __('Small', 'agm_google_maps'),
			'medium' => __('Medium', 'agm_google_maps'),
			'thumbnail' => __('Thumbnail', 'agm_google_maps'),
			'square' => __('Square', 'agm_google_maps'),
			'mini_square' => __('Mini Square', 'agm_google_maps'),
		);
		echo "<select id='image_size' name='agm_google_maps[image_size]'>";
		foreach($items as $item=>$lbl) {
			$selected = (@$opt['image_size'] == $item) ? 'selected="selected"' : '';
			echo "<option value='{$item}' {$selected}>{$lbl}</option>";
		}
		echo "</select>";
	}

	function create_alignment_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$pos = @$opt['map_alignment'];
		echo
			'<input type="radio" id="map_alignment_left" name="agm_google_maps[map_alignment]" value="left" ' . (('left' == $pos) ? 'checked="checked"' : '') . '  />' .
			'<label for="map_alignment_left">' . '<img src="' . AGM_PLUGIN_URL . '/img/system/left.png" />' . __('Left', 'agm_google_maps') . '</label><br/>'
		;
		echo
			'<input type="radio" id="map_alignment_center" name="agm_google_maps[map_alignment]" value="center" ' . (('center' == $pos) ? 'checked="checked"' : '') . '  />' .
			'<label for="map_alignment_center">' . '<img src="' . AGM_PLUGIN_URL . '/img/system/center.png" />' . __('Center', 'agm_google_maps') . '</label><br/>'
		;
		echo
			'<input type="radio" id="map_alignment_right" name="agm_google_maps[map_alignment]" value="right" ' . (('right' == $pos) ? 'checked="checked"' : '') . '  />' .
			'<label for="map_alignment_right">' . '<img src="' . AGM_PLUGIN_URL . '/img/system/right.png" />' . __('Right', 'agm_google_maps') . '</label><br/>'
		;
	}

	function create_custom_css_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$css = @$opt['additional_css'];
		echo "<textarea name='agm_google_maps[additional_css]' class='widefat' rows='4' cols='32'>{$css}</textarea>";
		_e('<p>You can use this box to add some quick style changes, to better blend maps appearance with your themes.</p>', 'agm_google_maps');
		_e('<p>You may want to set styles for some of these selectors: <code>.agm_mh_info_title</code>, <code>.agm_mh_info_body</code>, <code>a.agm_mh_marker_item_directions</code>, <code>.agm_mh_marker_list</code>, <code>.agm_mh_marker_item</code>, <code>.agm_mh_marker_item_content</code></p>', 'agm_google_maps');
	}

	function _create_cfyn_box ($name, $value) {
		return '<input type="radio" name="agm_google_maps[custom_fields_options][' . $name . ']" id="agm_cfyn_' . $name . '-yes" value="1" ' . ((1 == $value) ? 'checked="checked"' : '') . ' /> <label for="agm_cfyn_' . $name . '-yes">' . __("Yes", 'agm_google_maps') . '</label>' .
			'&nbsp;' .
			'<input type="radio" name="agm_google_maps[custom_fields_options][' . $name . ']" id="agm_cfyn_' . $name . '-no" value="0" ' . ((0 == $value) ? 'checked="checked"' : '') . ' /> <label for="agm_cfyn_' . $name . '-no">' . __("No", 'agm_google_maps') . '</label>' .
		'';
	}

	function create_snapping_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$use = isset($opt['snapping']) ? $opt['snapping'] : 1;
		echo '<input type="radio" name="agm_google_maps[snapping]" id="agm_snapping-yes" value="1" ' . ($use ? 'checked="checked"' : '') . ' /> <label for="agm_snapping-yes">' . __("Yes", 'agm_google_maps') . '</label>' .
			'&nbsp;' .
			'<input type="radio" name="agm_google_maps[snapping]" id="agm_snapping-no" value="0" ' . ($use ? '' : 'checked="checked"') . ' /> <label for="agm_snapping-no">' . __("No", 'agm_google_maps') . '</label>' .
		'';
	}

	function create_use_custom_fields_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$use = @$opt['use_custom_fields'];
		echo '<input type="radio" name="agm_google_maps[use_custom_fields]" id="agm_use_custom_fields-yes" value="1" ' . ($use ? 'checked="checked"' : '') . ' /> <label for="agm_use_custom_fields-yes">' . __("Yes", 'agm_google_maps') . '</label>' .
			'&nbsp;' .
			'<input type="radio" name="agm_google_maps[use_custom_fields]" id="agm_use_custom_fields-no" value="0" ' . ($use ? '' : 'checked="checked"') . ' /> <label for="agm_use_custom_fields-no">' . __("No", 'agm_google_maps') . '</label>' .
		'';
	}
	function create_custom_fields_map_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$lat_field = @$opt['custom_fields_map']['latitude_field'];
		$lon_field = @$opt['custom_fields_map']['longitude_field'];
		$add_field = @$opt['custom_fields_map']['address_field'];

		echo '<div><b>' . __('My posts have latitude/longitude fields', 'agm_google_maps') . '</b></div>';
		echo __("Latitude field name:", 'agm_google_maps') . ' <input type="text" name="agm_google_maps[custom_fields_map][latitude_field]" size="12" maxisize="32" value="' . $lat_field . '" />';
		echo '<br />';
		echo __("Longitude field name:", 'agm_google_maps') . ' <input type="text" name="agm_google_maps[custom_fields_map][longitude_field]" size="12" maxisize="32" value="' . $lon_field . '" />';

		echo '<div><b>' . __('My posts have an address field', 'agm_google_maps') . '</b></div>';
		echo __("Address field name:", 'agm_google_maps') . ' <input type="text" name="agm_google_maps[custom_fields_map][address_field]" size="12" maxisize="32" value="' . $add_field . '" />';

		$discard = @$opt['custom_fields_map']['discard_old'] ? 'checked="checked"' : '';
		echo '<br />';
		echo '<input type="hidden" name="agm_google_maps[custom_fields_map][discard_old]" value="" />';
		echo '<input type="checkbox" id="agm-custom_fields-discard_old" name="agm_google_maps[custom_fields_map][discard_old]" value="1" ' . $discard . ' />';
		echo '&nbsp;';
		echo '<label for="agm-custom_fields-discard_old">' . __('Discard old map when my custom fields value change', 'agm_google_maps') . '</label>';
	}
	function create_custom_fields_options_box () {
		$opt = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$opt = @$opt['custom_fields_options'];
		echo "<div><small>" . __("(A new map will be automatically created, using the defaults you specified above)", 'agm_google_maps') . "</small></div>";
		echo __("Associate the new map to post:", 'agm_google_maps') . ' ' . $this->_create_cfyn_box('associate_map', @$opt['associate_map']) . '<br />';
		echo __("Automatically show the map:", 'agm_google_maps') . ' ' . $this->_create_cfyn_box('autoshow_map', @$opt['autoshow_map']) . '<br />';

		$positions = array (
			'top' => 'Above',
			'bottom' => 'Below',
		);
		$select = '<select name="agm_google_maps[custom_fields_options][map_position]">';
		foreach ($positions as $key=>$lbl) {
			$select .= "<option value='{$key}' " . (($key == @$opt['map_position']) ? 'selected="selected"' : '') . ">" . __($lbl, 'agm_google_maps') . '</option>';
		}
		$select .= '</select>';

		printf (
			__("If previous option is set to \"Yes\", the new map will be shown %s the post body", 'agm_google_maps'),
			$select
		);
	}

	function create_plugins_box () {
		$all = AgmPluginsHandler::get_all_plugins();
		$active = AgmPluginsHandler::get_active_plugins();
		$sections = array('thead', 'tfoot');

		echo "<table class='widefat'>";
		foreach ($sections as $section) {
			echo "<{$section}>";
			echo '<tr>';
			echo '<th width="30%">' . __('Add-on name', 'agm_google_maps') . '</th>';
			echo '<th>' . __('Add-on description', 'agm_google_maps') . '</th>';
			echo '</tr>';
			echo "</{$section}>";
		}
		echo "<tbody>";
		foreach ($all as $plugin) {
			$plugin_data = AgmPluginsHandler::get_plugin_info($plugin);
			if (!@$plugin_data['Name']) continue; // Require the name
			$is_active = in_array($plugin, $active);
			echo "<tr>";
			echo "<td width='30%'>";
			echo '<b>' . $plugin_data['Name'] . '</b>';
			echo "<br />";
			echo ($is_active
				?
				'<a href="#deactivate" class="agm_deactivate_plugin" agm:plugin_id="' . esc_attr($plugin) . '">' . __('Deactivate', 'agm_google_maps') . '</a>'
				:
				'<a href="#activate" class="agm_activate_plugin" agm:plugin_id="' . esc_attr($plugin) . '">' . __('Activate', 'agm_google_maps') . '</a>'
			);
			echo "</td>";
			echo '<td>' .
				$plugin_data['Description'] .
				'<br />' .
				sprintf(__('Version %s', 'agm_google_maps'), $plugin_data['Version']) .
				'&nbsp;|&nbsp;' .
				sprintf(__('by %s', 'agm_google_maps'), '<a href="' . $plugin_data['Plugin URI'] . '">' . $plugin_data['Author'] . '</a>') .
			'</td>';
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";

		echo <<<EOAgmPluginJs
<script type="text/javascript">
(function ($) {
$(function () {
	$(".agm_activate_plugin").click(function () {
		var me = $(this);
		var plugin_id = me.attr("agm:plugin_id");
		$.post(ajaxurl, {"action": "agm_activate_plugin", "plugin": plugin_id}, function (data) {
			window.location = window.location;
		});
		return false;
	});
	$(".agm_deactivate_plugin").click(function () {
		var me = $(this);
		var plugin_id = me.attr("agm:plugin_id");
		$.post(ajaxurl, {"action": "agm_deactivate_plugin", "plugin": plugin_id}, function (data) {
			window.location = window.location;
		});
		return false;
	});
});
})(jQuery);
</script>
EOAgmPluginJs;
	}
}
