<?php
/*
Plugin Name: Google Places support
Description: Allows you to show nearby places.
Plugin URI: http://premium.wpmudev.org/project/wordpress-google-maps-plugin
Version: 1.0
Author: Ve Bailovity (Incsub)
*/

class Agm_PlacesAdminPages {

	private function __construct () {}

	public static function serve () {
		$me = new Agm_PlacesAdminPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		// UI
		add_action('agm_google_maps-load_admin_scripts', array($this, 'load_scripts'));
		add_action('agm_google_maps-prepare_for_save-map_options', array($this, 'prepare_for_save'), 10, 2);
		add_action('agm_google_maps-prepare_map-options', array($this, 'prepare_for_load'), 10, 2);

		// Adding in map defaults
		add_action('agm_google_maps-options', array($this, 'inject_default_location_types'));
	}

	function load_scripts () {
		wp_enqueue_script('places-admin', AGM_PLUGIN_URL . '/js/places-admin.js');
	}

	function prepare_for_save ($options, $raw) {
		$options['show_places'] = isset($raw['show_places']) ? $raw['show_places'] : 0;
		$options['places_radius'] = isset($raw['places_radius']) ? $raw['places_radius'] : 1000;
		$options['place_types'] = isset($raw['place_types']) ? $raw['place_types'] : array();
		return $options;
	}

	function prepare_for_load ($options, $raw) {
		$options['show_places'] = isset($raw['show_places']) ? $raw['show_places'] : 0;
		$options['places_radius'] = isset($raw['places_radius']) ? $raw['places_radius'] : 1000;
		$options['place_types'] = isset($raw['place_types']) ? $raw['place_types'] : array();
		return $options;
	}

