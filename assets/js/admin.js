/**
 * PMPro Level Explorer Admin JavaScript
 *
 * @package PMPro_Level_Explorer
 * @since 1.0.0
 */

jQuery( document ).ready( function( $ ) {
	var levelsData = pmproLevelExplorer.levels;
	var table = new DataTable( '#levels-table', {
		data: levelsData,
		columns: [
			{ data: 'id' },
			{ data: 'name' },
			{ data: 'group' },
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
		pageLength: 25,
		lengthMenu: [ [ 25, 50, 100, 500 ], [ 25, 50, 100, 500 ] ],
		order: [ [ 1, 'asc' ] ],
		initComplete: function() {
			var api = this.api();
			var filters = $( '#table-filters' );

			$( '.dt-search input' ).attr( 'placeholder', 'Search...' );

			// Add filter dropdowns for Group, Cycle, Expiration, Trial Enabled, New Signups.
			api.columns( [ 2, 6, 8, 11, 12 ] ).every( function() {
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
