<?php
/**
 * Misc utilities, helpers and handlers.
 */



/**
 * Helper function for generating the registration fields array.
 */
function wdfb_get_registration_fields_array () {
	global $current_site;
	$data = Wdfb_OptionsRegistry::get_instance();
	$wp_grant_blog = false;
	if (is_multisite()) {
		$reg = get_site_option('registration');
		if ('all' == $reg) $wp_grant_blog = true;
		else if ('user' != $reg) return array();
	} else {
		if (!(int)get_option('users_can_register')) return array();
	}
	$fields = array(
		array("name" => "name"),
		array("name" => "email"),
		array("name" => "first_name"),
		array("name" => "last_name"),
		array("name" => "gender"),
		array("name" => "location"),
		array("name" => "birthday"),
	);
	if ($wp_grant_blog) {
		$fields[] = array(
			'name' => 'blog_title',
			'description' => __('Your blog title', 'wdfb'),
			'type' => 'text',
		);
		$newdomain = is_subdomain_install() 
			? 'youraddress.' . preg_replace('|^www\.|', '', $current_site->domain) 
			: $current_site->domain . $current_site->path . 'youraddress'
		;
		$fields[] = array(
			'name' => 'blog_domain',
			'description' => sprintf(__('Your blog address (%s)', 'wdfb'), $newdomain),
			'type' => 'text',
		);
	}
	if (!$data->get_option('wdfb_connect', 'no_captcha')) {
		$fields[] = array("name" => "captcha");
	}
	return apply_filters('wdfb-registration_fields_array', $fields);
}

/**
 * Helper function for processing registration fields array into a string.
 */
function wdfb_get_registration_fields () {
	$ret = array();
	$fields = wdfb_get_registration_fields_array();
	foreach ($fields as $field) {
		$tmp = array();
		foreach ($field as $key => $value) {
			$tmp[] = "'{$key}':'{$value}'";
		}
		$ret[] = '{' . join(',', $tmp) . '}';
	}
	$ret = '[' . join(',', $ret) . ']';
	return apply_filters('wdfb-registration_fields_string', $ret);
}

/**
 * Helper function for finding out the proper locale.
 */
function wdfb_get_locale () {
	$data = Wdfb_OptionsRegistry::get_instance();
	$locale = $data->get_option('wdfb_api', 'locale');
	return $locale ? $locale : preg_replace('/-/', '_', get_locale());
}

/**
 * Helper function for getting the login redirect URL.
 */
function wdfb_get_login_redirect ($force_admin_redirect=false) {
	$redirect_url = false;
	$data = Wdfb_OptionsRegistry::get_instance();
	$url = $data->get_option('wdfb_connect', 'login_redirect_url');
	if ($url) {
		$base = $data->get_option('wdfb_connect', 'login_redirect_base');
		$base = ('admin_url' == $base) ? 'admin_url' : 'site_url';
		$redirect_url = $base($url);
	} else {
		if (!defined('BP_VERSION') && $force_admin_redirect) {
			// Forcing admin url redirection
			$redirect_url = admin_url();
		} else {
			// Non-admin URL redirection, no specific settings

			if (isset($_GET['redirect_to'])) {
				// ... via GET parameter
				$redirect_url = $_GET['redirect_to'];
			} else {
				// ... via heuristics and settings
				global $post, $wp;
				if (is_singular() && is_object($post) && isset($post->ID)) {
					// Set to permalink for current item, if possible
					$redirect_url = apply_filters('wdfb-login-redirect_url-item_url', get_permalink($post->ID));
				}
				$fallback_url = (defined('WDFB_EXACT_REDIRECT_URL_FALLBACK') && WDFB_EXACT_REDIRECT_URL_FALLBACK) ? site_url($wp->request) : home_url();
				// Default to home URL otherwise
				$redirect_url = $redirect_url ? $redirect_url : $fallback_url;
			}
		}
	}

	return apply_filters('wdfb-login-redirect_url', $redirect_url);
}

/**
 * Expands some basic supported user macros.
 */
function wdfb_expand_user_macros ($str) {
	$user = wp_get_current_user();
	$str = preg_replace('/\bUSER_ID\b/', $user->ID, $str);
	$str = preg_replace('/\bUSER_LOGIN\b/', $user->user_login, $str);
	return $str;
}
add_filter('wdfb-login-redirect_url', 'wdfb_expand_user_macros', 1);

