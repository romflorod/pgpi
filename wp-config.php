<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pgpi' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'X$_ETkXy%|Qq9E@^llHNm6x)`eXd#T+o~K ^EDc|_6QlgZzcYWP?08G{V4.caL>h' );
define( 'SECURE_AUTH_KEY',  ' d#Bd{V1;;kFF>W[0 q5ytyEz(R$%.dPS%An;yQ8A_p=L;u^qu!/wjqQM0Uk j5Z' );
define( 'LOGGED_IN_KEY',    'H=k&V&@sP>s5[GF4TU[I9:0cO0.pE2UL#,fW#Q*2B8eNZR1a$pQx9m!q1a</uR S' );
define( 'NONCE_KEY',        '3|%R-r-!unlSRm^#D2RXq|V6yjicdx|<T! R;DL 1N|w-5A$eE^c~|J>][++aQ0J' );
define( 'AUTH_SALT',        '/c|Y>Aaz8-Wx<pB}6?8Jf=v<udV8t&g1]:PUXDkf0nI3D:akN-n} ^otaH9,]#m{' );
define( 'SECURE_AUTH_SALT', 'D9xG%V])9S~pPC;lf}FoK7bNOq;734^7VKkE.@5),x2ld45Ph#%zj<[]TN<MTGa ' );
define( 'LOGGED_IN_SALT',   'mA@cFYfqHQ2M4bb=0*J-8!Sp{x>rT3aeBB%preZ@l/t_Jx@,7-yIm%[o(_(omyX*' );
define( 'NONCE_SALT',       'Job9EtRKRS8.#M92+3a=?F-K&q;$[7hh_1]b/RY gm%={C8T_y.6EehrAjPWWcob' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
