<?php
define( 'EDD_VERSION', '1.3.2' );
define('FS_METHOD','direct');
//define('WP_HOME','http://localhost/razorbee/maf');
//define('WP_SITEURL','http://localhost/razorbee/maf');

define('WP_MEMORY_LIMIT', '5000M');
define('WP_DEBUG', false);
define( 'WP_DEBUG_LOG', false );

//define('DB_NAME', 'moktarul_wp11');
//define('DB_USER', 'moktarul_wp11');
//define('DB_PASSWORD', 'D@Y3t4tbc#6VUYCNC8]68[.2');


define('DB_NAME', 'maf');
define('DB_USER', 'root');
define('DB_PASSWORD', '');


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '9(5qrN{H.<-kDkX6$;S|snxYx[5bSz}F#6+q8V}uIuuyb)!tK0E,|WNJsP!4l*JJ');
define('SECURE_AUTH_KEY',  't::#5aC%oOH/=3E6 <+8+6r&LNp;s$( Gz8 H`|$44R8!|=tf=aF^I!8z @wrd])');
define('LOGGED_IN_KEY',    '5gyXciIS9X{F(Y%05PPC0vUV3(Tkf%IR^uoTLa#El)y^ie1#N~7_HFX,/v3E@&?Q');
define('NONCE_KEY',        'D,)289uo(RJsvskUpyjeNi0.s%Yn*HtB1m%+rtG^!-MPMuV1+*~y}X*e)A2Y~Iq/');
define('AUTH_SALT',        'vJ=)yc.MG|V*hL3Rq>q`soY6_Cb2dSrJGpc-k6jjov4.t)eW[##<QY[Tl[RO)ARs');
define('SECURE_AUTH_SALT', 'XNj(TByfq8W@tjzWzk*nP6N9;Sn$1:Em6cS,-MWsT/P5[/dC{S:94R&=y(s^1Y^u');
define('LOGGED_IN_SALT',   '{]1j.yV39x =BBB.R{vr]k6vq|`=m>uDy#Df>RZpVYC#pPB(A&mV9r6/.3]o/3^d');
define('NONCE_SALT',       'nETAUha2r-|Ko.(}U%~ Qh!>yLx=FWq8ru[fWPDA49xKE+&99vo67PfM8S:0j_lL');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
//	define('WP_DEBUG', false);
define('CONCATENATE_SCRIPTS', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
