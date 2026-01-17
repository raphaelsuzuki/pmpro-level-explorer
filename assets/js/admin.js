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
	var stateSave = pmproLevelExplorer.stateSave !== undefined ? pmproLevelExplorer.stateSave : true;

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
		html += '<tr><td><strong>Protected Category IDs:</strong></td><td>' + categories + '</td></tr>';
		
		// Protected Pages
		var pages = '<em>No protected pages</em>';
		if ( d.protected_pages ) {
			var pageIds = d.protected_pages.split( ', ' );
			pages = pageIds.map( function( id ) {
				return '<a href="post.php?post=' + id + '&action=edit" target="_blank">' + id + '</a>';
			} ).join( ', ' );
		}
		html += '<tr><td><strong>Protected Page IDs:</strong></td><td>' + pages + '</td></tr>';
		
		// Protected Posts
		var posts = '<em>No protected posts</em>';
		if ( d.protected_posts ) {
			var postIds = d.protected_posts.split( ', ' );
			posts = postIds.map( function( id ) {
				return '<a href="post.php?post=' + id + '&action=edit" target="_blank">' + id + '</a>';
			} ).join( ', ' );
		}
		html += '<tr><td><strong>Protected Post IDs:</strong></td><td>' + posts + '</td></tr>';
		
		html += '</table>';
		html += '</div>';
		return html;
	}

	var table = new DataTable( '#levels-table', {
		data: levelsData,
		columns: [
			{ 
				data: 'id',
				className: 'dt-control'
			},
			{ data: 'name' },
			{ data: 'group' },
			{ data: 'members' },
			{ data: 'orders' },
			{ data: 'initial' },
			{ data: 'billing' },
			{ data: 'cycle' },
			{ data: 'billing_limit_display' },
			{ data: 'trial' },
			{ data: 'trial_limit_display' },
			{ data: 'custom_trial' },
			{ data: 'expiration' },
			{
				data: 'signups',
				render: function ( data, type, row ) {
					// Use signups_filter for filtering and sorting
					if ( type === 'filter' || type === 'sort' ) {
						return row.signups_filter;
					}
					return data;
				}
			},
			{ data: 'has_members' },
			{ data: 'has_orders' },
			{ data: 'actions', orderable: false }
		],
		pageLength: pageLength,
		lengthMenu: lengthMenu,
		order: [ defaultOrder ],
		stateSave: stateSave,
		stateDuration: stateSave ? -1 : 0,
		columnDefs: [
			{
				targets: [ 11, 14, 15 ], // Custom Trial, Has Members, Has Orders columns
				visible: false,
				searchable: true
			}
		],
		language: {
			info: "Showing _START_ to _END_ of _TOTAL_ entries",
			infoEmpty: "Showing 0 to 0 of 0 entries",
			infoFiltered: "(filtered from _MAX_ total entries)",
			searchPlaceholder: "Search..."
		},
		initComplete: function() {
			var api = this.api();
			var filters = $( '#table-filters' );

			// Add pmpro_section_inside class to the wrapper
			$( '#levels-table_wrapper' ).addClass( 'pmpro_section_inside' );

			// Add pmpro_section_actions class to the last dt-layout-row
			$( '#levels-table_wrapper > .dt-layout-row' ).last().addClass( 'pmpro_section_actions' );

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
				
				// Update expand/collapse all button text
				updateExpandAllButton();
			} );
			
			// Function to update expand/collapse all button text
			function updateExpandAllButton() {
				var totalRows = api.rows().count();
				var expandedRows = api.rows().nodes().to$().filter( '.shown' ).length;
				var btn = $( '#table-filters button' ).first();
				
				if ( expandedRows === totalRows && totalRows > 0 ) {
					btn.text( 'Collapse All' );
				} else {
					btn.text( 'Expand All' );
				}
			}

			// Add expand/collapse all button.
			var expandAllBtn = $( '<button type="button" class="button">Expand All</button>' )
				.appendTo( filters )
				.on( 'click', function() {
					var btn = $( this );
					var isExpanded = btn.text() === 'Collapse All';
					
					if ( isExpanded ) {
						// Collapse all rows
						api.rows().every( function() {
							if ( this.child.isShown() ) {
								this.child.hide();
								$( this.node() ).removeClass( 'shown' );
							}
						} );
						btn.text( 'Expand All' );
					} else {
						// Expand all rows
						api.rows().every( function() {
							if ( ! this.child.isShown() ) {
								this.child( formatChildRow( this.data() ) ).show();
								$( this.node() ).addClass( 'shown' );
							}
						} );
						btn.text( 'Collapse All' );
					}
				} );

			// Add filter dropdowns for Group, Cycle, Billing Limit, Custom Trial, Expiration, Allow Signups.
			api.columns( [ 2, 7, 8, 11, 12, 13 ] ).every( function() {
				var column = this;
				var columnIndex = column.index();
				var title = $( column.header() ).text();
				var label = title.endsWith( 's' ) ? title : title + 's';
				var select = $( '<select><option value="">' + label + '</option></select>' )
					.appendTo( filters )
					.on( 'change', function() {
						var selectedValue = $( this ).val();
						var $this = $( this );
						
						column.search( selectedValue, { exact: true } ).draw();
						
						// Use setTimeout to prevent flashing
						setTimeout( function() {
							// Update the first option text to show the filter when something is selected
							if ( selectedValue ) {
								$this.find( 'option[value=""]' ).text( title + ': ' + selectedValue );
								$this.addClass( 'filter-active' );
								// Reset to show the updated first option
								$this.val( '' );
							} else {
								$this.find( 'option[value=""]' ).text( label );
								$this.removeClass( 'filter-active' );
							}
						}, 0 );
					} );

				// Get unique values for this column
				var uniqueValues = [];
				if ( columnIndex === 13 ) {
					// For Allow Signups column, use signups_filter field
					uniqueValues = api.rows().data().toArray()
						.map( function( row ) { return row.signups_filter; } )
						.filter( function( val, index, arr ) { return val && arr.indexOf( val ) === index; } );
				} else {
					uniqueValues = column.data().unique().toArray().filter( Boolean );
				}

				uniqueValues.sort().forEach( function( val ) {
					select.append( '<option value="' + val + '">' + val + '</option>' );
				} );

				// Restore saved filter value from state
				var savedSearch = column.search();
				if ( savedSearch ) {
					// Update the first option text to show the restored filter
					select.find( 'option[value=""]' ).text( title + ': ' + savedSearch );
					select.addClass( 'filter-active' );
					select.val( '' );
				}
			} );

			// Add Members/Orders filter dropdown
			var membersOrdersSelect = $( '<select><option value="">Members/Orders</option></select>' )
				.appendTo( filters )
				.on( 'change', function() {
					var filterValue = $( this ).val();
					var $this = $( this );
					
					// Clear both columns first
					api.column( 14 ).search( '' );
					api.column( 15 ).search( '' );
					
					// Apply the appropriate filter
					if ( filterValue === 'Has Members' ) {
						api.column( 14 ).search( 'Has Members', { exact: true } );
					} else if ( filterValue === 'No Active Members' ) {
						api.column( 14 ).search( 'No Active Members', { exact: true } );
					} else if ( filterValue === 'Has Orders' ) {
						api.column( 15 ).search( 'Has Orders', { exact: true } );
					} else if ( filterValue === 'Never had Orders' ) {
						api.column( 15 ).search( 'Never had Orders', { exact: true } );
					}
					
					// Single draw call after all filters are set
					api.draw();
					
					// Use setTimeout to prevent flashing
					setTimeout( function() {
						// Update the first option text to show the filter
						if ( filterValue ) {
							$this.find( 'option[value=""]' ).text( 'Members/Orders: ' + filterValue );
							$this.addClass( 'filter-active' );
							$this.val( '' );
						} else {
							$this.find( 'option[value=""]' ).text( 'Members/Orders' );
							$this.removeClass( 'filter-active' );
						}
					}, 0 );
				} );

			// Add options for Members/Orders filter
			membersOrdersSelect.append( '<option value="Has Members">Has Members</option>' );
			membersOrdersSelect.append( '<option value="No Active Members">No Active Members</option>' );
			membersOrdersSelect.append( '<option value="Has Orders">Has Orders</option>' );
			membersOrdersSelect.append( '<option value="Never had Orders">Never had Orders</option>' );

			// Restore saved filter value for Members/Orders
			var savedMembersSearch = api.column( 14 ).search();
			var savedOrdersSearch = api.column( 15 ).search();
			if ( savedMembersSearch ) {
				membersOrdersSelect.find( 'option[value=""]' ).text( 'Members/Orders: ' + savedMembersSearch );
				membersOrdersSelect.addClass( 'filter-active' );
				membersOrdersSelect.val( '' );
			} else if ( savedOrdersSearch ) {
				membersOrdersSelect.find( 'option[value=""]' ).text( 'Members/Orders: ' + savedOrdersSearch );
				membersOrdersSelect.addClass( 'filter-active' );
				membersOrdersSelect.val( '' );
			}

			// Add reset filters button.
			$( '<button type="button" class="button">Reset Filters</button>' )
				.appendTo( filters )
				.on( 'click', function() {
					// Reset all filters and search
					$( '#table-filters select' ).val( '' );
					
					// Reset dropdown labels to original text and remove active class
					$( '#table-filters select' ).each( function() {
						var $select = $( this );
						var originalText = $select.find( 'option[value=""]' ).text();
						
						// Remove active filter class
						$select.removeClass( 'filter-active' );
						
						// Extract original label from current text (remove any prefix)
						if ( originalText.includes( ': ' ) ) {
							var parts = originalText.split( ': ' );
							var baseLabel = parts[0];
							// Convert back to plural form for consistency
							if ( baseLabel === 'Group' ) baseLabel = 'Groups';
							else if ( baseLabel === 'Billing Cycle' ) baseLabel = 'Billing Cycles';
							else if ( baseLabel === 'Recurring Limit' ) baseLabel = 'Recurring Limits';
							else if ( baseLabel === 'Custom Trial' ) baseLabel = 'Custom Trials';
							else if ( baseLabel === 'Expiration' ) baseLabel = 'Expirations';
							else if ( baseLabel === 'Allow Signups' ) baseLabel = 'Allow Signups';
							else if ( baseLabel === 'Members/Orders' ) baseLabel = 'Members/Orders';
							
							$select.find( 'option[value=""]' ).text( baseLabel );
						}
					} );
					
					api.search( '' ).columns().search( '' ).draw();
					
					// Reset table settings to defaults
					api.page.len( pageLength ).order( defaultOrder ).draw();
					
					// Clear saved state
					if ( stateSave ) {
						api.state.clear();
					}
					
					// Reset expand/collapse functionality
					api.rows().every( function() {
						if ( this.child.isShown() ) {
							this.child.hide();
							$( this.node() ).removeClass( 'shown' );
						}
					} );
					expandAllBtn.text( 'Expand All' );
				} );
		}
	} );
} );