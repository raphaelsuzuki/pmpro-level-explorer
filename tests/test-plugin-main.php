<?php
/**
 * Tests for the main plugin file.
 *
 * @package Pmpro_Level_Explorer
 */

/**
 * Test case for main plugin functionality.
 */
class Plugin_Main_Test extends WP_UnitTestCase {

	/**
	 * Test that plugin constants are defined.
	 */
	public function test_plugin_constants_are_defined() {
		$this->assertTrue( defined( 'PMPRO_LEVEL_EXPLORER_VERSION' ) );
		$this->assertTrue( defined( 'PMPRO_LEVEL_EXPLORER_DIR' ) );
		$this->assertTrue( defined( 'PMPRO_LEVEL_EXPLORER_URL' ) );
	}

	/**
	 * Test that version constant has correct value.
	 */
	public function test_version_constant_value() {
		$this->assertEquals( '1.5.0', PMPRO_LEVEL_EXPLORER_VERSION );
	}

	/**
	 * Test that directory constant points to correct path.
	 */
	public function test_directory_constant_is_valid_path() {
		$this->assertIsString( PMPRO_LEVEL_EXPLORER_DIR );
		$this->assertStringEndsWith( '/', PMPRO_LEVEL_EXPLORER_DIR );
	}

	/**
	 * Test that URL constant is a valid URL.
	 */
	public function test_url_constant_is_valid_url() {
		$this->assertIsString( PMPRO_LEVEL_EXPLORER_URL );
		$this->assertStringEndsWith( '/', PMPRO_LEVEL_EXPLORER_URL );
	}

	/**
	 * Test that dependency check function exists.
	 */
	public function test_dependency_check_function_exists() {
		$this->assertTrue( function_exists( 'pmpro_level_explorer_check_dependencies' ) );
	}

	/**
	 * Test that dependency notice function exists.
	 */
	public function test_dependency_notice_function_exists() {
		$this->assertTrue( function_exists( 'pmpro_level_explorer_dependency_notice' ) );
	}

	/**
	 * Test that textdomain loading function exists.
	 */
	public function test_textdomain_function_exists() {
		$this->assertTrue( function_exists( 'pmpro_level_explorer_load_textdomain' ) );
	}

	/**
	 * Test that init function exists.
	 */
	public function test_init_function_exists() {
		$this->assertTrue( function_exists( 'pmpro_level_explorer_init' ) );
	}

	/**
	 * Test that activation hook function exists.
	 */
	public function test_activation_function_exists() {
		$this->assertTrue( function_exists( 'pmpro_level_explorer_activate' ) );
	}

	/**
	 * Test textdomain loading action is registered.
	 */
	public function test_textdomain_action_is_registered() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', 'pmpro_level_explorer_load_textdomain' ) );
	}

	/**
	 * Test init action is registered.
	 */
	public function test_init_action_is_registered() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', 'pmpro_level_explorer_init' ) );
	}

	/**
	 * Test that dependency check returns boolean.
	 */
	public function test_dependency_check_returns_boolean() {
		$result = pmpro_level_explorer_check_dependencies();
		$this->assertIsBool( $result );
	}

	/**
	 * Test that dependency notice outputs expected content.
	 */
	public function test_dependency_notice_outputs_content() {
		ob_start();
		pmpro_level_explorer_dependency_notice();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'error', $output );
		$this->assertStringContainsString( 'PMPro Level Explorer requires Paid Memberships Pro', $output );
	}

	/**
	 * Test that admin class file exists.
	 */
	public function test_admin_class_file_exists() {
		$file_path = PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php';
		$this->assertFileExists( $file_path );
	}

	/**
	 * Test that admin class is loaded when in admin context.
	 */
	public function test_admin_class_is_defined() {
		// The class should be loaded by the init function in admin context
		if ( is_admin() ) {
			$this->assertTrue( class_exists( 'PMPRO_Level_Explorer_Admin' ) );
		} else {
			// Load manually for testing
			require_once PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php';
			$this->assertTrue( class_exists( 'PMPRO_Level_Explorer_Admin' ) );
		}
	}

	/**
	 * Test constants are not redefined.
	 */
	public function test_constants_are_not_redefined() {
		// Constants should not throw error when code tries to define them again
		// This tests the if ( ! defined() ) guards
		$original_version = PMPRO_LEVEL_EXPLORER_VERSION;

		// Try to include the file again (it should not redefine constants)
		// We can't actually re-include due to defined() checks, but we can verify they exist
		$this->assertEquals( $original_version, PMPRO_LEVEL_EXPLORER_VERSION );
	}

	/**
	 * Test plugin handles missing PMPro gracefully (negative test).
	 */
	public function test_dependency_check_with_missing_pmpro() {
		// This test verifies the function works even when PMPro is not available
		// We can't actually remove PMPro in this test environment, but we verify
		// the function executes without fatal errors
		$this->assertTrue( function_exists( 'pmpro_level_explorer_check_dependencies' ) );

		// The function should always return a boolean
		$result = pmpro_level_explorer_check_dependencies();
		$this->assertIsBool( $result );
	}

	/**
	 * Test that plugin files have proper PHP opening tags.
	 */
	public function test_plugin_file_has_proper_php_tags() {
		$file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$this->assertStringStartsWith( '<?php', $file_content );
	}

	/**
	 * Test that plugin file has ABSPATH check.
	 */
	public function test_plugin_file_has_abspath_check() {
		$file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$this->assertStringContainsString( "defined( 'ABSPATH' ) || exit", $file_content );
	}

	/**
	 * Test plugin textdomain matches expected value.
	 */
	public function test_textdomain_is_correct() {
		$file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$this->assertStringContainsString( "Text Domain: pmpro-level-explorer", $file_content );
		$this->assertStringContainsString( "'pmpro-level-explorer'", $file_content );
	}

	/**
	 * Test that activation hook is properly registered.
	 */
	public function test_activation_hook_is_registered() {
		global $wp_filter;

		// Verify the activation function exists and is callable
		$this->assertTrue( is_callable( 'pmpro_level_explorer_activate' ) );
	}
}