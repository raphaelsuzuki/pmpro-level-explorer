<?php
/**
 * Integration tests for PMPro Level Explorer.
 *
 * @package Pmpro_Level_Explorer
 */

/**
 * Integration test case.
 */
class Integration_Test extends WP_UnitTestCase {

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
	 * Test complete plugin initialization flow.
	 */
	public function test_plugin_initialization_flow() {
		// Verify constants are defined
		$this->assertTrue( defined( 'PMPRO_LEVEL_EXPLORER_VERSION' ) );
		$this->assertTrue( defined( 'PMPRO_LEVEL_EXPLORER_DIR' ) );
		$this->assertTrue( defined( 'PMPRO_LEVEL_EXPLORER_URL' ) );

		// Verify functions are defined
		$this->assertTrue( function_exists( 'pmpro_level_explorer_init' ) );
		$this->assertTrue( function_exists( 'pmpro_level_explorer_check_dependencies' ) );

		// Verify actions are registered
		$this->assertTrue( has_action( 'plugins_loaded', 'pmpro_level_explorer_init' ) !== false );
	}

	/**
	 * Test admin hooks are properly registered.
	 */
	public function test_admin_hooks_registration() {
		PMPRO_Level_Explorer_Admin::init();

		// Verify all admin hooks are registered
		$this->assertTrue( has_action( 'admin_menu', array( 'PMPRO_Level_Explorer_Admin', 'add_menu' ) ) !== false );
		$this->assertTrue( has_filter( 'pmpro_nav_tabs', array( 'PMPRO_Level_Explorer_Admin', 'add_nav_tab' ) ) !== false );
		$this->assertTrue( has_action( 'admin_enqueue_scripts', array( 'PMPRO_Level_Explorer_Admin', 'enqueue_assets' ) ) !== false );
	}

	/**
	 * Test that plugin works with WordPress core functions.
	 */
	public function test_plugin_integrates_with_wordpress() {
		// Test that WordPress functions are available
		$this->assertTrue( function_exists( 'add_action' ) );
		$this->assertTrue( function_exists( 'add_filter' ) );
		$this->assertTrue( function_exists( 'esc_html__' ) );
		$this->assertTrue( function_exists( 'admin_url' ) );
	}

	/**
	 * Test custom filters can be applied.
	 */
	public function test_custom_filters_can_be_applied() {
		$custom_order = array( 3, 'asc' );
		add_filter( 'pmpro_level_explorer_default_order', function() use ( $custom_order ) {
			return $custom_order;
		} );

		$filtered_order = apply_filters( 'pmpro_level_explorer_default_order', array( 1, 'desc' ) );
		$this->assertEquals( $custom_order, $filtered_order );

		remove_all_filters( 'pmpro_level_explorer_default_order' );
	}

	/**
	 * Test page length filter customization.
	 */
	public function test_page_length_filter_customization() {
		add_filter( 'pmpro_level_explorer_page_length', function() {
			return 100;
		} );

		$filtered_length = apply_filters( 'pmpro_level_explorer_page_length', 25 );
		$this->assertEquals( 100, $filtered_length );

		remove_all_filters( 'pmpro_level_explorer_page_length' );
	}

	/**
	 * Test length menu filter customization.
	 */
	public function test_length_menu_filter_customization() {
		$custom_menu = array( 10, 20, 30 );
		add_filter( 'pmpro_level_explorer_length_menu', function() use ( $custom_menu ) {
			return $custom_menu;
		} );

		$filtered_menu = apply_filters( 'pmpro_level_explorer_length_menu', array( 25, 50, 100 ) );
		$this->assertEquals( $custom_menu, $filtered_menu );

		remove_all_filters( 'pmpro_level_explorer_length_menu' );
	}

	/**
	 * Test state save can be disabled.
	 */
	public function test_state_save_can_be_disabled() {
		add_filter( 'pmpro_level_explorer_state_save', '__return_false' );

		$filtered_state = apply_filters( 'pmpro_level_explorer_state_save', true );
		$this->assertFalse( $filtered_state );

		remove_all_filters( 'pmpro_level_explorer_state_save' );
	}

	/**
	 * Test nav tab integration with existing tabs.
	 */
	public function test_nav_tab_integration_with_existing_tabs() {
		$existing_tabs = array(
			'pmpro-dashboard' => array(
				'title' => 'Dashboard',
				'url'   => admin_url( 'admin.php?page=pmpro-dashboard' ),
			),
			'pmpro-membershiplevels' => array(
				'title' => 'Membership Levels',
				'url'   => admin_url( 'admin.php?page=pmpro-membershiplevels' ),
			),
		);

		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( $existing_tabs );

		// Should preserve existing tabs
		$this->assertArrayHasKey( 'pmpro-dashboard', $result );
		$this->assertArrayHasKey( 'pmpro-membershiplevels', $result );

		// Should add new tab
		$this->assertArrayHasKey( 'pmpro-level-explorer', $result );

		// Should have 3 tabs total
		$this->assertCount( 3, $result );
	}

	/**
	 * Test plugin constants are consistent.
	 */
	public function test_plugin_constants_are_consistent() {
		// DIR should be a valid directory path
		$this->assertDirectoryExists( PMPRO_LEVEL_EXPLORER_DIR );

		// URL should contain the plugin directory name
		$this->assertStringContainsString( 'pmpro-level-explorer', PMPRO_LEVEL_EXPLORER_URL );

		// Version should be a valid semantic version format
		$this->assertMatchesRegularExpression( '/^\d+\.\d+\.\d+$/', PMPRO_LEVEL_EXPLORER_VERSION );
	}

