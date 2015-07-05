<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'phuketwithlove');

/** MySQL database username */
define('DB_USER', 'phuketwithlove');

/** MySQL database password */
define('DB_PASSWORD', 'KH7jyKc22ZaGacST');

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
define('AUTH_KEY',         '<HL![OT22?;M0J(v9LU+w?@[sK-&52.kNQt*|{k~HqYhrOPs^p, eWZQd=002bx$');
define('SECURE_AUTH_KEY',  ' hZVWUNE@[;(}-.}en{M:w-6?>.}|xo7/bsLVUvC?l*zN@(;iZ?/:H5yu g18%_Z');
define('LOGGED_IN_KEY',    'j;!wT~&:3E!c}!:k5vAv_!95tL`g(_Cq1pE[KlR;LX|/|E`o-u-<B[aI_c.!Dih<');
define('NONCE_KEY',        '[`0hpfM]_c`y6{q&+D]UWqyRZ+u5MR~$::r1t^k}T6D.$6*/K#ky/JSCNDO #xD!');
define('AUTH_SALT',        'GhT=w:|8v+uuU$T~9/zIZNz`WpT#l+y+NbD$?foWhW8Q|)Q}miDcgbep;9pUEJ!>');
define('SECURE_AUTH_SALT', 'Du)R6v4(0(DG/Bp-a`;X0GK!24=R~+.<WV.yZ0}&b< ?bPx!56GVyYYIV4lE0ay?');
define('LOGGED_IN_SALT',   '^tZ@fPeN.efQBrpC(dQ(+-NqX@=A6=MNRMIZ~P;J)&jd*_R8}o2MU-QTwb0`o&UY');
define('NONCE_SALT',       'b^xv/taY{k9saP6P#0Jf/3ZN0MNZ?XsE0;--df2&g>^He+{OQ2w/:{[Lb=twZ?UI');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
