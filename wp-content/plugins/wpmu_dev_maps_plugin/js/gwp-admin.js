(function ($) {

function _draw_centered_map (lat, lng) {
	var $root = $("#agm-gwp-location_root"),
		$address = $root.find('label[for="agm-address"]'),
		center = new google.maps.LatLng(lat, lng)
	;
	$address
		.hide()
		.after("<div id='agm-gwp-target_map' style='width:100%; height:300px' />")
	;
	var map = new google.maps.Map($("#agm-gwp-target_map").get(0), {
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
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var location = results[0].geometry.location
				marker.setPosition(location);
				$("#agm-latitude").val(location.lat());
				$("#agm-longitude").val(location.lng());
			} else alert(l10nStrings.geocoding_error);
		});
	});	
}

function _wait_for_maps () {
	if (!_agmMapIsLoaded) {
		setTimeout(_wait_for_maps, 100);
	} else {
		init();
	}
}

function init () {
	var $lat = $("#agm-latitude"),
		$lng = $("#agm-longitude")
		lat = parseFloat($lat.val()),
		lng = parseFloat($lng.val())
	;
	if (!!lat && !!lng) return _draw_centered_map(lat, lng);

	// No previously stored fields
	navigator.geolocation.getCurrentPosition(function(position) {
		_draw_centered_map(position.coords.latitude, position.coords.longitude);
	});
}

$(function () {
	if (!!!navigator.geolocation) return false;
	_wait_for_maps();
});

})(jQuery);