	function inject_default_location_types ($options) {
		$options['place_types'] = array(
			'accounting' => __('Accounting', 'agm_google_maps'),
			'airport' => __('Airport', 'agm_google_maps'),
			'amusement_park' => __('Amusement park', 'agm_google_maps'),
			'aquarium' => __('Aquarium', 'agm_google_maps'),
			'art_gallery' => __('Art gallery', 'agm_google_maps'),
			'atm' => __('ATM', 'agm_google_maps'),
			'bakery' => __('Bakery', 'agm_google_maps'),
			'bank' => __('Bank', 'agm_google_maps'),
			'bar' => __('Bar', 'agm_google_maps'),
			'beauty_salon' => __('Beauty salon', 'agm_google_maps'),
			'bicycle_store' => __('Bicycle store', 'agm_google_maps'),
			'book_store' => __('Book store', 'agm_google_maps'),
			'bowling_alley' => __('Bowling alley', 'agm_google_maps'),
			'bus_station' => __('Bus station', 'agm_google_maps'),
			'cafe' => __('Cafe', 'agm_google_maps'),
			'campground' => __('Campground', 'agm_google_maps'),
			'car_dealer' => __('Car dealer', 'agm_google_maps'),
			'car_rental' => __('Car rental', 'agm_google_maps'),
			'car_repair' => __('Car repair', 'agm_google_maps'),
			'car_wash' => __('Car wash', 'agm_google_maps'),
			'casino' => __('Casino', 'agm_google_maps'),
			'cemetery' => __('Cemetery', 'agm_google_maps'),
			'church' => __('Church', 'agm_google_maps'),
			'city_hall' => __('City hall', 'agm_google_maps'),
			'clothing_store' => __('Clothing store', 'agm_google_maps'),
			'convenience_store' => __('Convenience store', 'agm_google_maps'),
			'courthouse' => __('Courthouse', 'agm_google_maps'),
			'dentist' => __('Dentist', 'agm_google_maps'),
			'department_store' => __('Department store', 'agm_google_maps'),
			'doctor' => __('Doctor', 'agm_google_maps'),
			'electrician' => __('Electrician', 'agm_google_maps'),
			'electronics_store' => __('Electronics store', 'agm_google_maps'),
			'embassy' => __('Embassy', 'agm_google_maps'),
			'establishment' => __('Establishment', 'agm_google_maps'),
			'finance' => __('Finance', 'agm_google_maps'),
			'fire_station' => __('Fire station', 'agm_google_maps'),
			'florist' => __('Florist', 'agm_google_maps'),
			'food' => __('Food', 'agm_google_maps'),
			'funeral_home' => __('Funeral home', 'agm_google_maps'),
			'furniture_store' => __('Furniture store', 'agm_google_maps'),
			'gas_station' => __('Gas station', 'agm_google_maps'),
			'general_contractor' => __('General contractor', 'agm_google_maps'),
			'grocery_or_supermarket' => __('Grocery or supermarket', 'agm_google_maps'),
			'gym' => __('Gym', 'agm_google_maps'),
			'hair_care' => __('Hair care', 'agm_google_maps'),
			'hardware_store' => __('Hardware store', 'agm_google_maps'),
			'health' => __('Health', 'agm_google_maps'),
			'hindu_temple' => __('Hindu temple', 'agm_google_maps'),
			'home_goods_store' => __('Home goods store', 'agm_google_maps'),
			'hospital' => __('Hospital', 'agm_google_maps'),
			'insurance_agency' => __('Insurance agency', 'agm_google_maps'),
			'jewelry_store' => __('Jewelry store', 'agm_google_maps'),
			'laundry' => __('Laundry', 'agm_google_maps'),
			'lawyer' => __('Lawyer', 'agm_google_maps'),
			'library' => __('Library', 'agm_google_maps'),
			'liquor_store' => __('Liquor store', 'agm_google_maps'),
			'local_government_office' => __('Local government office', 'agm_google_maps'),
			'locksmith' => __('Locksmith', 'agm_google_maps'),
			'lodging' => __('Lodging', 'agm_google_maps'),
			'meal_delivery' => __('Meal delivery', 'agm_google_maps'),
			'meal_takeaway' => __('Meal takeaway', 'agm_google_maps'),
			'mosque' => __('Mosque', 'agm_google_maps'),
			'movie_rental' => __('Movie rental', 'agm_google_maps'),
			'movie_theater' => __('Movie theater', 'agm_google_maps'),
			'moving_company' => __('Moving company', 'agm_google_maps'),
			'museum' => __('Museum', 'agm_google_maps'),
			'night_club' => __('Night club', 'agm_google_maps'),
			'painter' => __('Painter', 'agm_google_maps'),
			'park' => __('Park', 'agm_google_maps'),
			'parking' => __('Parking', 'agm_google_maps'),
			'pet_store' => __('Pet store', 'agm_google_maps'),
			'pharmacy' => __('Pharmacy', 'agm_google_maps'),
			'physiotherapist' => __('Physiotherapist', 'agm_google_maps'),
			'place_of_worship' => __('Place of worship', 'agm_google_maps'),
			'plumber' => __('Plumber', 'agm_google_maps'),
			'police' => __('Police', 'agm_google_maps'),
			'post_office' => __('Post office', 'agm_google_maps'),
			'real_estate_agency' => __('Real estate agency', 'agm_google_maps'),
			'restaurant' => __('Restaurant', 'agm_google_maps'),
			'roofing_contractor' => __('Roofing contractor', 'agm_google_maps'),
			'rv_park' => __('RV park', 'agm_google_maps'),
			'school' => __('School', 'agm_google_maps'),
			'shoe_store' => __('Shoe store', 'agm_google_maps'),
			'shopping_mall' => __('Shopping mall', 'agm_google_maps'),
			'spa' => __('Spa', 'agm_google_maps'),
			'stadium' => __('Stadium', 'agm_google_maps'),
			'storage' => __('Storage', 'agm_google_maps'),
			'store' => __('Store', 'agm_google_maps'),
			'subway_station' => __('Subway station', 'agm_google_maps'),
			'synagogue' => __('Synagogue', 'agm_google_maps'),
			'taxi_stand' => __('Taxi stand', 'agm_google_maps'),
			'train_station' => __('Train station', 'agm_google_maps'),
			'travel_agency' => __('Travel agency', 'agm_google_maps'),
			'university' => __('University', 'agm_google_maps'),
			'veterinary_care' => __('Veterinary care', 'agm_google_maps'),
			'zoo' => __('Zoo', 'agm_google_maps'),
		);
		return $options;
	}
}


class Agm_PlacesUserPages {
	private function __construct () {}

	public static function serve () {
		$me = new Agm_PlacesUserPages;
		$me->_add_hooks();
	}

	private function _add_hooks () {
		// UI
		add_action('agm_google_maps-load_user_scripts', array($this, 'load_scripts'));
		add_action('agm_google_maps-prepare_map-options', array($this, 'prepare_for_load'), 10, 2);
	}

	function load_scripts () {
		wp_enqueue_script('places-user', AGM_PLUGIN_URL . '/js/places-user.js');
	}

	function prepare_for_load ($options, $raw) {
		$options['show_places'] = isset($raw['show_places']) ? $raw['show_places'] : 0;
		$options['places_radius'] = isset($raw['places_radius']) ? $raw['places_radius'] : 1000;
		$options['place_types'] = isset($raw['place_types']) ? $raw['place_types'] : array();
		return $options;
	}
}

function _agm_places_add_library_support ($data) {
	$data['libraries'] = $data['libraries'] ? $data['libraries'] : array();
	$data['libraries'][] = 'places';
	return $data;
}
add_filter('agm_google_maps-javascript-data_object', '_agm_places_add_library_support');

if (is_admin()) Agm_PlacesAdminPages::serve();
else Agm_PlacesUserPages::serve(); 