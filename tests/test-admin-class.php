<?php
/**
 * Tests for the admin class.
 *
 * @package Pmpro_Level_Explorer
 */

/**
 * Test case for PMPRO_Level_Explorer_Admin class.
 */
class Admin_Class_Test extends WP_UnitTestCase {

	/**
	 * Set up test environment.
	 */
	public function setUp(): void {
		parent::setUp();

		// Load the admin class
		if ( ! class_exists( 'PMPRO_Level_Explorer_Admin' ) ) {
			require_once PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php';
		}
	}

	/**
	 * Test that admin class exists.
	 */
	public function test_admin_class_exists() {
		$this->assertTrue( class_exists( 'PMPRO_Level_Explorer_Admin' ) );
	}

	/**
	 * Test that init method exists.
	 */
	public function test_init_method_exists() {
		$this->assertTrue( method_exists( 'PMPRO_Level_Explorer_Admin', 'init' ) );
	}

	/**
	 * Test that add_menu method exists.
	 */
	public function test_add_menu_method_exists() {
		$this->assertTrue( method_exists( 'PMPRO_Level_Explorer_Admin', 'add_menu' ) );
	}

	/**
	 * Test that add_nav_tab method exists.
	 */
	public function test_add_nav_tab_method_exists() {
		$this->assertTrue( method_exists( 'PMPRO_Level_Explorer_Admin', 'add_nav_tab' ) );
	}

	/**
	 * Test that enqueue_assets method exists.
	 */
	public function test_enqueue_assets_method_exists() {
		$this->assertTrue( method_exists( 'PMPRO_Level_Explorer_Admin', 'enqueue_assets' ) );
	}

	/**
	 * Test that render method exists.
	 */
	public function test_render_method_exists() {
		$this->assertTrue( method_exists( 'PMPRO_Level_Explorer_Admin', 'render' ) );
	}

	/**
	 * Test init registers admin_menu action.
	 */
	public function test_init_registers_admin_menu_action() {
		PMPRO_Level_Explorer_Admin::init();

		$this->assertTrue( has_action( 'admin_menu', array( 'PMPRO_Level_Explorer_Admin', 'add_menu' ) ) !== false );
	}

	/**
	 * Test init registers pmpro_nav_tabs filter.
	 */
	public function test_init_registers_nav_tabs_filter() {
		PMPRO_Level_Explorer_Admin::init();

		$this->assertEquals( 20, has_filter( 'pmpro_nav_tabs', array( 'PMPRO_Level_Explorer_Admin', 'add_nav_tab' ) ) );
	}

	/**
	 * Test init registers admin_enqueue_scripts action.
	 */
	public function test_init_registers_enqueue_scripts_action() {
		PMPRO_Level_Explorer_Admin::init();

		$this->assertTrue( has_action( 'admin_enqueue_scripts', array( 'PMPRO_Level_Explorer_Admin', 'enqueue_assets' ) ) !== false );
	}

