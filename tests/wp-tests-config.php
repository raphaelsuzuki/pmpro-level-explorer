<?php
/**
 * WordPress testing environment configuration.
 */

// Ensure database constants are defined if they aren't already.
// In CI, these are usually provided via environment variables.
if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('WP_DB_NAME') ?: 'wordpress_tests');
}
if (!defined('DB_USER')) {
    define('DB_USER', getenv('WP_DB_USER') ?: 'root');
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', getenv('WP_DB_PASS') ?: '');
}
if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('WP_DB_HOST') ?: '127.0.0.1');
}

// Define WordPress directory.
if (!defined('WP_CORE_DIR')) {
    define('WP_CORE_DIR', getenv('WP_CORE_DIR') ?: '/tmp/wordpress');
}

if (!defined('ABSPATH')) {
    define('ABSPATH', rtrim(WP_CORE_DIR, '/') . '/');
}

// Define missing constants for wp-phpunit from environment variables or defaults.
if (!defined('WP_TESTS_DOMAIN')) {
    define('WP_TESTS_DOMAIN', getenv('WP_TESTS_DOMAIN') ?: 'example.org');
}
if (!defined('WP_TESTS_EMAIL')) {
    define('WP_TESTS_EMAIL', getenv('WP_TESTS_EMAIL') ?: 'admin@example.org');
}
if (!defined('WP_TESTS_TITLE')) {
    define('WP_TESTS_TITLE', getenv('WP_TESTS_TITLE') ?: 'Test Blog');
}
if (!defined('WP_PHP_BINARY')) {
    define('WP_PHP_BINARY', getenv('WP_PHP_BINARY') ?: 'php');
}