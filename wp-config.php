<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'projectFit');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'pQ04j`lKQ !SKlG$MVMwxz{ ;+1wSrn}o42WGkCL_WwQ8>H(P+AZ4cb1IIoL3HZP');
define('SECURE_AUTH_KEY',  'vgSWd{.*7D6IOWAtOJwl_b0.)K9ey:~36nsqF}!RxxRahTYJ9WR+U`,h9O,%fRGN');
define('LOGGED_IN_KEY',    '7=-]w>_kjh),k1LE8NqT?yGSxKc,CEaxQGI<ncohf5!A-)*lMT04q lU`;CQ&0wk');
define('NONCE_KEY',        '-|D_[[12{XbDvKoij$$L/.x%iyX=1+w:K)TMkP/Fa#+vjP+%BI%p0{I~lrAwjqIj');
define('AUTH_SALT',        '3P_Lx6H_dZ]T8*LlX;]8Rx0*>)nu829.zUr$fTlgfL{rcz$_!clD{V#KLw6!nvRD');
define('SECURE_AUTH_SALT', 'eN}5r6YA5~%]drGi~4}lOKO_+9@ubzoRoQFJRIuzT9m-Nigbx,]2GR0X4|t,[_UL');
define('LOGGED_IN_SALT',   'oq7oiO,W4^;53bogq.r1t>hYb.S+(0.&09q) OGM6z0@|Um5dHdi:76b]<LA</xM');
define('NONCE_SALT',       ' V?9Q&g7AjUZeL$q`d5-RL3[ Xz$wJLFZSf!@ozlErIK=,GR>v?jF:fW<n!l!4F_');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
