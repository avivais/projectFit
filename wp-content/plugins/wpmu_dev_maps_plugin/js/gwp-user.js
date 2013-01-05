(function ($) {

$(document).bind("agm_google_maps-user-adding_marker", function (e, marker, idx, map, original) {
	if (!("disposition" in original)) return false;
	if ("post_marker" != original.disposition) return false;
	marker._agm_disposition = "post_type";
});

$(document).bind("agm_google_maps-user-map_initialized", function (e, map, data) {
	// Short-circuit from marker iteration if nothing to do
	if (data.nearby_posts_in_list && !data.nearby_boundaries) return false;

	var markers = map._agm_get_markers();
	$.each(markers, function (idx, marker) {
		if ("_agm_disposition" in marker) {
			if (!data.nearby_posts_in_list) {
				$('.agm_mh_marker_list a[href="#agm_mh_marker-' + idx + '"]')
					.parents("li").remove()
				;
			}
			return true;
		}
		if (!data.nearby_boundaries) return true;
		var circle = new google.maps.Circle({
			"map": map,
			"center": marker.getPosition(),
			"radius": data.nearby_within,
			"strokeWeight": 2,
			"strokeColor": "#000",
			"strokeOpacity": .4,
			"fillColor": "#000",
			"fillOpacity": .1
		});
	});
});
})(jQuery);