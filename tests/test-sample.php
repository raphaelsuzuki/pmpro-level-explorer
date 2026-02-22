<?php
/**
 * Class SampleTest
 *
 * @package Pmpro_Level_Explorer
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_sample() {
		$this->assertTrue( true );
	}

	/**
	 * Verify that the plugin is loaded.
	 */
	public function test_plugin_is_loaded() {
		$this->assertTrue( defined( 'PMPRO_LEVEL_EXPLORER_VERSION' ) );
	}
}
