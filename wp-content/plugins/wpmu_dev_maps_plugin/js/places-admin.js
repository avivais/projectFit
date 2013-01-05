(function ($) {
$(function () {

_places = {};

function initialize_all_markers_places (map, show, distance, types) {
	var show_places = show || $("#agm-show_places").is(":checked"),
		places_radius = distance || $("#agm-places_radius").val(),
		place_types = types && types.length ? types : false
	;
	if (!show_places) return clear_all_markers_places(map);
	
	var service = service = new google.maps.places.PlacesService(map),
		markers = map._agm_get_markers(),
		request = {
			"radius": places_radius
		}
	;
	if (place_types) request.types = place_types;
	$.each(markers, function () {
		var marker = this;
		request.location = marker.getPosition();
		service.search(request, function (response) { update_marker_places(map, marker, response); });
	});
}

function clear_all_markers_places (map) {
	var markers = map._agm_get_markers();
	$.each(markers, function () { clear_marker_places(this); });
}

function update_marker_places (map, marker, places) {
	clear_marker_places(marker);
	var pos = marker.getPosition().toString();
	$.each(places, function () {
		var place = this,
			place_icon = new google.maps.MarkerImage(
				place.icon.toString(),
				null, null, null, new google.maps.Size(32, 32)
			),
			place_marker = new google.maps.Marker({
				"title": place.name,
	            "map": map, 
	            "icon": place_icon,
	            "draggable": false,
	            "clickable": true,
	            "position": place.geometry.location
	        }),
			info = new google.maps.InfoWindow({
			    "content": '<b>' + place.name + '</b><br />' + '<p>' + place.vicinity + '</p>',
			    "maxWidth": 400
			})
		;
		google.maps.event.addListener(place_marker, 'click', function() {
			info.open(map, place_marker);
		});
		_places[pos].push(place_marker);
	});
}

function get_marker_places (marker) {
	return _places[marker.getPosition().toString()] ? _places[marker.getPosition().toString()] : [];
}

function clear_marker_places (marker) {
	places = get_marker_places(marker);
	$.each(places, function () {
		this.setMap(null);
	});
	_places[marker.getPosition().toString()] = [];
}

// ----- Hooks -----

// Add options
$(document).bind("agm_google_maps-admin-markup_created", function (e, el, data) {
	if (typeof google.maps.places !== 'object') return false;
	var show_places = false,
		places_radius = 1000
	;
	try { show_places = data.show_places ? parseInt(data.show_places) : show_places; } catch (e) { show_places = false; }
	try { places_radius = data.places_radius ? parseInt(data.places_radius) : places_radius; } catch (e) { places_radius = 1000; }
	var markup = '<fieldset id="agm-places">' +
		'<legend>Google Places</legend>' +
		'<input type="checkbox" id="agm-show_places" value="1" ' + (show_places ? 'checked="checked"' : '') + ' />' +
		' <label for="agm-show_places">Show Google Places close to my map markers</label>' +
		'<br />' +
		'<label for="agm-places_radius">Show Google Places within ' + 
			'<input type="text" size="6" id="agm-places_radius" value="' + places_radius + '" />' +
		' meters of the marker</label>' +
		'<br />' +
		'<label>Limit shown places to these types:</label><br />'
	;
	$.each(data.defaults.place_types, function (val, lbl) {
		var checked = data.place_types && data.place_types.length
			? (data.place_types.indexOf(val) > -1 ? 'checked="checked"' : '')
			: ''
		;
		markup += '<input type="checkbox" class="agm-place_type" id="agm-place_type-' + val + '" value="' + val + '" ' + checked + ' />' +
			' <label for="agm-place_type-' + val + '">' + lbl + '</label><br />';
	});
	markup += '</fieldset>';
	el.find("#agm_mh_options").append(markup);
});

// Save Places options
$(document).bind("agm_google_maps-admin-save_request", function (e, request) {
	if (typeof google.maps.places !== 'object') return false;
	request.show_places = $("#agm-show_places").is(":checked") ? 1 : 0;
	request.places_radius = $("#agm-places_radius").val();
	var place_types = [];
	$(".agm-place_type").each(function () {
		if ($(this).is(":checked")) place_types.push($(this).val());
	});
	request.place_types = place_types;
});

// Load Places
$(document).bind("agm_google_maps-admin-map_initialized", function (e, map, data) {
	if (typeof google.maps.places !== 'object') return false;
	var show = data.show_places ? parseInt(data.show_places) : false,
		distance = data.places_radius || 1000,
		place_types = data.place_types && data.place_types.length ? data.place_types : data.defaults.place_types
	;
	initialize_all_markers_places(map, show, distance, place_types);
});

// Repaint locations on options close
$(document).bind("agm_google_maps-admin-options_dialog-closed", function (e, map) {
	if (typeof google.maps.places !== 'object') return false;
	var place_types = [];
	$(".agm-place_type").each(function () {
		if ($(this).is(":checked")) place_types.push($(this).val());
	});
	initialize_all_markers_places(map, null, null, place_types);
});

// Repaint all places when adding a marker (inefficient, but easy)
$(document).bind("agm_google_maps-admin-marker_added", function (e, marker, map) {
	if (typeof google.maps.places !== 'object') return false;
	var place_types = [];
	$(".agm-place_type").each(function () {
		if ($(this).is(":checked")) place_types.push($(this).val());
	});
	initialize_all_markers_places(map, null, null, place_types);
});

// Null out places for removed marker
$(document).bind("agm_google_maps-admin-marker_removed", function (e, marker) {
	if (typeof google.maps.places !== 'object') return false;
	clear_marker_places(marker);
});

});
})(jQuery);