(function ($) {

$(document).bind("agm_google_maps-user-map_initialized", function (e, map, data) {
	if (!data.is_responsive) return false; // Short out

	var $map = $(map.getDiv()),
		$container = $map.parents(".agm_google_maps"),
		$parent = $container.parent(),
		center = map.getCenter(),
		total_width = $parent.width(),
		map_width = $map.width()
	;

	$(window).resize(function () {
		var width = $parent.width();
		if (data.responsive_respect_width) {
			width = (width / total_width) * map_width;
		}
		$container.width(width)
		$map.width(width);
		google.maps.event.trigger(map, 'resize');
		map.setCenter(center);
	}).trigger('resize');
});

})(jQuery);