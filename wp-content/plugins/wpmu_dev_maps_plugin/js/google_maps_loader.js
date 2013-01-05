/**
 * Asynchrounously load Google Maps API. 
 */


/**
 * Global API loaded flag.
 */
var _agmMapIsLoaded = false;


/**
 * Callback - triggers loaded flag setting. 
 */
function agmInitialize () {
	_agmMapIsLoaded = true;
	if ("undefined" != typeof google.maps.Map._agm_get_markers) return true;
	google.maps.Map.prototype._agm_markers = [];
	google.maps.Map.prototype._agm_get_markers = function () { return this._agm_markers; }
	google.maps.Map.prototype._agm_clear_markers = function () { this._agm_markers = []; }
	google.maps.Map.prototype._agm_add_marker = function (mrk) { this._agm_markers.push(mrk); }
	google.maps.Map.prototype._agm_remove_marker = function (idx) { this._agm_markers.splice(idx, 1); }
}

/**
 * Handles the actual loading of Google Maps API.
 */
function loadGoogleMaps () {
	if (typeof google === 'object' && typeof google.maps === 'object') return agmInitialize(); // We're loaded and ready - albeit from a different source.
	var protocol = '',
		language = '&language=iw',
		script = document.createElement("script"),
		libs = _agm.libraries.join(",")
	;
	try { protocol = document.location.protocol; } catch (e) { protocol = 'http:'; }
	if (typeof(_agmLanguage) != "undefined") {
		try { language = '&language=' + _agmLanguage; } catch (e) { language = ''; }
	}
	script.type = "text/javascript";
	script.src = protocol + "//maps.google.com/maps/api/js?v=3&libraries=" + libs + "&sensor=false" + language + "&callback=agmInitialize";
	document.body.appendChild(script);
}

jQuery(window).load(loadGoogleMaps);
