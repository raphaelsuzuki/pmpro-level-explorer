<?php
/**
 * Plugin Name: PMPro Level Explorer
 * Plugin URI: https://github.com/raphaelsuzuki/pmpro-level-explorer
 * Description: Enhanced level management with grouping, filtering, and search for Paid Memberships Pro
 * Version: 1.2.0
 * Author: Raphael Suzuki
 * Author URI: https://github.com/raphaelsuzuki/
 * Text Domain: pmpro-level-explorer
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: raphaelsuzuki/pmpro-level-explorer
 * Primary Branch: main
 */

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
if ( ! defined( 'PMPRO_LEVEL_EXPLORER_VERSION' ) ) {
	define( 'PMPRO_LEVEL_EXPLORER_VERSION', '1.2.0' );
}
if ( ! defined( 'PMPRO_LEVEL_EXPLORER_DIR' ) ) {
	define( 'PMPRO_LEVEL_EXPLORER_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'PMPRO_LEVEL_EXPLORER_URL' ) ) {
	define( 'PMPRO_LEVEL_EXPLORER_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Check if PMPro is active.
 *
 * @since 1.0.0
 * @return bool True if PMPro is active, false otherwise.
 */
function pmpro_level_explorer_check_dependencies() {
	if ( ! function_exists( 'pmpro_getAllLevels' ) ) {
		add_action( 'admin_notices', 'pmpro_level_explorer_dependency_notice' );
		return false;
	}
	return true;
}

/**
 * Show admin notice if PMPro is not active.
 *
 * @since 1.0.0
 */
function pmpro_level_explorer_dependency_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'PMPro Level Explorer requires Paid Memberships Pro to be installed and activated.', 'pmpro-level-explorer' ); ?></p>
	</div>
	<?php
}

/**
 * Load plugin text domain.
 *
 * @since 1.0.0
 */
function pmpro_level_explorer_load_textdomain() {
	load_plugin_textdomain( 'pmpro-level-explorer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'pmpro_level_explorer_load_textdomain' );

/**
 * Initialize the plugin.
 *
 * @since 1.0.0
 */
function pmpro_level_explorer_init() {
	if ( ! pmpro_level_explorer_check_dependencies() ) {
		return;
	}

	if ( is_admin() ) {
		require_once PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php';
		PMPRO_Level_Explorer_Admin::init();
	}
}
add_action( 'plugins_loaded', 'pmpro_level_explorer_init' );

/**
 * Plugin activation hook.
 *
 * @since 1.0.0
 */
function pmpro_level_explorer_activate() {
	if ( ! function_exists( 'pmpro_getAllLevels' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ! is_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( esc_html__( 'PMPro Level Explorer requires Paid Memberships Pro to be installed and activated.', 'pmpro-level-explorer' ) );
		}
	}
}
register_activation_hook( __FILE__, 'pmpro_level_explorer_activate' );
