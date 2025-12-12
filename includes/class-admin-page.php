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
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
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
	 * Enqueue admin assets.
	 *
	 * @since 1.0.0
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue_assets( $hook ) {
		// Only load on our plugin page.
		if ( isset( $_GET['page'] ) && 'pmpro-level-explorer' === $_GET['page'] ) {
			// Apply filters for customization.
			$default_order = apply_filters( 'pmpro_level_explorer_default_order', array( 1, 'desc' ) );
			$page_length   = apply_filters( 'pmpro_level_explorer_page_length', 25 );
			$length_menu   = apply_filters( 'pmpro_level_explorer_length_menu', array( 25, 50, 100, 500 ) );

			wp_enqueue_style( 'pmpro-admin', plugins_url( 'paid-memberships-pro/css/admin.css' ), array(), PMPRO_VERSION );
			wp_enqueue_style( 'datatables', PMPRO_LEVEL_EXPLORER_URL . 'assets/css/datatables/dataTables.dataTables.min.css', array(), PMPRO_LEVEL_EXPLORER_VERSION );
			wp_enqueue_style( 'pmpro-level-explorer', PMPRO_LEVEL_EXPLORER_URL . 'assets/css/admin.css', array(), PMPRO_LEVEL_EXPLORER_VERSION );
			wp_enqueue_script( 'datatables', PMPRO_LEVEL_EXPLORER_URL . 'assets/js/datatables/dataTables.min.js', array( 'jquery' ), PMPRO_LEVEL_EXPLORER_VERSION, true );
			wp_enqueue_script( 'pmpro-level-explorer', PMPRO_LEVEL_EXPLORER_URL . 'assets/js/admin.js', array( 'jquery', 'datatables' ), PMPRO_LEVEL_EXPLORER_VERSION, true );
			wp_localize_script(
				'pmpro-level-explorer',
				'pmproLevelExplorer',
				array(
					'levels'       => self::get_levels_data(),
					'defaultOrder' => $default_order,
					'pageLength'   => $page_length,
					'lengthMenu'   => $length_menu,
				)
			);
		}
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
		?>

		<div class="wrap pmpro_admin">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Level Explorer', 'pmpro-level-explorer' ); ?></h1>
			<a class="page-title-action pmpro-has-icon pmpro-has-icon-plus" href="<?php echo esc_url( admin_url( 'admin.php?page=pmpro-membershiplevels&edit=-1&template=none' ) ); ?>"><?php esc_html_e( 'Add New Advanced Level', 'pmpro-level-explorer' ); ?></a>
			<a class="page-title-action pmpro-has-icon pmpro-has-icon-plus" href="<?php echo esc_url( admin_url( 'admin.php?page=pmpro-membershiplevels&edit_group=-1' ) ); ?>"><?php esc_html_e( 'Add New Group', 'pmpro-level-explorer' ); ?></a>
			<hr class="wp-header-end">

			<div id="explorer-wrapper" class="pmpro_section">
				<div id="table-filters" class="pmpro_section_inside"></div>

				<table id="levels-table" class="widefat display" style="width:100%">
					<thead>
						<tr>
							<th><?php esc_html_e( 'ID', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Name', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Group', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Members', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Orders', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Initial Payment', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Billing Amount', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Billing Cycle', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Billing Limit', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Trial Amount', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Trial Limit', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Expiration', 'pmpro-level-explorer' ); ?></th>
							<th><?php esc_html_e( 'Allow Signups', 'pmpro-level-explorer' ); ?></th>
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
		$levels = $wpdb->get_results( "SELECT * FROM {$wpdb->pmpro_membership_levels} ORDER BY id DESC" );

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

		// Get order counts.
		$order_counts = array();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			"SELECT membership_id, COUNT(*) as count
			FROM {$wpdb->pmpro_membership_orders}
			WHERE membership_id IS NOT NULL
			GROUP BY membership_id"
		);
		foreach ( $results as $row ) {
			$order_counts[ $row->membership_id ] = (int) $row->count;
		}

		// Get group mappings.
		$level_groups = array();
		$level_group_ids = array();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			"SELECT lg.level, GROUP_CONCAT(DISTINCT g.name ORDER BY g.name SEPARATOR ', ') as group_names, GROUP_CONCAT(DISTINCT g.id ORDER BY g.name SEPARATOR ', ') as group_ids
			FROM {$wpdb->prefix}pmpro_membership_levels_groups lg
			INNER JOIN {$wpdb->prefix}pmpro_groups g ON lg.group = g.id
			GROUP BY lg.level"
		);
		foreach ( $results as $row ) {
			$level_groups[ $row->level ] = $row->group_names;
			$level_group_ids[ $row->level ] = $row->group_ids;
		}

		// Get protected categories.
		$protected_categories = array();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			"SELECT membership_id, GROUP_CONCAT(category_id ORDER BY category_id SEPARATOR ', ') as category_ids
			FROM {$wpdb->prefix}pmpro_memberships_categories
			GROUP BY membership_id"
		);
		foreach ( $results as $row ) {
			$protected_categories[ $row->membership_id ] = $row->category_ids;
		}

		// Get protected pages and posts.
		$protected_pages = array();
		$protected_posts = array();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			"SELECT mp.membership_id, 
					GROUP_CONCAT(CASE WHEN p.post_type = 'page' THEN mp.page_id END ORDER BY mp.page_id SEPARATOR ', ') as page_ids,
					GROUP_CONCAT(CASE WHEN p.post_type = 'post' THEN mp.page_id END ORDER BY mp.page_id SEPARATOR ', ') as post_ids
			FROM {$wpdb->prefix}pmpro_memberships_pages mp
			INNER JOIN {$wpdb->posts} p ON mp.page_id = p.ID
			GROUP BY mp.membership_id"
		);
		foreach ( $results as $row ) {
			if ( $row->page_ids ) {
				$protected_pages[ $row->membership_id ] = $row->page_ids;
			}
			if ( $row->post_ids ) {
				$protected_posts[ $row->membership_id ] = $row->post_ids;
			}
		}

		$data = array();
		foreach ( $levels as $l ) {
			// Format group with ID
			$group = '';
			if ( isset( $level_groups[ $l->id ] ) && isset( $level_group_ids[ $l->id ] ) ) {
				$group_names = explode( ', ', $level_groups[ $l->id ] );
				$group_ids   = explode( ', ', $level_group_ids[ $l->id ] );
				$formatted_groups = array();
				foreach ( $group_names as $index => $name ) {
					if ( isset( $group_ids[ $index ] ) ) {
						$formatted_groups[] = $name . ' (ID: ' . $group_ids[ $index ] . ')';
					}
				}
				$group = implode( ', ', $formatted_groups );
			}
			
			$cycle         = $l->cycle_number > 0 ? $l->cycle_number . ' ' . $l->cycle_period . ( $l->cycle_number > 1 ? 's' : '' ) : '-';
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
				'description'           => $l->description ? wp_kses_post( $l->description ) : '',
				'confirmation'          => $l->confirmation ? wp_kses_post( $l->confirmation ) : '',
				'account_message'       => isset( $l->account_message ) ? wp_kses_post( $l->account_message ) : '',
				'group'                 => $group,
				'members'               => isset( $member_counts[ $l->id ] ) && $member_counts[ $l->id ] > 0 ? '<a href="' . esc_url( admin_url( 'admin.php?page=pmpro-memberslist&l=' . $l->id ) ) . '">' . $member_counts[ $l->id ] . '</a>' : 0,
				'orders'                => isset( $order_counts[ $l->id ] ) && $order_counts[ $l->id ] > 0 ? '<a href="' . esc_url( admin_url( 'admin.php?page=pmpro-orders&l=' . $l->id . '&filter=within-a-level' ) ) . '">' . $order_counts[ $l->id ] . '</a>' : 0,
				'protected_categories'  => isset( $protected_categories[ $l->id ] ) ? $protected_categories[ $l->id ] : '',
				'protected_pages'       => isset( $protected_pages[ $l->id ] ) ? $protected_pages[ $l->id ] : '',
				'protected_posts'       => isset( $protected_posts[ $l->id ] ) ? $protected_posts[ $l->id ] : '',
				'initial'               => $l->initial_payment > 0 ? '$' . number_format( $l->initial_payment, 2 ) : '-',
				'billing'               => $l->billing_amount > 0 ? '$' . number_format( $l->billing_amount, 2 ) : '-',
				'cycle'                 => $cycle,
				'billing_limit_display' => $l->billing_limit > 0 ? $l->billing_limit : 'Unlimited',
				'trial_enabled'         => $trial_enabled,
				'trial'                 => $trial,
				'trial_limit_display'   => $l->trial_limit > 0 ? $l->trial_limit : '-',
				'expiration'            => $l->expiration_number > 0 ? $l->expiration_number . ' ' . $l->expiration_period : 'Never',
				'signups'               => $l->allow_signups ? '<a href="' . esc_url( pmpro_url( 'checkout', '?level=' . $l->id ) ) . '" target="_blank">Yes</a>' : 'No',
				'signups_filter'        => $l->allow_signups ? 'Yes' : 'No',
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