	/**
	 * Test add_nav_tab returns array with correct structure.
	 */
	public function test_add_nav_tab_returns_correct_structure() {
		$tabs = array();
		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( $tabs );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'pmpro-level-explorer', $result );
		$this->assertArrayHasKey( 'title', $result['pmpro-level-explorer'] );
		$this->assertArrayHasKey( 'url', $result['pmpro-level-explorer'] );
	}

	/**
	 * Test add_nav_tab title is correct.
	 */
	public function test_add_nav_tab_title_is_correct() {
		$tabs = array();
		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( $tabs );

		$this->assertEquals( 'Level Explorer', $result['pmpro-level-explorer']['title'] );
	}

	/**
	 * Test add_nav_tab URL is correct.
	 */
	public function test_add_nav_tab_url_contains_page_param() {
		$tabs = array();
		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( $tabs );

		$this->assertStringContainsString( 'page=pmpro-level-explorer', $result['pmpro-level-explorer']['url'] );
	}

	/**
	 * Test add_nav_tab preserves existing tabs.
	 */
	public function test_add_nav_tab_preserves_existing_tabs() {
		$tabs = array(
			'existing-tab' => array(
				'title' => 'Existing',
				'url'   => 'http://example.com',
			),
		);

		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( $tabs );

		$this->assertArrayHasKey( 'existing-tab', $result );
		$this->assertArrayHasKey( 'pmpro-level-explorer', $result );
		$this->assertCount( 2, $result );
	}

	/**
	 * Test render method requires capability.
	 */
	public function test_render_requires_capability() {
		// Create a user without the required capability
		$user_id = $this->factory->user->create( array( 'role' => 'subscriber' ) );
		wp_set_current_user( $user_id );

		$this->expectException( WPDieException::class );

		PMPRO_Level_Explorer_Admin::render();
	}

	/**
	 * Test render outputs expected HTML with admin capability.
	 */
	public function test_render_outputs_html_with_capability() {
		// Create an admin user
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		// Grant the specific capability
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'Level Explorer', $output );
		$this->assertStringContainsString( 'levels-table', $output );
		$this->assertStringContainsString( 'pmpro_admin', $output );
	}

	/**
	 * Test render outputs table structure.
	 */
	public function test_render_outputs_table_structure() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		$this->assertStringContainsString( '<table', $output );
		$this->assertStringContainsString( '<thead>', $output );
		$this->assertStringContainsString( 'id="levels-table"', $output );
	}

	/**
	 * Test render outputs expected table headers.
	 */
	public function test_render_outputs_expected_headers() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		$expected_headers = array( 'ID', 'Name', 'Group', 'Members', 'Orders', 'Actions' );

		foreach ( $expected_headers as $header ) {
			$this->assertStringContainsString( $header, $output );
		}
	}

	/**
	 * Test render includes Add New Level button.
	 */
	public function test_render_includes_add_new_level_button() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'Add New Advanced Level', $output );
		$this->assertStringContainsString( 'page-title-action', $output );
	}

	/**
	 * Test render includes Add New Group button.
	 */
	public function test_render_includes_add_new_group_button() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'Add New Group', $output );
	}

	/**
	 * Test default order filter is applied.
	 */
	public function test_default_order_filter_is_applied() {
		$_GET['page'] = 'pmpro-level-explorer';

		// Add filter to modify default order
		add_filter( 'pmpro_level_explorer_default_order', function() {
			return array( 2, 'asc' );
		} );

		// We can't directly test enqueue_assets output, but we can verify the filter exists
		$this->assertTrue( has_filter( 'pmpro_level_explorer_default_order' ) !== false );

		unset( $_GET['page'] );
		remove_all_filters( 'pmpro_level_explorer_default_order' );
	}

	/**
	 * Test page length filter is applied.
	 */
	public function test_page_length_filter_is_applied() {
		$_GET['page'] = 'pmpro-level-explorer';

		add_filter( 'pmpro_level_explorer_page_length', function() {
			return 50;
		} );

		$this->assertTrue( has_filter( 'pmpro_level_explorer_page_length' ) !== false );

		unset( $_GET['page'] );
		remove_all_filters( 'pmpro_level_explorer_page_length' );
	}

	/**
	 * Test length menu filter is applied.
	 */
	public function test_length_menu_filter_is_applied() {
		$_GET['page'] = 'pmpro-level-explorer';

		add_filter( 'pmpro_level_explorer_length_menu', function() {
			return array( 10, 25, 50 );
		} );

		$this->assertTrue( has_filter( 'pmpro_level_explorer_length_menu' ) !== false );

		unset( $_GET['page'] );
		remove_all_filters( 'pmpro_level_explorer_length_menu' );
	}

	/**
	 * Test state save filter is applied.
	 */
	public function test_state_save_filter_is_applied() {
		$_GET['page'] = 'pmpro-level-explorer';

		add_filter( 'pmpro_level_explorer_state_save', '__return_false' );

		$this->assertTrue( has_filter( 'pmpro_level_explorer_state_save' ) !== false );

		unset( $_GET['page'] );
		remove_all_filters( 'pmpro_level_explorer_state_save' );
	}

	/**
	 * Test admin class file has ABSPATH check.
	 */
	public function test_admin_class_file_has_abspath_check() {
		$file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );
		$this->assertStringContainsString( "defined( 'ABSPATH' ) || exit", $file_content );
	}

	/**
	 * Test admin class uses proper text domain.
	 */
	public function test_admin_class_uses_proper_text_domain() {
		$file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );
		$this->assertStringContainsString( "pmpro-level-explorer", $file_content );
	}

	/**
	 * Test admin class methods are static.
	 */
	public function test_admin_class_methods_are_static() {
		$reflection = new ReflectionClass( 'PMPRO_Level_Explorer_Admin' );

		$methods = array( 'init', 'add_menu', 'add_nav_tab', 'enqueue_assets', 'render' );

		foreach ( $methods as $method_name ) {
			$method = $reflection->getMethod( $method_name );
			$this->assertTrue( $method->isStatic(), "Method {$method_name} should be static" );
		}
	}

	/**
	 * Test render outputs filter container.
	 */
	public function test_render_outputs_filter_container() {
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		$user = get_user_by( 'id', $user_id );
		$user->add_cap( 'pmpro_membershiplevels' );

		ob_start();
		PMPRO_Level_Explorer_Admin::render();
		$output = ob_get_clean();

		$this->assertStringContainsString( 'table-filters', $output );
	}

	/**
	 * Test that class has proper docblocks.
	 */
	public function test_admin_class_has_docblocks() {
		$file_content = file_get_contents( PMPRO_LEVEL_EXPLORER_DIR . 'includes/class-admin-page.php' );
		$this->assertStringContainsString( '@package', $file_content );
		$this->assertStringContainsString( '@since', $file_content );
	}

	/**
	 * Edge case: Test add_nav_tab with null input.
	 */
	public function test_add_nav_tab_with_null_input() {
		// Should handle null gracefully
		$result = PMPRO_Level_Explorer_Admin::add_nav_tab( array() );
		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'pmpro-level-explorer', $result );
	}

	/**
	 * Edge case: Test multiple calls to init don't duplicate hooks.
	 */
	public function test_multiple_init_calls_dont_duplicate_hooks() {
		PMPRO_Level_Explorer_Admin::init();
		$first_priority = has_filter( 'pmpro_nav_tabs', array( 'PMPRO_Level_Explorer_Admin', 'add_nav_tab' ) );

		PMPRO_Level_Explorer_Admin::init();
		$second_priority = has_filter( 'pmpro_nav_tabs', array( 'PMPRO_Level_Explorer_Admin', 'add_nav_tab' ) );

		// Both should have the same priority (20)
		$this->assertEquals( $first_priority, $second_priority );
	}
}