/**
 * Expands some basic supported BuddyPress macros.
 */
function wdfb_expand_buddypress_macros ($str) {
	if (!defined('BP_VERSION')) return $str;

	if (function_exists('bp_get_activity_root_slug')) $str = preg_replace('/\bBP_ACTIVITY_SLUG\b/', bp_get_activity_root_slug(), $str);
	if (function_exists('bp_get_groups_slug')) $str = preg_replace('/\bBP_GROUPS_SLUG\b/', bp_get_groups_slug(), $str);
	if (function_exists('bp_get_members_slug')) $str = preg_replace('/\bBP_MEMBERS_SLUG\b/', bp_get_members_slug(), $str);

	return $str;
}
add_filter('wdfb-login-redirect_url', 'wdfb_expand_buddypress_macros', 1);



/**
 * Helper function for fetching the image for OpenGraph info.
 */
function wdfb_get_og_image ($id=false) {
	$data = Wdfb_OptionsRegistry::get_instance();
	$use = $data->get_option('wdfb_opengraph', 'always_use_image');
	if ($use) return apply_filters(
		'wdfb-opengraph-image',
		apply_filters('wdfb-opengraph-image-always_used_image', $use)
	);

	// Try to find featured image
	if (function_exists('get_post_thumbnail_id')) { // BuddyPress :/
		$thumb_id = get_post_thumbnail_id($id);
	} else {
		$thumb_id = false;
	}
	if ($thumb_id) {
		$image = wp_get_attachment_image_src($thumb_id, 'thumbnail');
		if ($image) return apply_filters(
			'wdfb-opengraph-image',
			apply_filters('wdfb-opengraph-image-featured_image', $image[0])
		);
	}

	// If we're still here, post has no featured image.
	// Fetch the first one.
	// Thank you for this fix, grola!
	if ($id) {
		$post = get_post($id);
		$html = $post->post_content;
		if (!function_exists('load_membership_plugins') && !defined('GRUNION_PLUGIN_DIR')) $html = apply_filters('the_content', $html);
	} else if (is_home() && $data->get_option('wdfb_opengraph', 'fallback_image')) {
		return apply_filters(
			'wdfb-opengraph-image',
			apply_filters('wdfb-opengraph-image-fallback_image', $data->get_option('wdfb_opengraph', 'fallback_image'))
		);
	} else {
		$html = get_the_content();
		if (!function_exists('load_membership_plugins')) $html = apply_filters('the_content', $html);
	}
	preg_match_all('/<img .*src=["\']([^ ^"^\']*)["\']/', $html, $matches);
	if (@$matches[1][0]) return apply_filters(
		'wdfb-opengraph-image',
		apply_filters('wdfb-opengraph-image-post_image', $matches[1][0])
	);

	// Post with no images? Pffft.
	// Return whatever we have as fallback.
	return apply_filters(
		'wdfb-opengraph-image',
		apply_filters('wdfb-opengraph-image-fallback_image', $data->get_option('wdfb_opengraph', 'fallback_image'))
	);
}

/**
 * Construct OpenGraph properties from name/value pairs.
 *
 * @param string $name Property identifier
 * @param string $value Property value
 */
function wdfb_get_opengraph_property ($name, $value, $auto_prefix=true) {
	if (!$name && !$value) return false; // Zero out empty tags
	$name = esc_attr($name);
	$name = $auto_prefix ? "og:{$name}" : $name;
	$value = esc_attr($value);
	return apply_filters('wdfb-opengraph-property', "<meta property='{$name}' content='{$value}' />\n", $name, $value);
}

/**
 * Facebook XFBML tag format utility function (default).
 * Called by dispatcher, @see wdfb_get_fb_plugin_markup for parameters.
 * @return string
 */
