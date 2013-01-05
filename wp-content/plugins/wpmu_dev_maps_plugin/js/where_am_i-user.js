(function ($) {

	// Check geolocation API and bail out if needed
	if (!!!navigator.geolocation) return false;

$(function () {

if (_agmWmi.add_marker) {
	$(document).bind("agm_google_maps-user-map_postprocess_markers", function (e, data, markers, callback) {
		if (_agmWmi.shortcode_only && !data.visitor_location) return false;
		navigator.geolocation.getCurrentPosition(function(position) {
			var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			var icon = _agmWmi.icon
				? (_agmWmi.icon.match(/^https?:\/\//) ? _agmWmi.icon : _agm.root_url + '/img/' + _agmWmi.icon)
				: _agm.root_url + '/img/system/marker.png'
			;
			callback(_agmWmi.marker_label, pos, '', icon)
		});
	});
} else {
	$(document).bind("agm_google_maps-user-map_initialized", function (e, map, data) {
		if (_agmWmi.shortcode_only && !data.visitor_location) return false;
		navigator.geolocation.getCurrentPosition(function(position) {
			var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			var icon = _agmWmi.icon
				? (_agmWmi.icon.match(/^https?:\/\//) ? _agmWmi.icon : _agm.root_url + '/img/' + _agmWmi.icon)
				: _agm.root_url + '/img/system/marker.png'
			;
			var marker = new google.maps.Marker({
				"title": _agmWmi.marker_label,
	            "map": map, 
	            "icon": icon,
	            "draggable": false,
	            "clickable": true,
	            "position": pos
	        });
			var info = new google.maps.InfoWindow({
			    "content": _agmWmi.marker_label,
			    "maxWidth": 200
			});
			google.maps.event.addListener(marker, 'click', function() {
				info.open(map, marker);
			});
			if (_agmWmi.auto_center) map.setCenter(pos);
		});
	});
}

});
})(jQuery);