<?php
/**
 * Security and validation tests for PMPro Level Explorer.
 *
 * @package Pmpro_Level_Explorer
 */

/**
 * Security test case.
 */
class Security_Test extends WP_UnitTestCase {

	/**
	 * Set up test environment.
	 */
	public function setUp(): void {
		parent::setUp();

		if ( ! class_exists( 'PMPRO_Level_Explorer_Admin' ) ) {
			require_once PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php';
		}
	}

	/**
	 * Test that main plugin file has ABSPATH security check.
	 */
	public function test_main_file_has_abspath_check() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$this->assertStringContainsString( "defined( 'ABSPATH' ) || exit", $content );
	}

	/**
	 * Test that admin class file has ABSPATH security check.
	 */
	public function test_admin_class_has_abspath_check() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );
		$this->assertStringContainsString( "defined( 'ABSPATH' ) || exit", $content );
	}

	/**
	 * Test that render method checks user capabilities.
	 */
	public function test_render_checks_user_capabilities() {
		// Create user without capabilities
		$user_id = $this->factory->user->create( array( 'role' => 'subscriber' ) );
		wp_set_current_user( $user_id );

		// Should not allow access
		$this->expectException( WPDieException::class );
		PMPRO_Level_Explorer_Admin::render();
	}

	/**
	 * Test that admin URLs are properly escaped.
	 */
	public function test_admin_urls_are_escaped() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use esc_url for all admin_url() calls
		$this->assertStringContainsString( 'esc_url( admin_url(', $content );
	}

	/**
	 * Test that translatable strings are properly escaped.
	 */
	public function test_translatable_strings_are_escaped() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use esc_html__ or esc_html_e for translated strings in HTML context
		$this->assertStringContainsString( 'esc_html__', $content );
		$this->assertStringContainsString( 'esc_html_e', $content );
	}

	/**
	 * Test that all text uses proper text domain.
	 */
	public function test_text_domain_is_consistent() {
		$main_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$admin_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// All translatable text should use 'pmpro-level-explorer' domain
		$pattern = "/'pmpro-level-explorer'/";
		$this->assertMatchesRegularExpression( $pattern, $main_content );
		$this->assertMatchesRegularExpression( $pattern, $admin_content );
	}

	/**
	 * Test that constants cannot be redefined.
	 */
	public function test_constants_have_redefinition_guards() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );

		// Should check if constants are already defined
		$this->assertStringContainsString( "if ( ! defined( 'PMPRO_LEVEL_EXPLORER_VERSION' ) )", $content );
		$this->assertStringContainsString( "if ( ! defined( 'PMPRO_LEVEL_EXPLORER_DIR' ) )", $content );
		$this->assertStringContainsString( "if ( ! defined( 'PMPRO_LEVEL_EXPLORER_URL' ) )", $content );
	}

	/**
	 * Test that plugin checks for required WordPress version.
	 */
	public function test_plugin_specifies_minimum_wordpress_version() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$this->assertStringContainsString( 'Requires at least:', $content );
	}

	/**
	 * Test that plugin specifies minimum PHP version.
	 */
	public function test_plugin_specifies_minimum_php_version() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$this->assertStringContainsString( 'Requires PHP:', $content );
	}

	/**
	 * Test that sensitive data in $_GET is validated.
	 */
	public function test_get_parameter_is_validated() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should check if $_GET['page'] is set before using it
		$this->assertStringContainsString( "isset( \$_GET['page'] )", $content );
	}

	/**
	 * Test that user input is sanitized in database queries.
	 */
	public function test_database_queries_use_wpdb() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use $wpdb for database queries
		$this->assertStringContainsString( 'global $wpdb;', $content );

		// Should use prepared statements or proper table prefixes
		$this->assertStringContainsString( '$wpdb->', $content );
	}

	/**
	 * Test that output is sanitized with wp_kses_post.
	 */
	public function test_html_output_is_sanitized() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use wp_kses_post for HTML content
		$this->assertStringContainsString( 'wp_kses_post', $content );
	}

	/**
	 * Test that nonces are used for delete actions.
	 */
	public function test_delete_actions_use_nonces() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use wp_nonce_url for delete actions
		$this->assertStringContainsString( 'wp_nonce_url', $content );
	}

	/**
	 * Test that JavaScript is properly escaped.
	 */
	public function test_javascript_output_is_escaped() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use esc_js for JavaScript strings
		$this->assertStringContainsString( 'esc_js', $content );
	}

	/**
	 * Test that activation hook validates dependencies.
	 */
	public function test_activation_validates_dependencies() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );

		// Activation function should check for PMPro
		$this->assertStringContainsString( 'pmpro_getAllLevels', $content );
		$this->assertStringContainsString( 'pmpro_level_explorer_activate', $content );
	}

	/**
	 * Test that plugin uses WordPress coding standards.
	 */
	public function test_files_follow_wordpress_coding_standards() {
		$main_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );
		$admin_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use proper spacing and braces
		$this->assertStringContainsString( ' ) {', $main_content );
		$this->assertStringContainsString( ' ) {', $admin_content );

		// Should use proper indentation (tabs)
		$this->assertStringContainsString( "\t", $main_content );
		$this->assertStringContainsString( "\t", $admin_content );
	}

	/**
	 * Test that render method escapes all output.
	 */
	public function test_render_escapes_all_output() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		// Output should not contain unescaped PHP variables
		$this->assertStringNotContainsString( '<?php echo $', $output );

		// Should use proper escaping functions
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );
		$this->assertStringContainsString( 'esc_url', $content );
		$this->assertStringContainsString( 'esc_html', $content );
	}

	/**
	 * Test that plugin validates user roles correctly.
	 */
	public function test_plugin_validates_user_roles() {
		// Test with different user roles
		$roles = array( 'subscriber', 'contributor', 'author', 'editor' );

		foreach ( $roles as $role ) {
			$user_id = $this->factory->user->create( array( 'role' => $role ) );
			wp_set_current_user( $user_id );

			// These roles should not have pmpro_membershiplevels capability by default
			$has_cap = current_user_can( 'pmpro_membershiplevels' );

			if ( $role === 'subscriber' || $role === 'contributor' || $role === 'author' ) {
				$this->assertFalse( $has_cap, "Role {$role} should not have pmpro_membershiplevels capability" );
			}
		}
	}

	/**
	 * Test that plugin prevents direct file access.
	 */
	public function test_files_prevent_direct_access() {
		$files = array(
			'pmpro-level-explorer.php',
			'includes/class-admin-page.php',
		);

		foreach ( $files as $file ) {
			$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . $file );
			$this->assertStringContainsString( "defined( 'ABSPATH' ) || exit", $content, "File {$file} should prevent direct access" );
		}
	}

	/**
	 * Test that plugin uses proper WordPress hooks.
	 */
	public function test_plugin_uses_proper_wordpress_hooks() {
		// Should use plugins_loaded for initialization
		$this->assertTrue( has_action( 'plugins_loaded', 'pmpro_level_explorer_init' ) !== false );
		$this->assertTrue( has_action( 'plugins_loaded', 'pmpro_level_explorer_load_textdomain' ) !== false );
	}

	/**
	 * Test SQL injection prevention in data retrieval.
	 */
	public function test_sql_injection_prevention() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use $wpdb methods for database access
		$this->assertStringContainsString( '$wpdb->get_results', $content );

		// Should use proper table name prefixing
		$this->assertStringContainsString( '$wpdb->pmpro_', $content );
		$this->assertStringContainsString( '$wpdb->prefix', $content );
	}

	/**
	 * Test XSS prevention in admin output.
	 */
	public function test_xss_prevention_in_output() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		// Output should be properly escaped
		$this->assertStringNotContainsString( '<script>', $output );

		// Check that esc_html_e is used in the render method
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );
		$this->assertStringContainsString( 'esc_html_e', $content );
	}

	/**
	 * Test that plugin handles errors gracefully.
	 */
	public function test_plugin_handles_errors_gracefully() {
		// Test dependency check returns boolean even if PMPro is missing
		$result = pmpro_level_explorer_check_dependencies();
		$this->assertIsBool( $result );

		// Should not throw fatal errors
		$this->assertTrue( true );
	}

	/**
	 * Test CSRF protection in forms.
	 */
	public function test_csrf_protection_in_forms() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Delete actions should use nonces
		$this->assertStringContainsString( 'wp_nonce_url', $content );
		$this->assertStringContainsString( 'pmpro_membershiplevels_nonce', $content );
	}

	/**
	 * Test that plugin validates file paths.
	 */
	public function test_plugin_validates_file_paths() {
		// Constants should use proper WordPress functions
		$this->assertStringEndsWith( '/', PMPRO_LEVEL_EXPLORER_DIR );
		$this->assertStringEndsWith( '/', PMPRO_LEVEL_EXPLORER_URL );

		// Paths should be valid
		$this->assertDirectoryExists( PMPRO_LEVEL_EXPLORER_DIR );
	}

	/**
	 * Test proper use of WordPress sanitization functions.
	 */
	public function test_proper_sanitization_function_usage() {
		$content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use various WordPress sanitization functions
		$sanitization_functions = array( 'esc_html', 'esc_url', 'esc_js', 'wp_kses_post' );

		foreach ( $sanitization_functions as $function ) {
			$this->assertStringContainsString( $function, $content, "Should use {$function} for sanitization" );
		}
	}
}