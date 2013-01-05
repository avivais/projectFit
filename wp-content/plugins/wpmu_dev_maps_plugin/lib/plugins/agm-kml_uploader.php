<?php
/*
Plugin Name: KML Uploader
Description: Allows you to upload your own KML files. <b>Please, make sure you activate the KML Overlay plugin first.</b>
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Kml_UploaderAdminPages {

	private function __construct () {}

	public static function serve () {
		$me = new Agm_Kml_UploaderAdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		// UI
		add_action('agm_google_maps-load_admin_scripts', array($this, 'load_scripts'));
		add_action('wp_ajax_agm_list_kml_uploads', array($this, 'json_list_kml_uploads'));

		// Uploads
		add_filter('agm_google_maps-settings_form_options', array($this, 'settings_form_options'));
		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
	}

	function load_scripts () {
		wp_enqueue_script('kml_uploads-admin', AGM_PLUGIN_URL . '/js/kml_uploads-admin.js', array('kml_overlay-admin'));
	}

	function json_list_kml_uploads () {
		$files = $this->_list_kml_files();
		$files = $files ? $files : array();

		$result = array();
		foreach ($files as $key => $val) {
			$file = basename($val);
			$url = esc_url($this->_get_kml_url($file));
			if (!$file || !$url) continue;
			$result[$file] = $url;
		}
		header('Content-type: application/json');
		echo json_encode($result);
		exit();
	}

	function settings_form_options ($opts) {
		return $opts . ' enctype="multipart/form-data"';
	}

	function register_settings () {
		if (isset($_FILES['kml'])) $this->_upload_kml_file();

		add_settings_section('agm_google_maps_kml', __('KML files', 'agm_google_maps'), create_function('', ''), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_list_kmls', __('Existing KML files', 'agm_google_maps'), array($this, 'create_kml_list_box'), 'agm_google_maps_options_page', 'agm_google_maps_kml');
		add_settings_field('agm_google_maps_upload_kml', __('Upload a KML file', 'agm_google_maps'), array($this, 'create_kml_uploads_box'), 'agm_google_maps_options_page', 'agm_google_maps_kml');
	}

	function create_kml_list_box () {
		$files = $this->_list_kml_files();
		if (!$files) {
			_e("<em>No KML files.</em>", 'agm_google_maps');
			return false;
		}
		echo "<ul>";
		foreach ($files as $file) {
			$file = basename($file);
			$url = esc_url($this->_get_kml_url($file));
			$file = esc_html($file);
			if (!$file || !$url) continue;
			echo '<li>';
			echo "<a href='{$url}'>{$file}</a>";
			echo '</li>';
		}
		echo "</ul>";
	}

	function create_kml_uploads_box () {
		echo '<input type="file" name="kml" />';
		echo '<div><small>' . __('Only files with .kml and .kmz extension are allowed.', 'agm_google_maps') . '</small></div>';
		echo '<p><input type="submit" value="' . __('Upload', 'agm_google_maps') . '" /></p>';
	}

	private function _list_kml_files () {
		$dir = $this->_get_uploads_dir();
		if (!$dir) return false;

		return glob("{$dir}/*.{kml,kmz}", GLOB_BRACE);
	}

	private function _upload_kml_file () {
		$name = strtolower(basename(preg_replace('~[^-_.a-z0-9]~i', '-', $_FILES['kml']['name'])));
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		if (!in_array($ext, array('kml', 'kmz'))) return false;

		// Get upload dir info.
		$dir = $this->_get_uploads_dir();
		if (!$dir) return false;

		if (!move_uploaded_file($_FILES["kml"]["tmp_name"], "{$dir}/{$name}")) return false;
		return true;
	}

	private function _get_uploads_dir () {
		$uploads = wp_upload_dir();
		$path = $uploads["basedir"] . '/agm-kmls';
		if (!is_dir($path)) wp_mkdir_p($path);
		if (!is_dir($path)) return false;

		return $path;
	}

	private function _get_kml_url ($file) {
		if (!$file) return false;
		$file = basename($file);
		$uploads = wp_upload_dir();
		$path = $uploads["basedir"] . '/agm-kmls';
		$url = $uploads["baseurl"] . '/agm-kmls';

		if (!is_dir($path)) return false;
		if (!file_exists("{$path}/{$file}")) return false;

		return "{$url}/{$file}";
	}
}

if (class_exists('Agm_Kml_AdminPages') && is_admin()) Agm_Kml_UploaderAdminPages::serve();