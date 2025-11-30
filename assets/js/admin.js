/**
 * PMPro Level Explorer Admin JavaScript
 *
 * @package PMPro_Level_Explorer
 * @since 1.0.0
 */

jQuery( document ).ready( function( $ ) {
	var levelsData = pmproLevelExplorer.levels;
	var defaultOrder = pmproLevelExplorer.defaultOrder || [ 0, 'desc' ];
	var pageLength = pmproLevelExplorer.pageLength || 25;
	var lengthMenu = pmproLevelExplorer.lengthMenu || [ 25, 50, 100, 500 ];

	var table = new DataTable( '#levels-table', {
		data: levelsData,
		columns: [
			{ data: 'id' },
			{ data: 'name' },
			{ data: 'group' },
			{ data: 'group_id', defaultContent: '' },
			{ data: 'members' },
			{ data: 'initial' },
			{ data: 'billing' },
			{ data: 'cycle' },
			{ data: 'billing_limit_display' },
			{ data: 'trial_enabled' },
			{ data: 'trial' },
			{ data: 'trial_limit_display' },
			{ data: 'expiration' },
			{ data: 'signups' },
			{ data: 'actions', orderable: false }
		],
		pageLength: pageLength,
		lengthMenu: [ lengthMenu, lengthMenu ],
		order: [ defaultOrder ],
		initComplete: function() {
			var api = this.api();
			var filters = $( '#table-filters' );

			// Add pmpro_section_inside class to the wrapper
			$( '#levels-table_wrapper' ).addClass( 'pmpro_section_inside' );

			// Add pmpro_section_actions class to the last dt-layout-row
			$( '#levels-table_wrapper > .dt-layout-row' ).last().addClass( 'pmpro_section_actions' );

			$( '.dt-search input' ).attr( 'placeholder', 'Search...' );

			// Add filter dropdowns for Group, Cycle, Trial Enabled, Expiration, New Signups.
			api.columns( [ 2, 7, 9, 12, 13 ] ).every( function() {
				var column = this;
				var title = $( column.header() ).text();
				var label = title.endsWith( 's' ) ? 'All ' + title : 'All ' + title + 's';
				var select = $( '<select><option value="">' + label + '</option></select>' )
					.appendTo( filters )
					.on( 'change', function() {
						column.search( $( this ).val(), { exact: true } ).draw();
					} );

				column.data().unique().sort().each( function( d ) {
					if ( d ) {
						select.append( '<option value="' + d + '">' + d + '</option>' );
					}
				} );
			} );

			// Add reset filters button.
			$( '<button type="button" class="button">Reset Filters</button>' )
				.appendTo( filters )
				.on( 'click', function() {
					$( '#table-filters select' ).val( '' );
					api.search( '' ).columns().search( '' ).draw();
				} );
		}
	} );
} );
