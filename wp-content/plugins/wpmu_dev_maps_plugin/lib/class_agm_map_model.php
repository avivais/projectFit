<?php

/**
 * Mediates all database interactions.
 *
 * This is where all the map data is saved and loaded.
 */
class AgmMapModel {

	/**
	 * Name of the table where map data is located.
	 * No table prefix.
	 *
	 * @access private
	 */
	var $_table_name = 'agm_maps';

	/**
	 * PHP4 compatibility constructor.
	 */
	function AgmMapModel () {
		$this->__construct();
	}

	function __construct () {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Returns the name of maps database table.
	 *
	 * @access public
	 * @return string Name of the table with prefix.
	 */
	function get_table_name () {
		return $this->wpdb->prefix . $this->_table_name;
	}

	function clear_table () {
		$table = $this->get_table_name();
		$this->wpdb->query("DELETE FROM {$table}");
		return (0 === (int)$this->wpdb->get_var("SELECT COUNT(*) FROM {$table}"));
	}

	/**
	 * Fetches maps associated with current WP posts.
	 *
	 * @return mixed Maps array on success, false on failure
	 */
	function get_current_maps () {
		global $wp_query;
		$table = $this->get_table_name();
		$posts = $wp_query->get_posts();
		$where_string = $this->prepare_query_string($posts);
		if (!$where_string) return false;
		$maps = $this->wpdb->get_results("SELECT * FROM {$table} {$where_string}", ARRAY_A);
		if (is_array($maps)) foreach ($maps as $k=>$v) {
			$maps[$k] = $this->prepare_map($v);
		}
		return $maps;
	}

	/**
	 * Fetches maps associated with current WP post - singular one, even on archive pages.
	 *
	 * @return mixed Maps array on success, false on failure
	 */
	function get_current_post_maps () {
		global $post;
		$table = $this->get_table_name();
		$posts = array($post);
		$where_string = $this->prepare_query_string($posts);
		if (!$where_string) return false;
		$maps = $this->wpdb->get_results("SELECT * FROM {$table} {$where_string}", ARRAY_A);
		if (is_array($maps)) foreach ($maps as $k=>$v) {
			$maps[$k] = $this->prepare_map($v);
		}
		return $maps;
	}

	/**
	 * Fetches all maps associated with any posts.
	 *
	 * @return mixed Maps array on success, false on failure
	 */
	function get_all_posts_maps () {
		$table = $this->get_table_name();
		$maps = $this->wpdb->get_results("SELECT * FROM {$table} WHERE post_ids <> 'a:0:{}'", ARRAY_A);
		if (is_array($maps)) foreach ($maps as $k=>$v) {
			$maps[$k] = $this->prepare_map($v);
		}
		return $maps;
	}

	/**
	 * Fetches all maps.
	 *
	 * @return mixed Maps array on success, false on failure
	 */
	function get_all_maps () {
		$table = $this->get_table_name();
		$maps = $this->wpdb->get_results("SELECT * FROM {$table}", ARRAY_A);
		if (is_array($maps)) foreach ($maps as $k=>$v) {
			$maps[$k] = $this->prepare_map($v);
		}
		return $maps;
	}

	/**
	 * Fetches a random map.
	 *
	 * @return mixed Map array on success, false on failure
	 */
	function get_random_map () {
		$table = $this->get_table_name();
		$map = $this->wpdb->get_row("SELECT * FROM {$table} ORDER BY RAND() LIMIT 1", ARRAY_A);
		return $map ? array($this->prepare_map($map)) : false;
	}

	/**
	 * Fetches maps associated with posts found with custom WP posts query.
	 *
	 * @param string Custom WP posts query
	 * @return mixed Maps array on success, false on failure
	 */
	function get_custom_maps ($query) {
		$table = $this->get_table_name();
		$wpq = new Wp_Query($query);
		$posts = $wpq->posts ? $wpq->posts : array();//get_posts();
		$where_string = $this->prepare_query_string($posts);
		if (!$where_string) return false;
		$maps = $this->wpdb->get_results("SELECT * FROM {$table} {$where_string}", ARRAY_A);
		if (is_array($maps)) foreach ($maps as $k=>$v) {
			$maps[$k] = $this->prepare_map($v);
		}
		return $maps;
	}

	/**
	 * Fetches all maps on the network.
	 * This could potentially be quite slow.
	 *
	 * @return Maps array on success, false on failure
	 */
	function get_all_network_maps () {
		$maps = array();
		$blogs = $this->wpdb->get_col("SELECT blog_id FROM {$this->wpdb->base_prefix}blogs WHERE public='1' AND archived='0' AND mature='0' AND spam='0' AND deleted='0'");
		$blogs = $blogs ? $blogs : array();

		foreach ($blogs as $blog) {
			switch_to_blog($blog);
			$maps += $this->get_all_maps();
			restore_current_blog();
		}

		return $maps;
	}

	/**
	 * Fetches random map from random blog on the network.
	 *
	 * @return Maps array on success, false on failure
	 */
	function get_random_network_map () {
		$blogs = $this->wpdb->get_col("SELECT blog_id FROM {$this->wpdb->base_prefix}blogs WHERE public='1' AND archived='0' AND mature='0' AND spam='0' AND deleted='0'");
		if (!$blogs) return $this->get_random_map();

		$map = false; $iter = 0;
		while (!$map) {
			if ($iter > 10) break;
			$blog = $blogs[array_rand($blogs)];
			switch_to_blog($blog);
			$map = $this->get_random_map();
			restore_current_blog();
			$iter++;
		}

		return $map;
	}

	/**
	 * Fetches network maps associated with posts found with custom WP posts query.
	 * Requires Post Indexer plugin.
	 *
	 * @param string Custom WP posts query
	 * @return mixed Maps array on success, false on failure
	 */
	function get_custom_network_maps ($query) {
		if (!AGM_USE_POST_INDEXER) return false;

		$tags = explode('tag=', $query);
		if (!isset($tags[1])) return false;
		$tags = explode(',', $tags[1]);
		foreach ($tags as $key=>$val) {
			$tags[$key] = "'" . trim($val) . "'";
		}
		$tags = join(',', $tags);

		$tag_table = $this->wpdb->base_prefix . 'site_terms';
		$sql = "SELECT term_id FROM {$tag_table} WHERE type='post_tag' AND slug IN ({$tags})";
		$tag_ids = $this->wpdb->get_results($sql, ARRAY_A);
		if (empty($tag_ids)) return false;

		$post_table = $this->wpdb->base_prefix . 'site_posts';
		$where = array();
		foreach ($tag_ids as $tag) {
			$where[] = "post_terms LIKE '%|" . $tag['term_id'] . "|%'";
		}
		$where = join (' OR ', $where);
		$sql = "SELECT * FROM {$post_table} WHERE {$where} ORDER BY site_id, blog_id";
		$posts = $this->wpdb->get_results($sql, ARRAY_A);
		if (empty($posts)) return false;

		$sql = array();
		foreach ($posts as $post) {
			switch_to_blog($post['blog_id']);
			$id = (int)$post['post_id'];
			$len = strlen($post['post_id']);
			$blog_id = $post['blog_id'];
			$table = $this->get_table_name();
			$sql[] = "SELECT *, {$blog_id} as blog_id FROM {$table} WHERE {$table}.post_ids LIKE '%s:{$len}:\"{$id}\";%'";
			restore_current_blog();
		}
		$sql = join(' UNION ', $sql);
		$maps = $this->wpdb->get_results($sql, ARRAY_A);

		if (is_array($maps)) foreach ($maps as $k=>$v) {
			$maps[$k] = $this->prepare_map($v);
		}
		return $maps;
	}

	/**
	 * Fetches maps by list of IDs.
	 *
	 * @param array List of map IDs
	 * @return mixed Maps array on success, false on failure
	 */
	function get_maps_by_ids ($ids) {
		if (!is_array($ids)) return false;
		$clean = array();
		foreach ($ids as $id) {
			if ((int)$id) $clean[] = (int)$id;
		}
		if (empty($clean)) return false;
		$table = $this->get_table_name();
		$maps = $this->wpdb->get_results("SELECT * FROM {$table} WHERE id IN(" . join(',', $clean) . ")", ARRAY_A);
		if (is_array($maps)) foreach ($maps as $k=>$v) {
			$maps[$k] = $this->prepare_map($v);
		}
		return $maps;
	}

	/**
	 * Fetches a list of post titles.
	 *
	 * @param array List of post IDs to fetch titles for
	 * @return mixed Post titles/IDs array on success, false on failure
	 */
	function get_post_titles ($ids) {
		if (!is_array($ids)) return false;
		$blog_id = false;
		$posts = array();
		$blogs_to_posts = array();

		foreach ($ids as $k=>$v) {
			if (strpos($v, "|")) {
				list($blog_id, $v) = explode('|', $v);
				$blog_id = (int)$blog_id;
			}
			$blogs_to_posts[$blog_id] = is_array($blogs_to_posts[$blog_id]) ? array_merge($blogs_to_posts[$blog_id], array($v)) : array($v);
		}

		foreach ($blogs_to_posts as $blog_id => $ids) {
			if ($blog_id) {
				switch_to_blog($blog_id);
			}
			$table = $this->wpdb->prefix . 'posts';
			$ids_string = join(', ', $ids);
			$result = $this->wpdb->get_results("SELECT id, post_title FROM {$table} WHERE ID IN ({$ids_string})", ARRAY_A);
			foreach ($result as $rid => $post) {
				$post['permalink'] = get_permalink($post['id']);
				$posts[] = $post;
			}
			if ($blog_id) {
				restore_current_blog();
			}
		}
		if (!$posts) return false;
		return $posts;
	}

	/**
	 * Fetches a list of existing maps ids/titles.
	 *
	 * @param int $page (optional) Page to retrieve
	 * @param int $limit (optional) Upper limit to fetch
	 * @return mixed Map id/title array on success, false on failure
	 */
	function get_maps ($page=false, $limit=false) {
		$paged = false;
		if ($limit >= 0) {
			$builtin_limit = defined('AGM_GET_MAPS_LIMIT') && AGM_GET_MAPS_LIMIT ? AGM_GET_MAPS_LIMIT: 50;
			$limit = apply_filters('agm_google_maps-get_maps-limit',
				($limit ? $limit : $builtin_limit)
			);
			$start = $page ? $page * $limit : 0;
			$stop = $limit;
			$paged = "LIMIT {$start}, {$stop}";
		}
		$table = $this->get_table_name();
		return $this->wpdb->get_results("SELECT id, title FROM {$table} {$paged}", ARRAY_A);
	}

	function get_maps_total () {
		$table = $this->get_table_name();
		return $this->wpdb->get_var("SELECT COUNT(*) FROM {$table}");
	}

	/**
	 * Gets a particular map.
	 *
	 * @param int Map id
	 * @return mixed Map array on success, false on failure
	 */
	function get_map ($id) {
		$id = (int)$id;
		$table = $this->get_table_name();
		$map = $this->wpdb->get_row("SELECT * FROM {$table} WHERE id={$id}", ARRAY_A);

		return $this->prepare_map($map);
	}

	/**
	 * Returns a list of map default options.
	 *
	 * @return mixed Maps defaults array
	 */
	function get_map_defaults () {
		$defaults = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		if (!isset($defaults['image_limit'])) $defaults['image_limit'] = 10;
		if (isset($defaults['use_custom_fields'])) unset($defaults['use_custom_fields']);
		if (isset($defaults['custom_fields_map'])) unset($defaults['custom_fields_map']);
		if (isset($defaults['custom_fields_options'])) unset($defaults['custom_fields_options']);
		$ret = array_filter($defaults);
		$ret['snapping'] = (int)@$defaults['snapping'];
		return $ret;
	}

	/**
	 * Saves a map.
	 *
	 * @param array Map data to save.
	 * @return mixed Id on success, false on failure
	 */
	function save_map ($data) {
		$id = isset($data['id']) ? (int)$data['id'] : false;
		$table = $this->get_table_name();
		$data = $this->prepare_for_save($data);
		$ret = false;

		if ($id) {
			$result = $this->wpdb->update($table, $data, array('id'=>$id));
			$ret = ($result) ? $id : false;
		} else {
			$result = $this->wpdb->insert($table, $data);
			$ret = ($result) ? $this->wpdb->insert_id : false;
		}
		return $ret;
	}

	/**
	 * Removes a map from the database.
	 *
	 * @param array Array containing 'id' key
	 * @return mixed Deleted id on success, false on failure
	 */
	function delete_map ($data) {
		$id = (int)$data['id'];
		$table = $this->get_table_name();

		$result = $this->wpdb->query("DELETE FROM {$table} WHERE id={$id}");
		return $result ? $id : false;
	}

	/**
	 * Deletes maps by list of IDs.
	 *
	 * @param array List of map IDs
	 * @return bool true on success, false on failure
	 */
	function batch_delete_maps ($ids) {
		if (!is_array($ids)) return false;
		$clean = array();
		foreach ($ids as $id) {
			if ((int)$id) $clean[] = (int)$id;
		}
		if (empty($clean)) return false;
		$table = $this->get_table_name();
		$res = $this->wpdb->query("DELETE FROM {$table} WHERE id IN(" . join(',', $clean) . ")", ARRAY_A);
		return $res ? true : false;
	}

	/**
	 * Prepares a complex query string.
	 * Used to find maps associated to posts.
	 *
	 * @param array A list of posts
	 * @return mixed Maps array on success, false on failure
	 */
	function prepare_query_string ($posts) {
		$where = array();
		if (!is_array($posts)) return false;
		foreach ($posts as $post) {
			$id = (int)$post->ID;
			$len = strlen($post->ID);
			$where[] = "'%s:{$len}:\"{$id}\";%'";
		}
		return 'WHERE post_ids LIKE ' . join(' OR post_ids LIKE ', $where);
	}

	/**
	 * Prepares map array for serving to front end.
	 *
	 * @param array Map array
	 * @return array Prepared map array
	 */
	function prepare_map ($map) {
		if (!$map) return false;
		$markers = unserialize(@$map['markers']);
		$options = unserialize(@$map['options']);
		$post_ids =  unserialize(@$map['post_ids']);
		$defaults = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));

