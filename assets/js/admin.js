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

	// Function to format child row content
	function formatChildRow( d ) {
		var html = '<div class="child-row-content">';
		html += '<table class="child-details">';
		
		// Description
		var description = d.description ? d.description : '<em>No description</em>';
		html += '<tr><td><strong>Description:</strong></td><td>' + description + '</td></tr>';
		
		// Confirmation Message
		var confirmation = d.confirmation ? d.confirmation : '<em>No confirmation message</em>';
		html += '<tr><td><strong>Confirmation Message:</strong></td><td>' + confirmation + '</td></tr>';
		
		// Account Message
		var accountMessage = d.account_message ? d.account_message : '<em>No account message</em>';
		html += '<tr><td><strong>Account Message:</strong></td><td>' + accountMessage + '</td></tr>';
		
		// Protected Categories
		var categories = '<em>No protected categories</em>';
		if ( d.protected_categories ) {
			var categoryIds = d.protected_categories.split( ', ' );
			categories = categoryIds.map( function( id ) {
				return '<a href="term.php?taxonomy=category&tag_ID=' + id + '&post_type=post" target="_blank">' + id + '</a>';
			} ).join( ', ' );
		}
		html += '<tr><td><strong>Protected Categories:</strong></td><td>' + categories + '</td></tr>';
		
		// Protected Pages
		var pages = '<em>No protected pages</em>';
		if ( d.protected_pages ) {
			var pageIds = d.protected_pages.split( ', ' );
			pages = pageIds.map( function( id ) {
				return '<a href="post.php?post=' + id + '&action=edit" target="_blank">' + id + '</a>';
			} ).join( ', ' );
		}
		html += '<tr><td><strong>Protected Pages:</strong></td><td>' + pages + '</td></tr>';
		
		html += '</table>';
		html += '</div>';
		return html;
	}

	var table = new DataTable( '#levels-table', {
		data: levelsData,
		columns: [
			{
				className: 'dt-control',
				orderable: false,
				data: null,
				defaultContent: ''
			},
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

			// Add click event for expanding/collapsing child rows
			$( '#levels-table tbody' ).on( 'click', 'td.dt-control', function() {
				var tr = $( this ).closest( 'tr' );
				var row = api.row( tr );

				if ( row.child.isShown() ) {
					row.child.hide();
					tr.removeClass( 'shown' );
				} else {
					row.child( formatChildRow( row.data() ) ).show();
					tr.addClass( 'shown' );
				}
			} );

			// Add filter dropdowns for Group, Cycle, Trial Enabled, Expiration, Allow Signups.
			api.columns( [ 3, 8, 10, 13, 14 ] ).every( function() {
				var column = this;
				var title = $( column.header() ).text();
				var label = title.endsWith( 's' ) ? title : title + 's';
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
