<?php
/**
 * Admin page class for PMPro Level Explorer.
 *
 * @package PMPro_Level_Explorer
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class PMPRO_Level_Explorer_Admin
 *
 * Handles the admin interface for Level Explorer.
 *
 * @since 1.0.0
 */
class PMPRO_Level_Explorer_Admin {

	/**
	 * Initialize the admin page.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_filter( 'pmpro_nav_tabs', array( __CLASS__, 'add_nav_tab' ), 20 );
	}

	/**
	 * Add submenu page to PMPro dashboard.
	 *
	 * @since 1.0.0
	 */
	public static function add_menu() {
		add_submenu_page(
			'pmpro-dashboard',
			__( 'Level Explorer', 'pmpro-level-explorer' ),
			__( 'Level Explorer', 'pmpro-level-explorer' ),
			'pmpro_membershiplevels',
			'pmpro-level-explorer',
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Add Level Explorer tab to PMPro navigation.
	 *
	 * @since 1.0.0
	 * @param array $tabs Existing PMPro navigation tabs.
	 * @return array Modified tabs array.
	 */
	public static function add_nav_tab( $tabs ) {
		$tabs['pmpro-level-explorer'] = array(
			'title' => __( 'Level Explorer', 'pmpro-level-explorer' ),
			'url'   => admin_url( 'admin.php?page=pmpro-level-explorer' ),
		);
		return $tabs;
	}

	/**
	 * Render the Level Explorer admin page.
	 *
	 * @since 1.0.0
	 */
	public static function render() {
		// Check user capabilities.
		if ( ! current_user_can( 'pmpro_membershiplevels' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'pmpro-level-explorer' ) );
		}

		$levels_data = self::get_levels_data();

		wp_enqueue_style( 'pmpro-admin', plugins_url( 'paid-memberships-pro/css/admin.css' ), array(), PMPRO_VERSION );
		wp_enqueue_style( 'datatables', PMPRO_LEVEL_EXPLORER_URL . 'assets/css/datatables/dataTables.dataTables.min.css', array(), PMPRO_LEVEL_EXPLORER_VERSION );
		wp_enqueue_style( 'pmpro-level-explorer', PMPRO_LEVEL_EXPLORER_URL . 'assets/css/admin.css', array(), PMPRO_LEVEL_EXPLORER_VERSION );
		wp_enqueue_script( 'datatables', PMPRO_LEVEL_EXPLORER_URL . 'assets/js/datatables/dataTables.min.js', array( 'jquery' ), PMPRO_LEVEL_EXPLORER_VERSION, true );
		wp_enqueue_script( 'pmpro-level-explorer', PMPRO_LEVEL_EXPLORER_URL . 'assets/js/admin.js', array( 'jquery', 'datatables' ), PMPRO_LEVEL_EXPLORER_VERSION, true );
		wp_localize_script( 'pmpro-level-explorer', 'pmproLevelExplorer', array( 'levels' => $levels_data ) );
		?>

		<div class="wrap pmpro_admin">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Level Explorer', 'pmpro-level-explorer' ); ?></h1>
			<a class="page-title-action pmpro-has-icon pmpro-has-icon-plus" href="<?php echo esc_url( admin_url( 'admin.php?page=pmpro-membershiplevels&edit=-1' ) ); ?>"><?php esc_html_e( 'Add New Level', 'pmpro-level-explorer' ); ?></a>
			<a class="page-title-action pmpro-has-icon pmpro-has-icon-plus" href="<?php echo esc_url( admin_url( 'admin.php?page=pmpro-membershiplevels&edit_group=-1' ) ); ?>"><?php esc_html_e( 'Add New Group', 'pmpro-level-explorer' ); ?></a>
			<hr class="wp-header-end">

			<div id="explorer-wrapper">
				<div id="table-filters"></div>

				<table id="levels-table" class="widefat display" style="width:100%">
					<thead>
						<tr>
							<th><?php esc_html_e( 'ID', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Name', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Group', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Members', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Initial', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Billing', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Cycle', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Billing Limit', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Trial Enabled', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Trial', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Trial Limit', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Expiration', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'New Signups', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'pmpro-level-explorer' ); ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Get formatted levels data for DataTables.
	 *
	 * @since 1.0.0
	 * @return array Array of formatted level data.
	 */
	private static function get_levels_data() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$levels = $wpdb->get_results( "SELECT * FROM {$wpdb->pmpro_membership_levels} ORDER BY name ASC" );

		// Get active member counts.
		$member_counts = array();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			"SELECT membership_id, COUNT(*) as count
			FROM {$wpdb->pmpro_memberships_users}
			WHERE status = 'active'
			GROUP BY membership_id"
		);
		foreach ( $results as $row ) {
			$member_counts[ $row->membership_id ] = (int) $row->count;
		}

		// Get group mappings.
		$level_groups = array();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			"SELECT lg.level, GROUP_CONCAT(DISTINCT g.name ORDER BY g.name SEPARATOR ', ') as group_names
			FROM {$wpdb->prefix}pmpro_membership_levels_groups lg
			INNER JOIN {$wpdb->prefix}pmpro_groups g ON lg.group = g.id
			GROUP BY lg.level"
		);
		foreach ( $results as $row ) {
			$level_groups[ $row->level ] = $row->group_names;
		}

		$data = array();
		foreach ( $levels as $l ) {
			$group         = isset( $level_groups[ $l->id ] ) ? $level_groups[ $l->id ] : '';
			$cycle         = $l->cycle_number > 0 ? $l->cycle_number . ' ' . $l->cycle_period : '-';
			$trial         = $l->trial_amount > 0 ? '$' . number_format( $l->trial_amount, 2 ) : '-';
			$trial_enabled = $l->trial_amount > 0 || $l->trial_limit > 0 ? 'Enabled' : 'Disabled';

			$delete_url = wp_nonce_url(
				admin_url( 'admin.php?page=pmpro-membershiplevels&action=delete_membership_level&deleteid=' . $l->id ),
				'delete_membership_level',
				'pmpro_membershiplevels_nonce'
			);

			$data[] = array(
				'id'                    => (int) $l->id,
				'name'                  => $l->name,
				'group'                 => $group,
				'members'               => isset( $member_counts[ $l->id ] ) ? $member_counts[ $l->id ] : 0,
				'initial'               => $l->initial_payment > 0 ? '$' . number_format( $l->initial_payment, 2 ) : '-',
				'billing'               => $l->billing_amount > 0 ? '$' . number_format( $l->billing_amount, 2 ) : '-',
				'cycle'                 => $cycle,
				'billing_limit_display' => $l->billing_limit > 0 ? $l->billing_limit : 'Unlimited',
				'trial_enabled'         => $trial_enabled,
				'trial'                 => $trial,
				'trial_limit_display'   => $l->trial_limit > 0 ? $l->trial_limit : '-',
				'expiration'            => $l->expiration_number > 0 ? $l->expiration_number . ' ' . $l->expiration_period : 'Never',
				'signups'               => $l->allow_signups ? 'Enabled' : 'Disabled',
				'actions'               => '<a href="' . esc_url( admin_url( 'admin.php?page=pmpro-membershiplevels&edit=' . $l->id ) ) . '">' . esc_html__( 'Edit', 'pmpro-level-explorer' ) . '</a> | ' .
					'<a href="' . esc_url( admin_url( 'admin.php?page=pmpro-membershiplevels&edit=-1&copy=' . $l->id ) ) . '">' . esc_html__( 'Copy', 'pmpro-level-explorer' ) . '</a> | ' .
					'<a href="javascript:pmpro_askfirst(\'' . esc_js( sprintf( __( 'Are you sure you want to delete membership level %s? All payment subscriptions for this level will be cancelled.', 'pmpro-level-explorer' ), $l->name ) ) . '\', \'' . esc_js( $delete_url ) . '\'); void(0);" class="delete-link">' . esc_html__( 'Delete', 'pmpro-level-explorer' ) . '</a>',
				'billing_amount'        => (float) $l->billing_amount,
				'cycle_number'          => (int) $l->cycle_number,
				'cycle_period'          => $l->cycle_period,
				'initial_payment'       => (float) $l->initial_payment,
				'trial_amount'          => (float) $l->trial_amount,
				'trial_limit'           => (int) $l->trial_limit,
				'billing_limit'         => (int) $l->billing_limit,
				'expiration_number'     => (int) $l->expiration_number,
				'expiration_period'     => $l->expiration_period ? $l->expiration_period : 'Never',
			);
		}

		return $data;
	}

}
