(function ($) {

$(document).bind("agm_google_maps-user-map_initialized", function (e, map, data, markers) {
	if (!data.hide_map_markers) return false;
	$.each(markers, function () {
		this.setVisible(false);
	});
});

})(jQuery);