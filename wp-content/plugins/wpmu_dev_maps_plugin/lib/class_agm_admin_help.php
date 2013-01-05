<?php

class Agm_AdminHelp {

	const SETTINGS_SCREEN_ID = 'settings_page_agm_google_maps';

	private $_help;

	private static $_instance;

	private function __construct () {
		if (!class_exists('WpmuDev_ContextualHelp')) require_once(AGM_PLUGIN_BASE_DIR . '/lib/external/class_wd_contextual_help.php');
		$this->_help = new WpmuDev_ContextualHelp;
	}

	private function _get_default_tabs () {
		return array(
			array(
				'id' => 'agm_google_maps-options',
				'title' => __('Options', 'agm_google_maps'),
				'content' => '' .
					'<p>' .
						__('This is where you can set up your Google Maps default settings.', 'agm_google_maps') .
					'</p>'
			),
			array(
				'id' => 'agm_google_maps-custom_fields',
				'title' => __('Custom fields', 'agm_google_maps'),
				'content' => '' .
					'<p>' .
						__('This is where you can set up auto-creation of new Google Maps, triggered by your existing location custom fields.', 'agm_google_maps') .
					'</p>'
			),
			array(
				'id' => 'agm_google_maps-addons',
				'title' => __('Add-ons', 'agm_google_maps'),
				'content' => '' .
					'<p>' .
						__('These are the optional additions for your Google Maps. Activate or deactivate them as needed.', 'agm_google_maps') .
					'</p>'
			),

		);
	}

	private function _get_default_sidebar () {
		return '' .
			'<h4>' . __('Google Maps', 'agm_google_maps') . '</h4>' .
			'<ul>' .
				'<li><a href="http://premium.wpmudev.org/project/wordpress-google-maps-plugin/" target="_blank">' . __('Project page', 'agm_google_maps') . '</a></li>' .
				'<li><a href="http://premium.wpmudev.org/project/wordpress-google-maps-plugin/#usage" target="_blank">' . __('Installation and instructions page', 'agm_google_maps') . '</a></li>' .
				'<li><a href="http://premium.wpmudev.org/forums/tags/google-maps" target="_blank">' . __('Support forum', 'agm_google_maps') . '</a></li>' .
			'</ul>' . 
		'';
	}

	public static function get_instance () {
		if (self::$_instance instanceof Agm_AdminHelp) return self::$_instance;
		self::$_instance = new Agm_AdminHelp;
		return self::$_instance;
	}

	public static function serve () {
		$me = self::get_instance();
		$me->_add_hooks();
	}

	private function _add_hooks () {
		add_action('admin_init', array($this, 'initialize'));
	}

	public function initialize () {
		$this->_help->add_page(
			self::SETTINGS_SCREEN_ID,
			$this->_get_default_tabs(),
			$this->_get_default_sidebar()
		);
		$this->_help->initialize();
	}

	public function add_tab ($id, $title, $content) {
		$this->_help->add_tab(
			self::SETTINGS_SCREEN_ID,
			array(
				'id' => $id,
				'title' => $title,
				'content' => $content,
			)
		);
	}
}
Agm_AdminHelp::serve();