# PMPro Level Explorer

Enhanced level management for Paid Memberships Pro with advanced filtering, sorting, and search powered by DataTables.

## Description

PMPro Level Explorer adds a powerful admin interface to manage membership levels efficiently, perfect for sites with many levels.

## Features

- **DataTables Integration** - Fast, responsive table with sorting, pagination and all level details at a glance
- **Advanced Filtering** - Filter by Group, Cycle, Trial Enabled, Expiration, and New Signups Disabled
- **Live Search** - Instantly search levels by name or any other information
- **Active Member Counts** - See real-time active member counts per level
- **PMPro Groups Support** - Displays levels organized by PMPro Groups
- **Quick Actions** - Edit, Copy, and Delete levels directly from the table
- **PMPro Design System** - Matches PMPro's admin styling as much as possible
- **Lightweight** - Locally hosted DataTables 2.3.5, no CDN dependencies

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Paid Memberships Pro (active)

## Installation

### Manual Installation

1. Upload the `pmpro-level-explorer` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to **Memberships → Level Explorer** to use it

### Automatic Updates via Git Updater

This plugin supports automatic updates through [Git Updater](https://git-updater.com/).

1. Install and activate [Git Updater](https://github.com/afragen/git-updater)
2. Install this plugin using any method above
3. Git Updater will automatically check for updates from the GitHub repository
4. Update notifications will appear in your WordPress admin dashboard

## Usage

### Accessing Level Explorer

Go to **Memberships → Level Explorer** in your WordPress admin menu.

### Table Columns

- **ID** - Level ID
- **Name** - Level name
- **Group** - PMPro Group(s) the level belongs to
- **Members** - Active member count
- **Initial** - Initial payment amount
- **Billing** - Recurring billing amount
- **Cycle** - Billing cycle (e.g., "1 Month")
- **Billing Limit** - Number of billing cycles
- **Trial Enabled** - Whether trial is enabled
- **Trial** - Trial amount
- **Trial Limit** - Trial billing limit
- **Expiration** - Membership expiration period
- **New Signups** - Whether new signups are allowed
- **Actions** - Edit, Copy, Delete

### Filtering

Use the dropdown filters to narrow down levels:

- **Group** - Filter by PMPro Group
- **Cycle** - Filter by billing cycle
- **Trial Enabled** - Show only enabled/disabled trials
- **Expiration** - Filter by expiration period
- **New Signups** - Filter by signup status

Click **Reset Filters** to clear all filters.

### Search

Type in the search box to filter levels by name in real-time.

### Pagination

Choose to display 25, 50, 100, or 500 levels per page.

### Actions

- **Edit** - Opens PMPro's edit page for the level
- **Copy** - Creates a copy of the level
- **Delete** - Deletes the level (with confirmation)

## Technical Details

### WordPress Coding Standards

- Fully compliant with WordPress Coding Standards
- phpDoc documentation for all functions and methods
- Proper escaping and sanitization
- Translatable strings with text domain

### Database Queries

- Queries PMPro's native tables
- Supports PMPro Groups via JOIN queries
- Efficient member count aggregation

### Assets

- DataTables 2.3.5 (locally hosted)
- Custom CSS using PMPro design system variables
- Minimal JavaScript for filter initialization

## Compatibility

- ✅ Standard PMPro levels
- ✅ PMPro Groups
- ✅ Multisite compatible

## Developer Hooks

### Customize Default Sorting

Change the default sort column and direction:

```php
add_filter( 'pmpro_level_explorer_default_order', function( $order ) {
    return array( 1, 'asc' ); // Sort by Name (column 1) ascending
    // Column indices: 0=ID, 1=Name, 2=Group, 3=Members, etc.
} );
```

### Customize Page Length

Change the default number of levels per page:

```php
add_filter( 'pmpro_level_explorer_page_length', function( $length ) {
    return 50; // Show 50 levels per page
} );
```

### Customize Length Menu

Change the available page length options:

```php
add_filter( 'pmpro_level_explorer_length_menu', function( $menu ) {
    return array( 10, 25, 50, 100 ); // Custom options
} );
```

## Frequently Asked Questions

### Does this replace PMPro's levels page?

No, it creates a separate page. PMPro's original levels page remains unchanged.

### Can I use both pages?

Yes! Use whichever interface you prefer for different tasks.

### Does it work with PMPro Groups?

Yes! Levels are automatically organized by their PMPro Groups.

### Does it modify the database?

No, it only reads from PMPro's existing tables.

## Changelog

### 1.1.0
- Enhanced: Improved WordPress Coding Standards compliance
- Enhanced: Added robust dependency checking with `function_exists()`
- Enhanced: Moved asset enqueuing to `admin_enqueue_scripts` hook
- Enhanced: Added text domain loading for translations
- Enhanced: Guarded constant definitions for better compatibility
- Added: Developer hooks for customization:
  - `pmpro_level_explorer_default_order` - Customize default sort column/direction
  - `pmpro_level_explorer_page_length` - Customize default page length
  - `pmpro_level_explorer_length_menu` - Customize pagination options
- Changed: Default sort to ID descending (newest levels first)
- Changed: "Add New Level" button now links to advanced level template
- Fixed: Proper capability checks in render method
- Fixed: PHPCS compliance with proper database query annotations

### 1.0.0
- Initial release
- DataTables 2.3.5 integration (locally hosted)
- Advanced filtering (Group, Cycle, Trial Enabled, Expiration, New Signups)
- Live search functionality with 300px search input
- Active member counts per level
- PMPro Groups support with proper JOIN queries
- Quick actions: Edit, Copy, Delete with confirmation
- PMPro design system styling (CSS variables)
- WordPress Coding Standards compliant
- Full phpDoc documentation
- Git Updater support for automatic updates

## Contributing

Contributions are welcome! Please submit pull requests or open issues on GitHub.

## Support

For issues and feature requests, please use the GitHub repository:
https://github.com/raphaelsuzuki/pmpro-level-explorer

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Credits

- Built for Paid Memberships Pro
- Powered by DataTables 2.3.5
- Developed by Raphael Suzuki
