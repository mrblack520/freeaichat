<?php
define( 'WP_CACHE', true );

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u358376817_au0ts' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          ';0m?6-5ATAoPZZPe*R)GxG%)*Y Nt~R$B*EJJZ,hjq=$KFf$5?idCH-QG%me|C9^' );
define( 'SECURE_AUTH_KEY',   '1|%)3fCdgL+=4Xyn97t5F;EEl[{7UL_AYqjgAJqp7tTDS$> >PY^Q{rZjH!lw(;|' );
define( 'LOGGED_IN_KEY',     'D`P6 ry;b{W;8PU;<km;-*%;>Ej>}LZ(CG}lIQW`lPZk9StI?F^ $m^_j5V,+a7P' );
define( 'NONCE_KEY',         '<umXJE[oR~JuB5g,P@0i9#2$pVn eI.U4esS$$M)w-dm7E{2;7]4d>GytZJO/rc[' );
define( 'AUTH_SALT',         'c:C|`|$`3NFrKWz08L>~. ^c:gJ}K|!-hJYyeg%9Swt@CU7y;bkl}gPA:g]qi<YD' );
define( 'SECURE_AUTH_SALT',  'hS(}1bq2Rv8b4eB6:KpS(SLLDtyQbcB.apUpFWLsA3n=i+;KWjk1bFm=QI/O5 E@' );
define( 'LOGGED_IN_SALT',    'Rhq.0o3ENY2TAlg*#VeaHM61;[Q4o$mVB}foX|y 1y^r/U*fnOE;]&.}q:;un9Cd' );
define( 'NONCE_SALT',        'FfQs3j9[dMRWT[p9_|uevkUeB&^xR5W}y[~xG|r`Ea|x)o;bqK6R-3g.R!l2ebpy' );
define( 'WP_CACHE_KEY_SALT', 'IG&$>pupLKk@O:e:/F.n[Mk*9xT;&d=v5_f$VUxD/I6-W:T4fkBoUu>A!,Q Gm^+' );


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

define('WP_HOME','http://localhost/freeaichat/');
define('WP_SITEURL','http://localhost/freeaichat/');

define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
