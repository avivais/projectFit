(function ($) {

function open_location_map_editor () {
	var $lat = $("#agm-latitude"),
		$lng = $("#agm-longitude"),
		$root = $("#agm-gwa-bp_map_editor"),
		$map = $("#agm-gwa-bp_map_editor-map"),
		lat = parseFloat($lat.val()),
		lng = parseFloat($lng.val()),
		center = new google.maps.LatLng(lat, lng),
		height = parseInt($(window).height() / 3)
	;
	if (!$root.length) {
		$("body").append('<div id="agm-gwa-bp_map_editor" style="display:none"><div id="agm-gwa-bp_map_editor-map" style="width:100%; height:' + height + 'px" ></div></div>');
		$root = $("#agm-gwa-bp_map_editor");
		$map = $("#agm-gwa-bp_map_editor-map");
	}
	tb_show('Edit Location', '#TB_inline?width=640&height=' + height + '&inlineId=agm-gwa-bp_map_editor');
	var map = new google.maps.Map($map.get(0), {
		"zoom": 12,
		"minZoom": 1,
		"center": center,
		"mapTypeId": google.maps.MapTypeId["ROADMAP"]
	});
	var marker = new google.maps.Marker({
		title: "Me",
        map: map, 
        icon: _agm.root_url + '/img/system/marker.png',
        draggable: true,
        clickable: false,
        position: center
    });
    google.maps.event.addListener(marker, 'dragend', function() {
		var location = marker.getPosition();
		$("#agm-latitude").val(location.lat());
		$("#agm-longitude").val(location.lng());
    	geolocate_coordinates(location.lat(), location.lng());
	});	
	return false;
}

function geolocate_coordinates (lat, lng) {
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': new google.maps.LatLng(lat, lng)}, function (results, status) {
		if (status != google.maps.GeocoderStatus.OK) return false;
		geolocate_coordinates_ui(results[0].formatted_address);
	});
}

// Right, so now we have coords - make them show nicely, and make it editable.
function geolocate_coordinates_ui (address) {
	var $root = $("#agm-gwp-location_root"),
		$address = $root.find('label[for="agm-address"]'),
		$link = $root.find("#agm-gwp-formatted_address"),
		geocoder = new google.maps.Geocoder()
	;
	if (!$link.length) {
		$address.after('<a href="#change-address" id="agm-gwp-formatted_address" />');
		$link = $root.find("#agm-gwp-formatted_address");
		$link.unbind("click").bind("click", open_location_map_editor);
	}
	$link.text(address);
}

function _get_user_location (lat, lng) {
	var $root = $("#agm-gwp-location_root"),
		$address = $root.find('label[for="agm-address"]')
	;
	$("#agm-latitude").val(lat);
	$("#agm-longitude").val(lng);
	$address.hide();
	geolocate_coordinates(lat, lng);
}

function init_bp_form () {
	var $lat = $("#agm-latitude"),
		$lng = $("#agm-longitude"),
		lat = parseFloat($lat.val()),
		lng = parseFloat($lng.val())
	;
	if (!!lat && !!lng) return _get_user_location(lat, lng);

	// No previously stored fields
	if (!!navigator.geolocation) navigator.geolocation.getCurrentPosition(function(position) {
		_get_user_location(position.coords.latitude, position.coords.longitude);
	});
	
	$.ajaxSetup({
		"beforeSend": function (jqXHR, settings) {
			if (!settings.data.match(/\baction=post_update\b/)) return false; // Scope out n/a requests
			var lat = parseFloat($("#agm-latitude").val()),
				lng = parseFloat($("#agm-longitude").val()),
				address = $("#agm-address").val()
				request = (!!lat && !!lng)
					? '&agm-latitude=' + lat + '&agm-longitude=' + lng
					: '&agm-address=' + encodeURIComponent(address)
			;
			settings.data += request;
		}
	});

	// Check for BP default theme JS... sigh
	if ($("#whats-new-options").length) { // Assume default BP theme
		$("body").append(
			$("<div id='agm-bp-height_test' />").append($("#whats-new-options").html())
		);
		var height = $("#agm-bp-height_test").height();
		$("#agm-bp-height_test").remove();
		var _int = setInterval(function () {
			var $parent = $('#whats-new-options[style*="height"]'); // Y u no use classes?
			if (!$parent.length) return false;
			if ($parent.height() <= 39) return false;
			if ($parent.height() > height) {
				clearInterval(_int);
				return false;
			}
			$parent.height(height);
		}, 500);
	}
}

function init () {
	if ($("#_wpnonce_post_update").length || $("#whats-new-post-object").length) init_bp_form();
}

$(function () {
	init();
});

$(document).bind("agm_google_maps-user-adding_marker", function (e, marker, idx, map, original) {
	if (!("disposition" in original)) return false;
	if ("activity_marker" != original.disposition) return false;
	marker._agm_disposition = "activity_type";
});

})(jQuery);