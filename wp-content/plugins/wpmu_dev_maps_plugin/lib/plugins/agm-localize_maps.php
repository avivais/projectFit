<?php
/*
Plugin Name: Forced maps localization
Description: By default, your maps will be shown according to preferred browser locale for your visitors. Enabling this add-on will show your maps in the language you select in plugin settings.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_Locale_AdminPages {
	
	private function __construct () {}
	
	public static function serve () {
		$me = new Agm_Locale_AdminPages;
		$me->_add_hooks();
	}
	
	private function _add_hooks () {
		add_action('agm_google_maps-options-plugins_options', array($this, 'register_settings'));
	}
	
	function register_settings () {
		add_settings_section('agm_google_maps_forced_l10n', __('Localization', 'agm_google_maps'), create_function('', ''), 'agm_google_maps_options_page');
		add_settings_field('agm_google_maps_l10n_languages', __('Languages', 'agm_google_maps'), array($this, 'create_languages_box'), 'agm_google_maps_options_page', 'agm_google_maps_forced_l10n');
	}
	
	function create_languages_box () {
		$language = $this->_get_options('language');
		echo '<label for="agm-locale-select_language">' . __('Select your language', 'agm_google_maps') . ':</label> ';
		echo '<select id="agm-locale-select_language" name="agm_google_maps[locale-language]">';
		echo '<option value="">' . __('Browser detect (default)', 'agm_google_maps') . '</option>';
		foreach (Agm_Locale_PublicPages::get_supported_languages() as $key => $lang) {
			$selected = ($key == $language) ? 'selected="selected"' : '';
			echo "<option value='{$key}' {$selected}>{$lang}</option>";
		}
		echo '</select>';
	}
	
	private function _get_options ($key='language') {
		$opts = apply_filters('agm_google_maps-options-locale', get_option('agm_google_maps'));
		return @$opts['locale-' . $key];
	}
}

class Agm_Locale_PublicPages {
	
	private function __construct () {}
	
	public static function serve () {
		$me = new Agm_Locale_PublicPages;
		$me->_add_hooks();
	}
	
	public static function get_supported_languages () {
		return array (
			'ar' => __('Arabic', 'agm_google_maps'), 
			'eu' => __('Basque', 'agm_google_maps'), 
			'bg' => __('Bulgarian', 'agm_google_maps'), 
			'bn' => __('Bengali', 'agm_google_maps'), 
			'ca' => __('Catalan', 'agm_google_maps'), 
			'cs' => __('Czech', 'agm_google_maps'), 
			'da' => __('Danish', 'agm_google_maps'), 
			'de' => __('German', 'agm_google_maps'), 
			'el' => __('Greek', 'agm_google_maps'), 
			'en' => __('English', 'agm_google_maps'), 
			'en-AU' => __('English (Australian)', 'agm_google_maps'), 
			'en-GB' => __('English (Great Britain)', 'agm_google_maps'),  
			'es' => __('Spanish', 'agm_google_maps'), 
			'eu' => __('Basque', 'agm_google_maps'), 
			'fa' => __('Farsi', 'agm_google_maps'), 
			'fi' => __('Finnish', 'agm_google_maps'), 
			'fil' => __('Filipino', 'agm_google_maps'), 
			'fr' => __('French', 'agm_google_maps'), 
			'gl' => __('Galician', 'agm_google_maps'), 
			'gu' => __('Gujarati', 'agm_google_maps'), 
			'hi' => __('Hindi', 'agm_google_maps'), 
			'hr' => __('Croatian', 'agm_google_maps'), 
			'hu' => __('Hungarian', 'agm_google_maps'), 
			'id' => __('Indonesian', 'agm_google_maps'), 
			'it' => __('Italian', 'agm_google_maps'), 
			'iw' => __('Hebrew', 'agm_google_maps'), 
			'ja' => __('Japanese', 'agm_google_maps'), 
			'kn' => __('Kannada', 'agm_google_maps'), 
			'ko' => __('Korean', 'agm_google_maps'), 
			'lt' => __('Lithuanian', 'agm_google_maps'), 
			'lv' => __('Latvian', 'agm_google_maps'), 
			'ml' => __('Malayalam', 'agm_google_maps'), 
			'mr' => __('Marathi', 'agm_google_maps'), 
			'nl' => __('Dutch', 'agm_google_maps'), 
			'no' => __('Norwegian', 'agm_google_maps'), 
			'pl' => __('Polish', 'agm_google_maps'), 
			'pt' => __('Portuguese', 'agm_google_maps'), 
			'pt-BR' => __('Portuguese (Brazil)', 'agm_google_maps'),
			'pt-PT' => __('Portuguese (Portugal)', 'agm_google_maps'),
			'ro' => __('Romanian', 'agm_google_maps'), 
			'ru' => __('Russian', 'agm_google_maps'), 
			'sk' => __('Slovak', 'agm_google_maps'), 
			'sl' => __('Slovenian', 'agm_google_maps'), 
			'sr' => __('Serbian', 'agm_google_maps'), 
			'sv' => __('Swedish', 'agm_google_maps'), 
			'tl' => __('Tagalog', 'agm_google_maps'), 
			'ta' => __('Tamil', 'agm_google_maps'), 
			'te' => __('Telugu', 'agm_google_maps'), 
			'th' => __('Thai', 'agm_google_maps'), 
			'tr' => __('Turkish', 'agm_google_maps'), 
			'uk' => __('Ukrainian', 'agm_google_maps'), 
			'vi' => __('Vietnamese', 'agm_google_maps'), 
			'zh-CN' => __('Chinese (simplified)', 'agm_google_maps'),
			'zh-TW' => __('Chinese (traditional)', 'agm_google_maps'),	
		);
	}

	private function _get_options ($key='language') {
		$opts = apply_filters('agm_google_maps-options-locale', get_option('agm_google_maps'));
		return @$opts['locale-' . $key];
	}
	
	private function _add_hooks () {
		add_action('agm_google_maps-add_javascript_data', array($this, 'add_language_data'));
	}
	
	function add_language_data () {
		$language = $this->_get_options('language');
		if (!in_array($language, array_keys(self::get_supported_languages()))) return false;
		printf(
			'<script type="text/javascript">if (typeof(_agmLanguage) == "undefined") _agmLanguage="%s";</script>',
			$language
		);
	}
}

if (is_admin()) Agm_Locale_AdminPages::serve();
else Agm_Locale_PublicPages::serve();
