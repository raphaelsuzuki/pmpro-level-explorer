<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Pmpro_Level_Explorer
 */

// Load Composer autoloader.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define the path to the WordPress tests library.
$_tests_dir = getenv('WP_TESTS_DIR');

if (!$_tests_dir) {
	// Use the library from wp-phpunit composer package.
	$_tests_dir = dirname(__DIR__) . '/vendor/wp-phpunit/wp-phpunit';
}

// Tell wp-phpunit where to find the test config. This needs to be available to
// child processes (via getenv) when wp-phpunit runs install.php via system().
putenv('WP_PHPUNIT__TESTS_CONFIG=' . __DIR__ . '/wp-tests-config.php');

// Define the constants in the current process.
require_once __DIR__ . '/wp-tests-config.php';

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';


/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin()
{
	require dirname(dirname(__FILE__)) . '/pmpro-level-explorer.php';
}
tests_add_filter('muplugins_loaded', '_manually_load_plugin');

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';