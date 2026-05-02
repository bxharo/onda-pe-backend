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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * 
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */


//PHP SERVER PARA DESARROLLO
if ( $_SERVER['HTTP_HOST'] === 'localhost:8000' ) {
    // Entorno de Desarrollo Local
    define( 'WP_HOME', 'http://localhost:8000' );
    define( 'WP_SITEURL', 'http://localhost:8000' );
} else {
    // Entorno de Producción (Onda.pe)
    // WordPress usará las URLs que configuraste en la base de datos
}
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ondadb' );

/** Database username */
define( 'DB_USER', 'ondauser' );

/** Database password */
define( 'DB_PASSWORD', '864SUNSHINE_CO_LOR_' );

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
define( 'AUTH_KEY',          '8^]193SFQwNI~kot~!I)dh*-<&R[EdC{_GIO50.gnl5 ).:uY4z4dt9>FY6Px@78' );
define( 'SECURE_AUTH_KEY',   '=pF(#>!LL1Nmh>*v{%_h}j.Uu.(q*3;qEfPg$vmZ:s[4;}Ni(hj`Ah2AR6A~Vx;t' );
define( 'LOGGED_IN_KEY',     '!5hLw026V<?sS_/>b6W:54d_`4gC5*C)/4*NiRr: zTU|zU(a&:7?qHL;,:<`M$M' );
define( 'NONCE_KEY',         '`d>QRMF)N$c^&B*0#KRbEZ7t?Z)-q|FklVD,<fRo{nt?h>om<2+zS=plA=>I?4eM' );
define( 'AUTH_SALT',         '7/&`h[(Zzg[GP!A^l,b?,=2_Po^f>_o_vNY=s73QPF)<J6JM[-m-P|` gV7kfR{u' );
define( 'SECURE_AUTH_SALT',  'G|MqMmX+i|n48d9AjPq~RViz8ap`28YlzY|+1x%v H=2D!03/632N!$0<,1c,wH{' );
define( 'LOGGED_IN_SALT',    '3s?(d5S//SG[Qk2A&l^Tetb4|$S-ErBxG[Ph%o-/sQNfg:GPimWI$HPZ${! ven[' );
define( 'NONCE_SALT',        'Cer#X~S*%Gp#gx0T-}?^~g5U@&8%ajf pqA u1,L@mgK/j/RToO51RVV%o$j=cdz' );
define( 'WP_CACHE_KEY_SALT', '{-KY5UA^xUGdI.1 0v>+=1`KXx&_!n3K&PRVaN)wbXNjon`wNBVe`>s8_. H(V%;' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}
// Indica a WP que guarde los errores en un archivo llamado debug.log
define( 'WP_DEBUG_LOG', true );

// Evita que los errores salgan impresos en la pantalla de la web (opcional)
define( 'WP_DEBUG_DISPLAY', false );

/** Limitar revisiones a 3 por post */
define( 'WP_POST_REVISIONS', 3 );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

//Mientras estamos en entorno de desarrollo
define('FS_METHOD', 'direct');