function wdfb_get_fb_plugin_markup_xfbml ($type, $args) {
	$markup = '';
	switch ($type) {
		case "like":
			$markup = '<fb:like href="' . 
				$args['href'] . '" send="' . 
				($args['send'] ? 'true' : 'false') . '" layout="' . 
				$args['layout'] . '" width="' . 
				$args['width'] . 
			'" show_faces="true" font=""></fb:like>';
			break;

		case "login-button":
			$markup = '<fb:login-button scope="' . 
				$args['scope'] . 
				'" redirect-url="' . 
				$args['redirect-url'] . '"  onlogin="_wdfb_notifyAndRedirect();">' . 
					$args['content'] . 
			'</fb:login-button>';
			break;

		case "comments":
			$markup = '<fb:comments href="' . $args['link'] . '" '.
				'xid="' . $args['xid'] . '"  ' .
				'num_posts="' . $args['num_posts'] . '"  ' .
				'width="' . $args['width'] . 'px"  ' .
				'reverse="' . $args['reverse'] . '"  ' .
				'colorscheme="' . $args['scheme'] . '"  ' .
			'publish_feed="true"></fb:comments>';
			break;

		case "activity":
			$markup = '<fb:activity site="' . 
				$args['url'] . '" width="' . 
				$args['width'] . '" height="' . 
				$args['height'] . '" header="' . 
				$args['show_header'] . '" colorscheme="' . 
				$args['color_scheme'] . '" recommendations="' . 
				$args['recommendations'] . '" linktarget="' . 
			$args['links'] . '"></fb:activity>';
			break;
	}
	return $markup;
}

/**
 * Facebook HTML5 tag format utility function.
 * Called by dispatcher, @see wdfb_get_fb_plugin_markup for parameters.
 * @return string
 */
function wdfb_get_fb_plugin_markup_html5 ($type, $args) {
	$markup = '';
	switch ($type) {
		case "like":
			$markup = '<div class="fb-like" data-href="' . 
				$args['href'] . '" data-send="' . 
				($args['send'] ? 'true' : 'false') . '" data-layout="' . 
				$args['layout'] . '" data-width="' . 
				$args['width'] . 
			'" data-show-faces="true"></div>';
			break;

		case "login-button":
			$markup = '<div class="fb-login-button" data-scope="' . 
				$args['scope'] . 
				'" data-redirect-url="' . 
				$args['redirect-url'] . '"  data-onlogin="_wdfb_notifyAndRedirect();">' . 
					$args['content'] . 
			'</div>';
			break;

		case "comments":
			$markup = '<div class="fb-comments" data-href="' . $args['link'] . '" '.
				'data-xid="' . $args['xid'] . '"  ' .
				'data-num-posts="' . $args['num_posts'] . '"  ' .
				'data-width="' . $args['width'] . '"  ' .
				'data-reverse="' . $args['reverse'] . '"  ' .
				'data-colorscheme="' . $args['scheme'] . '"  ' .
			'data-publish-feed="true"></div>';
			break;

		case "activity":
			$markup = '<div class="fb-activity" data-site="' . 
				$args['url'] . '" data-width="' . 
				$args['width'] . '" data-height="' . 
				$args['height'] . '" data-header="' . 
				$args['show_header'] . '" data-recommendations="' . 
				$args['recommendations'] . '" data-colorscheme="' . 
				$args['color_scheme'] . '" data-linktarget="' . 
			$args['links'] . '"></div>';
			break;
	}

	return $markup;
}

/**
 * Facebook markup dispatcher.
 * Allows for multiple tag formats support.
 * @param string $type Tag type to render.
 * @param array $args A hash of arguments to use in rendering.
 * @param string $forced_format Optional output format to force.
 * @return string Tag output.
 */
function wdfb_get_fb_plugin_markup ($type, $args, $forced_format=false) {
	$_formats = array('html5', 'xfbml');
	$is_html5 = false;
	if ($forced_format && in_array($forced_format, $_formats)) {
		$is_html5 = ('html5' == $forced_format);
	} else {
		$is_html5 = defined('WDFB_USE_HTML5_TAGS') && WDFB_USE_HTML5_TAGS;
	}
	return apply_filters('wdfb-tags-use_html5', $is_html5) 
		? wdfb_get_fb_plugin_markup_html5($type, $args)
		: wdfb_get_fb_plugin_markup_xfbml($type, $args)
	;
}

/**
 * Template tag for FB comments.
 * @return string Facebook comments markup.
 * @example
 * <code>
 * // In e.g. single.php
 * if (function_exists('wdfb_get_fb_comments')) echo wdfb_get_fb_comments();
 * </code>
 */