		// Data is force-escaped by WP, so compensate for that
		$result = array_map('stripslashes_deep', array(
			"markers" => $markers,
			"defaults" => $defaults,
			"post_ids" => array_values($post_ids), // Reindex the array, so it doesn't turn up as JSON object
			"id" => @$map['id'],
			"title" => @$map['title'],
			"height" => @$options['height'],
			"width" =>  @$options['width'],
			"zoom" =>  @$options['zoom'],
			"map_type" =>  @$options['map_type'],
			"map_alignment" =>  @$options['map_alignment'],
			"show_map" =>  @$options['show_map'],
			"show_posts" =>  @$options['show_posts'],
			"show_markers" =>  @$options['show_markers'],
			"show_images" =>  @$options['show_images'],
			"image_size" =>  @$options['image_size'],
			"image_limit" =>  $options['image_limit'],
			"show_panoramio_overlay" =>  @$options['show_panoramio_overlay'],
			"panoramio_overlay_tag" =>  @$options['panoramio_overlay_tag'],
			"street_view" =>  @$options['street_view'],
			"street_view_pos" =>  @$options['street_view_pos'],
			"street_view_pov" =>  @$options['street_view_pov'],
		));

		if (isset($map['blog_id'])) $result['blog_id'] = $map['blog_id'];

