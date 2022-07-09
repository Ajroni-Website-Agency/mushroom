<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

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
define( 'DB_NAME', 'i6822217_wp8' );

/** Database username */
define( 'DB_USER', 'i6822217_wp8' );

/** Database password */
define( 'DB_PASSWORD', 'S.AsiiAdWDEhrMks1Qj00' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY',         'sCX1rmX8oUUKlucFX0jtR0XgXT957RMi8eiHYSjxGTheZaCuwJTydWC76vlr3Odd');
define('SECURE_AUTH_KEY',  'kiMMccpglnuGbhaGbMnaFnEs6oY9V6lTfe07ICKcFDzUvLP5robSCi14mhPjSryA');
define('LOGGED_IN_KEY',    'szyAx6VajkOtltL8rnunvBp1gSZSl5iP7PcUjOzbf9kT4Fjty7nGWBUozmpUQ9vi');
define('NONCE_KEY',        'QJDLmM1AbezHIHnpMnjOn21uyYcyn3KHGFRHs3jN5tyxQCKnggcAys7ma1UanzYP');
define('AUTH_SALT',        '6SP9GLz3qSqZBdhxvvLbkyL5hUB2s13tNueYA7LXVmAZRmJDA0lVkfVzNo63rm2z');
define('SECURE_AUTH_SALT', 'kcRwhxHxerbhHVICCdrcVX6MIbdon9gBDxqwz2FjeuQVICxX2ILmDqq3MCgwDdep');
define('LOGGED_IN_SALT',   '5Zj6r2H6zjNEf38GjQ2aJ08GbcfD4hM9OPY2APRqT1AscLe4VINbQZdKd6ryKW3w');
define('NONCE_SALT',       '0sm9260aEtbc9eI16XyAushVcU3UjZcFJXcpCA8KbCMcu75UObeplgfih8aBgpOo');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


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