function wdfb_get_fb_comments () {
	$data = Wdfb_OptionsRegistry::get_instance();
	$link = get_permalink();
	$xid = rawurlencode($link);

	$width = (int)$data->get_option('wdfb_comments', 'fb_comments_width');
	$width = $width ? $width : '550';

	$num_posts = (int)$data->get_option('wdfb_comments', 'fb_comments_number');
	$reverse = $data->get_option('wdfb_comments', 'fb_comments_reverse') ? 'true' : 'false';

	$scheme = $data->get_option('wdfb_comments', 'fb_color_scheme');
	$scheme = $scheme ? $scheme : 'light';

	return wdfb_get_fb_plugin_markup('comments', compact(array('link', 'xid', 'num_posts', 'width', 'reverse', 'scheme')));
}


/**
 * BuddyPress singular activity boolean flag.
 * Because `bp_is_single_item()` is basically broken.
 * Hence using the modified heuristic attrocity from bp-activity-screens.php.
 */
function wdfb_is_single_bp_activity () {
	if (!defined('BP_VERSION')) return false;
	if (!function_exists('bp_is_activity_component') || !bp_is_activity_component()) return false;
	if (!bp_current_action() || !is_numeric(bp_current_action())) return false;
	return true;
}

/**
 * Description abstraction, to make sure we sugarcoat the BP uglyness.
 */
function wdfb_get_singular_description () {
	$content = '';
	if (wdfb_is_single_bp_activity()) {
		$activity = bp_activity_get_specific( array( 'activity_ids' => bp_current_action(), 'show_hidden' => true, 'spam' => 'ham_only', ) );
		$activity = empty( $activity['activities'][0] ) || bp_action_variables() ? '' : $activity['activities'][0];
		$content = apply_filters_ref_array('bp_get_activity_content_body', array($activity->content, &$activity));
	} else {
		global $post;
		$content = $post->post_excerpt ? $post->post_excerpt : strip_shortcodes($post->post_content);
	}
	return htmlspecialchars(wp_strip_all_tags($content), ENT_QUOTES);
}


/**
 * Wrapper for URL to post ID matching.
 */
function wdfb_url_to_postid ($url) {
	$post_id = false;
	// Do our best to unwrap Jetpack shortlinks
	if (wdfb__has_jetpack() && preg_match('/https?:' . preg_quote('//wp.me', '/') . '/i', $url)) {
		// We may have a Jetpack-encoded link.
		$path = trim(parse_url($url, PHP_URL_PATH), '/');
		$type = substr($path, 0, 1);
		$no_type = substr($path, 1);
		if (false !== strstr($no_type, '-')) {
			list($raw_blog_id, $raw_post_id) = explode('-', $no_type);
			if ('s' === $type) {
				$post_id = wdfb__post_name_to_id($raw_post_id);
			} else {
				$post_id = wdfb__base62_to_decimal($raw_post_id);
			}
		}
	} else {
		// We hopefully have a regular link/shortlink
		$post_id = url_to_postid($url);
	}
	if (!$post_id) $post_id = apply_filters('wdfb-comments-url_to_post_id-fallback', $url);
	return apply_filters('wdfb-comments-url_to_post_id-post_id', $post_id);
}



/**
 * Applying the proper message for registration email notification.
 */
function wdfb_add_registration_filter () {
	add_filter('wdfb-registration_message', 'wdfb_add_email_message');
}
add_action('wdfb-registration_email_sent', 'wdfb_add_registration_filter');

/**
 * Creates a proper registration email notification message.
 */
function wdfb_add_email_message ($msg) {
	return
		apply_filters(
			'wdfb-registration_message-user',
			__('<p>An email with your login credentails has been sent to your email address.</p>', 'wdfb')
		) .
		$msg
	;
}

/**
 * Error registry class for exception transport.
 */
class Wdfb_ErrorRegistry {
	private static $_errors = array();
	
	private function __construct () {}
	
	public static function store ($exception) {
		self::$_errors[] = $exception;
	}
	
	public static function clear () {
		self::$_errors = array();
	}
	
	public static function get_errors () {
		return self::$_errors;
	}
	
	public static function get_last_error () {
		return end(self::$_errors);
	}
	
	public static function get_last_error_message () {
		$e = self::get_last_error();
		return ($e && is_object($e) && $e instanceof Exception) 
			? $e->getMessage()
			: false
		;
	}
}