		$result["markers"] = apply_filters('agm_google_maps-prepare_map-markers', $result["markers"], $markers);
		$result = apply_filters('agm_google_maps-prepare_map-options', $result, $options);
		return $result;
	}

	/**
	 * Prepares raw map data for saving to the database.
	 *
	 * @param array Raw map data array
	 * @return array Map array prepared for storage
	 */
	function prepare_for_save ($data) {
		// Normalize marker contents
		if (is_array($data['markers'])) foreach ($data['markers'] as $k=>$v) {
			$data['markers'][$k]['icon'] = basename($v['icon']);
			if (!current_user_can('unfiltered_html')) {
				$data['markers'][$k]['body'] = wp_filter_post_kses($v['body']);
			}
		}
		$post_ids = is_array(@$data['post_ids']) ? array_unique($data['post_ids']) : array();
		// Pack options
		$map_options = array (
			"height" => @$data['height'],
			"width" => @$data['width'],
			"zoom" => @$data['zoom'],
			"map_type" => strtoupper(@$data['map_type']),
			"map_alignment" => strtolower(@$data['map_alignment']),
			"show_map" => (int)@$data['show_map'],
			"show_posts" => (int)@$data['show_posts'],
			"show_markers" => (int)@$data['show_markers'],
			"show_images" => (int)@$data['show_images'],
			"image_size" => @$data['image_size'],
			"image_limit" => (int)@$data['image_limit'],
			"show_panoramio_overlay" => (int)@$data['show_panoramio_overlay'],
			"panoramio_overlay_tag" => @$data['panoramio_overlay_tag'],
			"street_view" => @$data['street_view'],
			"street_view_pos" => @$data['street_view_pos'],
			"street_view_pov" => @$data['street_view_pov'],
		);

		$data['title'] = apply_filters('agm_google_maps-prepare_for_save-title', $data['title'], $data);
		$data['markers'] = apply_filters('agm_google_maps-prepare_for_save-markers', $data['markers'], $data);
		$post_ids = apply_filters('agm_google_maps-prepare_for_save-post_ids', $post_ids, $data);
		$map_options = apply_filters('agm_google_maps-prepare_for_save-map_options', $map_options, $data);

		// Return prepped data array
		return array (
			"title" => $data['title'],
			"markers" => serialize($data['markers']),
			"post_ids" => serialize($post_ids),
			"options" => serialize($map_options),
		);
	}

	function merge_markers ($maps) {
		if (!is_array($maps)) return false;
		$defaults = $this->get_map_defaults();
		$markers = array();
		foreach ($maps as $map) {
			$map['markers'] = is_array($map['markers']) ? $map['markers'] : array();
			$markers = array_merge($markers, $map['markers']);
		}
		//$markers = agm_array_multi_unique($markers);

		// Merge in all the post ids too.
		// This is for widget show_posts option.
		$new_markers = $markers;
		foreach ($maps as $map) {
			if (isset($map['blog_id']) && is_array($map['post_ids'])) {
				foreach ($map['post_ids'] as $key=>$val) {
					$map['post_ids'][$key] = $map['blog_id'] . "|{$val}";
				}
			}
			foreach ($markers as $mid=>$marker) {
				$post_ids = isset($marker['post_ids']) ? $marker['post_ids'] : array();
				$map['markers'] = is_array($map['markers']) ? $map['markers'] : array();
				if (in_array($marker, $map['markers'])) {
					$post_ids = array_merge($post_ids, $map['post_ids']);
					@$new_markers[$mid]['post_ids'] = is_array($new_markers[$mid]['post_ids']) ? array_merge($new_markers[$mid]['post_ids'], $post_ids) : $post_ids;
				}
			}
		}
		$markers = agm_array_multi_unique($new_markers);

		return array(
			'id' => md5(rand(). microtime()),
			'defaults' => $defaults,
			'markers' => $markers,
			'show_map' => 1,
			'show_markers' => 1,
			'show_images' => 1,
			'zoom' => $defaults['zoom'],
		);
	}

	/**
	 * Autocreates and saves a map from supplied values.
	 */
	function autocreate_map ($post_id, $lat, $lon, $add) {
		$opts = apply_filters('agm_google_maps-options', get_option('agm_google_maps'));
		$do_associate = @$opts['custom_fields_options']['associate_map'];

		if (!$lat || !$lon) {
			$geo = $this->_address_to_marker($add);
		} else {
			$geo = $this->_position_to_marker($lat, $lon);
		}

		if (!$geo) return false; // Geolocation failed

		$map = $this->get_map_defaults();
		$map['title'] = $geo['title'];
		$map['show_map'] = 1;
		$map['show_markers'] = 1;
		$map['markers'] = array($geo);
		if ($do_associate) $map['post_ids'] = array("{$post_id}");

		$map_id = $this->save_map($map);

		if (!$map_id) return false;
		update_post_meta($post_id, 'agm_map_created', $map_id);
		return $map_id;
	}

	/**
	 * Converts valid address information to map marker.
	 *
	 * @access private
	 */
	function _address_to_marker ($address) {
		$result = $this->geocode_address($address);
		if (!$result) return false;

		return array(
			'title' => $address,
			'body' => '',
			'icon' => 'marker.png',
			'position' => array (
				$result->geometry->location->lat,
				$result->geometry->location->lng
			),
		);
	}

	/**
	 * Converts latitude/longitude pair to map marker.
	 *
	 * @access private
	 */
	function _position_to_marker ($lat, $lon) {
		$url = "http://maps.google.com/maps/api/geocode/json?latlng={$lat},{$lon}&sensor=false";
		$result = wp_remote_get($url);
		$json = json_decode($result['body']);

		if (!$json) return false;
		$result = (isset($json->results) && isset($json->results[0])) ? $json->results[0] : false;
		if (!$result) return false;

		return array(
			'title' => $result->formatted_address,
			'body' => '',
			'icon' => 'marker.png',
			'position' => array (
				$result->geometry->location->lat,
				$result->geometry->location->lng
			),
		);
	}

	/**
	 * Algorithm adapted from http://janmatuschek.de/LatitudeLongitudeBoundingCoordinates
	 */
	public function find_bounding_coordinates ($lat, $lng, $distance) {
		$rad_dist = $distance / 6371000;
		$rad_lat = deg2rad($lat);
		$rad_lng = deg2rad($lng);

		$min_lat = $rad_lat - $rad_dist;
		$max_lat = $rad_lat + $rad_dist;

		if ($min_lat > deg2rad(-90) && $max_lat < deg2rad(90)) {
			$delta_lng = asin(sin($rad_dist) / cos($rad_lat));
			$min_lng = $rad_lng - $delta_lng;
			if ($min_lng < deg2rad(-180)) $min_lng += 2 * E_PI;
			$max_lng = $rad_lng + $delta_lng;
			if ($max_lng > deg2rad(180)) $max_lng -= 2 * E_PI;
		} else {
			// a pole is within the distance
			$min_lat = Math.max($min_lat, deg2rad(-90));
			$max_lat = Math.min($max_lat, deg2rad(90));
			$min_lng = deg2rad(-180);
			$max_lng = deg2rad(180);
		}

		$min_lat = rad2deg($min_lat);
		$min_lng = rad2deg($min_lng);
		
		$max_lat = rad2deg($max_lat);
		$max_lng = rad2deg($max_lng);
		return array($min_lat, $max_lat, $min_lng, $max_lng);
	}

	public function geocode_address ($address) {
		$urladd = rawurlencode($address);
		$url = "http://maps.google.com/maps/api/geocode/json?address={$urladd}&sensor=false";
		$result = wp_remote_get($url);
		$json = json_decode($result['body']);

		if (!$json) return false;
		return $json->results[0];
	}
}


/**
 * Variation of standard array_unique that works on multidimensional arrays.
 *
 * @param array Array to be processed.
 * @return array Processed array.
 */
function agm_array_multi_unique ($array) {
	if (!is_array($array)) return $array;
	$ret = array();
	$hashes = array();

	foreach ($array as $k=>$v) {
		$hash = md5(serialize($v));
		if (isset($hashes[$hash])) continue;
		$hashes[$hash] = $hash;
		$ret[] = $v;
	}

	return $ret;
}

/**
 * Positive values helper, used for getting positive values in
 * shortcode parsing.
 */
function agm_positive_values () {
	return array('yes', 'true', 'on');
}

/**
 * Negative values helper, used for getting negative values in
 * shortcode parsing.
 */
function agm_negative_values () {
	return array('no', 'false', 'off');
}