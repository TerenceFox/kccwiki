<?php
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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'kccwiki');

/** MySQL database password */
define('DB_PASSWORD', 'kccwiki');

/** MySQL hostname */
define('DB_HOST', 'kccwiki');

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
define('AUTH_KEY',         '>r=Z+cR2lYBpz>l#~j=_,^YW5W5lS^#Q6]jE.[sBAe%w|x^XhQDb[)/*TrW?mh3?');
define('SECURE_AUTH_KEY',  '%pPb}r6}[51#-*t|fC!j=I.gH4f_LONZpvLc.@jZ4GJ?u G&!MCbj,ICt5=*:d2%');
define('LOGGED_IN_KEY',    'UeBOe~=!?t0vn{]usjc%X`iDerTBVS/2FuoBx=QXp]0-.t7v*m#)mK$xg86MFh60');
define('NONCE_KEY',        'bR[apKR}DT#2gciBew`B3KW-q9obba`%o?%|ir) a6v{2N 2&c!IYmNx;Im|@O?_');
define('AUTH_SALT',        'R|v$>r</G:b[AWBdE,fvORS_^AZGqWJLl7T( 1wQmK_Gs}b=HZFv#U%Cu#<h[l{3');
define('SECURE_AUTH_SALT', '[{P|E>Af0Y dW=DI?d/*y)_,PkR.K)7d]NQMa/[I OJXr@Ku5?!0x#l#`{l;aaGs');
define('LOGGED_IN_SALT',   'S)bG&Ww6OU/-O2Z8e$Y<zSJMxb[uCj)_E+/yG/Q~!#5_d,EF<8hX|rl7Nuurb[Pf');
define('NONCE_SALT',       'tDz<{5f}O6pm:UVBynjF%lM$A*(cBk;n`2WT!EooZO#G,L[jS<sBzfBdDgemL&~V');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/** Bypass FTP */
define('FS_METHOD', 'direct');