/**
 * Utility to help converting the Jetpack-encoded shortlink format.
 * Barely adapted from
 * http://stackoverflow.com/questions/4964197/converting-a-number-base-10-to-base-62-a-za-z0-9
 * Original code by Eineki http://stackoverflow.com/users/29125/eineki
 * Thanks!
 */
function wdfb__base62_to_decimal ($num, $b=62) {
	$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$limit = strlen($num);
	$res = strpos($base, $num[0]);
	for ($i=1; $i<$limit; $i++) {
		$res = $b * $res + strpos($base,$num[$i]);
	}
	return $res;
}

/**
 * Post name to ID helper.
 */
function wdfb__post_name_to_id ($post_name) {
	global $wpdb;
	return $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_name=%s AND post_status='publish'", $post_name));
}

/**
 * Jetpack recognition helper.
 */
function wdfb__has_jetpack () {
	//return (bool)(class_exists('Jetpack') && function_exists('wpme_get_shortlink'));
	return defined('JETPACK__API_BASE');
}

/**
 * Allow for default curlopt timeout define.
 */
function wdfb_fb_core_curlopt_increase_timeout ($options) {
	if (!(defined('WDFB_FACEBOOK_CURLOPT_TIMEOUT') && WDFB_FACEBOOK_CURLOPT_TIMEOUT)) return $options;
	$options[CURLOPT_CONNECTTIMEOUT] = WDFB_FACEBOOK_CURLOPT_TIMEOUT;
	return $options;
}
add_filter('wdfb-fb_core-facebook_curl_options', 'wdfb_fb_core_curlopt_increase_timeout');


function wdfb_cleanup_admin_pages ($list) {
	return array_merge($list, array(
		'tools_page_codestyling-localization/codestyling-localization',
	));
}
add_filter('wdfb-scripts-prevent_inclusion_ids', 'wdfb_cleanup_admin_pages');


/**
 * Education complex field data mapping processor - school names.
 */
function wdfb__education_complex_profile_field_schools ($data) {
	if (!is_array($data) || empty($data)) return $data;
	$ret = array();
	foreach ($data as $sch) {
		if (!isset($sch['school']['name'])) continue;
		if (in_array($sch['school']['name'], $ret)) continue;
		$ret[] = $sch['school']['name'];
	}
	return join(', ', $ret);
}
add_filter('wdfb-profile_sync-education-schools', 'wdfb__education_complex_profile_field_schools');

/**
 * Education complex field data mapping processor - graduation.
 */
function wdfb__education_complex_profile_field_graduation ($data) {
	if (!is_array($data) || empty($data)) return $data;
	$ret = array();
	foreach ($data as $sch) {
		if (!isset($sch['school']['name'])) continue;
		if (!isset($sch['year']['name'])) continue;

		$str = $sch['school']['name'] . ' (' . $sch['year']['name'] . ')';
		if (in_array($str, $ret)) continue;

		$ret[] = $str;
	}
	return join(', ', $ret);
}
add_filter('wdfb-profile_sync-education-graduation_dates', 'wdfb__education_complex_profile_field_graduation');

/**
 * Education complex field data mapping processor - subjects.
 */
function wdfb__education_complex_profile_field_subjects ($data) {
	if (!is_array($data) || empty($data)) return $data;
	$ret = array();
	foreach ($data as $sch) {
		if (empty($sch['concentration'])) continue;
		foreach ($sch['concentration'] as $subject) {
			if (in_array($subject['name'], $ret)) continue;
			$ret[] = $subject['name'];
		}
	}
	return join(', ', $ret);
}
add_filter('wdfb-profile_sync-education-subjects', 'wdfb__education_complex_profile_field_subjects');

/**
 * Work complex field data mapping processor - employers.
 */
function wdfb__work_complex_profile_field_employers ($data) {
	if (!is_array($data) || empty($data)) return $data;
	$ret = array();
	foreach ($data as $wrk) {
		if (empty($wrk['employer']['name'])) continue;
		if (in_array($wrk['employer']['name'], $ret)) continue;
		$ret[] = $wrk['employer']['name'];
	}
	return join(', ', $ret);
}
add_filter('wdfb-profile_sync-work-employers', 'wdfb__work_complex_profile_field_employers');

/**
 * Work complex field data mapping processor - position_history.
 */
