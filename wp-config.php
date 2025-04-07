<?php
define( 'WP_CACHE', true ); // Added by WP Rocket




//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL

//Begin Really Simple SSL Load balancing fix
if ((isset($_ENV["HTTPS"]) && ("on" == $_ENV["HTTPS"]))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "1") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && (strpos($_SERVER["HTTP_X_FORWARDED_SSL"], "on") !== false))
|| (isset($_SERVER["HTTP_CF_VISITOR"]) && (strpos($_SERVER["HTTP_CF_VISITOR"], "https") !== false))
|| (isset($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_CLOUDFRONT_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && (strpos($_SERVER["HTTP_X_FORWARDED_PROTO"], "https") !== false))
|| (isset($_SERVER["HTTP_X_PROTO"]) && (strpos($_SERVER["HTTP_X_PROTO"], "SSL") !== false))
) {
$_SERVER["HTTPS"] = "on";
}
//END Really Simple SSL
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
// define( 'DB_NAME', 'wp-linkticweb' );
define('DB_NAME', 'linktic_pro_dev');
/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('WP_HOME','http://localhost/linktic' );
define('WP_SITEURL','http://localhost/linktic' );


define("FS_METHOD","direct");

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '9a}F wg7+$-aXPdq~OQX5ee_y#)aiUzJvok4wm7DIbORz*r35~Z#U|=y[2r80k}e' );
define( 'SECURE_AUTH_KEY',  'G~}OwU#+hT|Ptbt, _{hpJA$+~x|c@[J>G8F.{NG{o6~A<a.Oq3$)sQnYFZ]=.((' );
define( 'LOGGED_IN_KEY',    '?y*f3>I42YJ=|TQyt!SMfYv$fbNkYgPw5X3gin$}>KupIy>}!pdQ(h-MtfhMLl)1' );
define( 'NONCE_KEY',        'uV2n6<4@=ino<~(dI#2R}Z|Cq/A#3([TsW$BNG{rGSqcFhm$LtBt5NAag9 coTS.' );
define( 'AUTH_SALT',        'qOLvSJ]H,HTp 7r-:F#YY}bl,<6-D+4|~V4,;5=&M8=5x7S.*zVLF7>Ah9C$xNg@' );
define( 'SECURE_AUTH_SALT', '%EHr~U/k*&pmOOy8+bC1B ~04(P!.9prjMP,FV.N {|4lBSlr2kHWuu^w!hEjVS:' );
define( 'LOGGED_IN_SALT',   '~qT}I33mVaZwaBt|N]wjeIu44rH1d@cR+wN>p-+m./K]H]|QCRjZ(@K:ZV.tfj2v' );
define( 'NONCE_SALT',       'G6q-1O>)6/ML!Y{~cFO4(VXv$oXS$]c2[AbEjOVRh@m@kr;uEEwuXN45!)YaG<[@' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
// $table_prefix = 'lt_';
$table_prefix = 'H0cA2_';
define('UPLOADS', 'media');
define('WP_ALLOW_REPAIR', true);




/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
