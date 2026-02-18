<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Pmpro_Level_Explorer
 */

// Load Composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Define the path to the WordPress tests library.
$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	// Use the library from wp-phpunit composer package.
	$_tests_dir = dirname( __DIR__ ) . '/vendor/wp-phpunit/wp-phpunit';
}

// Define missing constants for wp-phpunit.
if ( ! defined( 'WP_TESTS_DOMAIN' ) ) {
	define( 'WP_TESTS_DOMAIN', 'example.org' );
}
if ( ! defined( 'WP_TESTS_EMAIL' ) ) {
	define( 'WP_TESTS_EMAIL', 'admin@example.org' );
}
if ( ! defined( 'WP_TESTS_TITLE' ) ) {
	define( 'WP_TESTS_TITLE', 'Test Blog' );
}
if ( ! defined( 'WP_PHP_BINARY' ) ) {
	define( 'WP_PHP_BINARY', 'php' );
}

// Ensure database constants are defined if they aren't already.
// In CI, these are usually provided via environment variables.
if ( ! defined( 'DB_NAME' ) ) {
	define( 'DB_NAME', getenv( 'WP_DB_NAME' ) ?: 'wordpress_tests' );
}
if ( ! defined( 'DB_USER' ) ) {
	define( 'DB_USER', getenv( 'WP_DB_USER' ) ?: 'root' );
}
if ( ! defined( 'DB_PASSWORD' ) ) {
	define( 'DB_PASSWORD', getenv( 'WP_DB_PASS' ) ?: '' );
}
if ( ! defined( 'DB_HOST' ) ) {
	define( 'DB_HOST', getenv( 'WP_DB_HOST' ) ?: '127.0.0.1' );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';


/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/pmpro-level-explorer.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