function wdfb__work_complex_profile_field_position_history ($data) {
	if (!is_array($data) || empty($data)) return $data;
	$ret = array();
	foreach ($data as $wrk) {
		if (empty($wrk['employer']['name'])) continue;
		$position = !empty($wrk['position']['name']) ? $wrk['position']['name'] : __('N/A', 'wdfb');
		$str = $wrk['employer']['name'] . " ({$position})";
		if (in_array($str, $ret)) continue;
		$ret[] = $str;
	}
	return join(', ', $ret);
}
add_filter('wdfb-profile_sync-work-position_history', 'wdfb__work_complex_profile_field_position_history');

/**
 * Work complex field data mapping processor - employer_history.
 */
function wdfb__work_complex_profile_field_employer_history ($data) {
	if (!is_array($data) || empty($data)) return $data;
	$ret = array();
	foreach ($data as $wrk) {
		if (empty($wrk['employer']['name'])) continue;
		$position = !empty($wrk['position']['name']) ? $wrk['position']['name'] : __('N/A', 'wdfb');
		$timespan = false;
		$start_date = !empty($wrk['start_date']) && !preg_match('/^0{4}/', $wrk['start_date']) ? $wrk['start_date'] : false;
		if ($start_date) {
			$end_date = !empty($wrk['end_date']) && !preg_match('/^0{4}/', $wrk['end_date']) ? $wrk['end_date'] : false;
			$end_date = $end_date ? $end_date : __('Present', 'wdfb');
			$timespan = ", {$start_date} - {$end_date}";
		}

		$str = $wrk['employer']['name'] . " ({$position}{$timespan})";
		if (in_array($str, $ret)) continue;
		$ret[] = $str;
	}
	return join(', ', $ret);
}
add_filter('wdfb-profile_sync-work-employer_history', 'wdfb__work_complex_profile_field_employer_history');

/**
 * Connection processor helper.
 */
function wdfb__profile_sync_connections_process_connection ($name, $model) {
	try {
		$data = $model->fb->api("/me/{$name}");
	} catch (Exception $e) {
		$data = false;
	}
	$data = !empty($data['data']) ? $data['data'] : array();
	$ret = array();
	foreach ($data as $item) {
		if (empty($item['name'])) continue;
		if (in_array($item['name'], $ret)) continue;
		$ret[] = $item['name'];
	}
	return join(', ', $ret);
}
/**
 * Books connection complex field data mapping processor.
 */
function wdfb__connection_complex_profile_field_books ($data, $name, $model) {
	return wdfb__profile_sync_connections_process_connection('books', $model);
}
add_filter('wdfb-profile_sync-connection-books', 'wdfb__connection_complex_profile_field_books', 10, 3);
/**
 * Games connection complex field data mapping processor.
 */
function wdfb__connection_complex_profile_field_games ($data, $name, $model) {
	return wdfb__profile_sync_connections_process_connection('games', $model);
}
add_filter('wdfb-profile_sync-connection-games', 'wdfb__connection_complex_profile_field_games', 10, 3);
/**
 * Music connection complex field data mapping processor.
 */
function wdfb__connection_complex_profile_field_movies ($data, $name, $model) {
	return wdfb__profile_sync_connections_process_connection('movies', $model);
}
add_filter('wdfb-profile_sync-connection-movies', 'wdfb__connection_complex_profile_field_movies', 10, 3);
/**
 * Movies connection complex field data mapping processor.
 */
function wdfb__connection_complex_profile_field_music ($data, $name, $model) {
	return wdfb__profile_sync_connections_process_connection('music', $model);
}
add_filter('wdfb-profile_sync-connection-music', 'wdfb__connection_complex_profile_field_music', 10, 3);
/**
 * Movies connection complex field data mapping processor.
 */
function wdfb__connection_complex_profile_field_television ($data, $name, $model) {
	return wdfb__profile_sync_connections_process_connection('television', $model);
}
add_filter('wdfb-profile_sync-connection-television', 'wdfb__connection_complex_profile_field_television', 10, 3);
/**
 * Movies connection complex field data mapping processor.
 */
function wdfb__connection_complex_profile_field_interests ($data, $name, $model) {
	return wdfb__profile_sync_connections_process_connection('interests', $model);
}
add_filter('wdfb-profile_sync-connection-interests', 'wdfb__connection_complex_profile_field_interests', 10, 3);