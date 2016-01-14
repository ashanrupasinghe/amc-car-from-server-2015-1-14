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
define('DB_NAME', 'amc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'admin@sdu123#');

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
define('AUTH_KEY',         'GRM}yeP~eYp;Q.;XiC3yJ=xLk|7oLm_#&rTo,$+(O$$V@$aC~V( !%!#6cGTo[j`');
define('SECURE_AUTH_KEY',  '(!5=.0-*)^z;j7?s-5vV,)Cr2,0!;ydD<$SgZDP__z4wI$)zujnwDma4#M@?6z60');
define('LOGGED_IN_KEY',    'Xpk)4)9V[s0h$pdApae1~<zw-N1>zzPogPB1YadpYr)u:b-D9kwuqxR!4PoF^{(?');
define('NONCE_KEY',        '7~p4H?dv-e:!_f|A%L7:jl!<=ott,@yz3W<mezbdLxpghfqCyL[(,7`7J#0SJ1mM');
define('AUTH_SALT',        ')-]UHWlIY&y ^+R3tQ~Lm-|^4h+P-;)0C.N|6ru}6/H+HM#dN1w;(1mA?b`m{Vhd');
define('SECURE_AUTH_SALT', 'KaMi36,8T5tu8:,o![dwmua8zGd_y Qzuh|U}p[%N(5oI%6,^k/A)wofq@G3~Lb{');
define('LOGGED_IN_SALT',   'M}LZVeMg]JnV9Yl*P^v>Sk[JRXc|#xmQo<^Q2FMJ,Q0xp17%xVD?Gr$|H{}>:fa9');
define('NONCE_SALT',       'XFU%]]e,jDoX7YHG!s.(y.#K@p*7#5$$!c2./n{yUbkV)p?~dTT_)k7xcD7-t]yt');

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