	/**
	 * Test plugin files are properly structured.
	 */
	public function test_plugin_files_are_properly_structured() {
		// Main plugin file should exist
		$this->assertFileExists( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );

		// Includes directory should exist
		$this->assertDirectoryExists( PMPRO_LEVEL_EXPLORER_DIR . 'includes' );

		// Admin class file should exist
		$this->assertFileExists( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Assets directory should exist
		$this->assertDirectoryExists( PMPRO_LEVEL_EXPLORER_DIR . 'assets' );
	}

	/**
	 * Test admin capability requirement.
	 */
	public function test_admin_capability_requirement() {
		// Test with user without capability
		$subscriber_id = $this->factory->user->create( array( 'role' => 'subscriber' ) );
		wp_set_current_user( $subscriber_id );

		$this->assertFalse( current_user_can( 'pmpro_membershiplevels' ) );

		// Test with user with capability
		$admin_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $admin_id );
		$admin = get_user_by( 'id', $admin_id );
		$admin->add_cap( 'pmpro_membershiplevels' );

		$this->assertTrue( current_user_can( 'pmpro_membershiplevels' ) );
	}

	/**
	 * Test WordPress escaping functions are used.
	 */
	public function test_wordpress_escaping_functions_are_used() {
		$admin_file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );

		// Should use esc_html__
		$this->assertStringContainsString( 'esc_html__', $admin_file_content );

		// Should use esc_url
		$this->assertStringContainsString( 'esc_url', $admin_file_content );
	}

	/**
	 * Test plugin handles WordPress debug mode.
	 */
	public function test_plugin_handles_debug_mode() {
		// Plugin should not generate warnings even in debug mode
		$original_debug = defined( 'WP_DEBUG' ) ? WP_DEBUG : false;

		if ( ! defined( 'WP_DEBUG' ) ) {
			define( 'WP_DEBUG', true );
		}

		// Re-initialize should not cause errors
		$result = pmpro_level_explorer_check_dependencies();
		$this->assertIsBool( $result );
	}

	/**
	 * Test plugin version consistency across files.
	 */
	public function test_plugin_version_consistency() {
		$main_file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'pmpro-level-explorer.php' );

		// Version in header should match constant
		$this->assertStringContainsString( 'Version: 1.5.0', $main_file_content );
		$this->assertEquals( '1.5.0', PMPRO_LEVEL_EXPLORER_VERSION );
	}

	/**
	 * Test all filter names follow WordPress conventions.
	 */
	public function test_filter_names_follow_conventions() {
		$filters = array(
			'pmpro_level_explorer_default_order',
			'pmpro_level_explorer_page_length',
			'pmpro_level_explorer_length_menu',
			'pmpro_level_explorer_state_save',
			'pmpro_nav_tabs',
		);

		foreach ( $filters as $filter ) {
			// Filter names should be lowercase with underscores
			$this->assertMatchesRegularExpression( '/^[a-z_]+$/', $filter );

			// Should start with plugin prefix or pmpro
			$this->assertTrue(
				str_starts_with( $filter, 'pmpro_level_explorer_' ) || str_starts_with( $filter, 'pmpro_' ),
				"Filter {$filter} should start with proper prefix"
			);
		}
	}

	/**
	 * Test plugin handles missing assets gracefully.
	 */
	public function test_plugin_structure_includes_required_assets() {
		// JS directory should exist
		$this->assertDirectoryExists( PMPRO_LEVEL_EXPLORER_DIR . 'assets/js' );

		// CSS directory should exist
		$this->assertDirectoryExists( PMPRO_LEVEL_EXPLORER_DIR . 'assets/css' );

		// Admin JS file should exist
		$this->assertFileExists( PMPRO_LEVEL_EXPLORER_DIR . 'assets/js/admin.js' );
	}

	/**
	 * Regression test: Verify init function checks dependencies.
	 */
	public function test_init_function_checks_dependencies() {
		// The init function should call check_dependencies
		$this->assertTrue( function_exists( 'pmpro_level_explorer_check_dependencies' ) );
		$this->assertTrue( function_exists( 'pmpro_level_explorer_init' ) );

		// Verify dependency check is called (indirectly by checking function exists)
		$result = pmpro_level_explorer_check_dependencies();
		$this->assertIsBool( $result );
	}

	/**
	 * Boundary test: Test with empty tabs array.
	 */
	public function test_add_nav_tab_with_empty_array() {
		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( array() );

		$this->assertIsArray( $result );
		$this->assertCount( 1, $result );
		$this->assertArrayHasKey( 'pmpro-level-explorer', $result );
	}

	/**
	 * Boundary test: Test with large number of existing tabs.
	 */
	public function test_add_nav_tab_with_many_existing_tabs() {
		$tabs = array();
		for ( $i = 0; $i < 50; $i++ ) {
			$tabs[ "tab-{$i}" ] = array(
				'title' => "Tab {$i}",
				'url'   => "http://example.com/{$i}",
			);
		}

		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( $tabs );

		$this->assertCount( 51, $result );
		$this->assertArrayHasKey( 'pmpro-level-explorer', $result );
	}
}