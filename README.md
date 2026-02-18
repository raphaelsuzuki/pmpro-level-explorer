# PMPro Level Explorer

[![CI](https://github.com/raphaelsuzuki/pmpro-level-explorer/actions/workflows/ci.yml/badge.svg)](https://github.com/raphaelsuzuki/pmpro-level-explorer/actions/workflows/ci.yml)
[![PHPCS](https://github.com/raphaelsuzuki/pmpro-level-explorer/actions/workflows/phpcs.yml/badge.svg)](https://github.com/raphaelsuzuki/pmpro-level-explorer/actions/workflows/phpcs.yml)
[![PHPUnit](https://github.com/raphaelsuzuki/pmpro-level-explorer/actions/workflows/phpunit.yml/badge.svg)](https://github.com/raphaelsuzuki/pmpro-level-explorer/actions/workflows/phpunit.yml)

Enhanced level management for Paid Memberships Pro with advanced filtering, sorting, and search powered by DataTables. PMPro Level Explorer adds a powerful admin interface to manage membership levels efficiently, perfect for sites with many levels.

## Dependabot

Dependabot is enabled for this repository to automatically manage dependency updates.

- **What it does:** Automatically checks for outdated or vulnerable Composer dependencies and opens pull requests.
- **Schedule:** Weekly (Monday mornings).
- **How to review:** When a Dependabot PR is opened, review the included changelog and verify that the CI checks (PHPCS and PHPUnit) have passed.
- **How to merge:** Once verified, you can merge the PR directly through the GitHub interface.


## Features

- **DataTables Integration** - Fast, responsive table with sorting, pagination and all level details at a glance
- **Advanced Filtering** - Filter by Group, Cycle, Trial Enabled, Expiration, and New Signups Disabled
- **Live Search** - Instantly search levels by name or any other information
- **State Persistence** - Automatically remembers your sort order, page length, filters, and search across sessions
- **Active Member Counts** - See real-time active member counts per level
- **Order Counts** - View total orders per level with direct links to filtered order pages
- **Expandable Details** - Click to expand rows and view descriptions, messages, and protected content
- **PMPro Groups Support** - Displays levels organized by PMPro Groups with IDs
- **Quick Actions** - Edit, Copy, and Delete levels directly from the table
- **PMPro Design System** - Matches PMPro's admin styling as much as possible
- **Lightweight** - Locally hosted DataTables 2.3.5, no CDN dependencies

## Requirements

- **WordPress:** 5.0 or higher
- **PHP:** 7.4 or higher  
- **Paid Memberships Pro:** Latest version recommended
- **Tested up to:** WordPress 6.4
- **Stable tag:** 1.4.3

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

**Repository:** `raphaelsuzuki/pmpro-level-explorer`  
**Branch:** `main`

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

Click **Reset Filters** to clear all filters and reset the table to defaults.

### State Persistence

The table automatically remembers your preferences across sessions:

- Current page number
- Page length (25, 50, 100, 500)
- Sort order and column
- Search term
- Filter selections

Your preferences are saved in your browser and persist until you click "Reset Filters" or clear your browser data.

### Search

Type in the search box to filter levels by name in real-time.

### Pagination

Choose to display 25, 50, 100, or 500 levels per page.

### Actions

- **Edit** - Opens PMPro's edit page for the level
- **Copy** - Creates a copy of the level
- **Delete** - Deletes the level (with confirmation)

## Screenshots

1. **Main Level Explorer Interface** - Enhanced DataTables view with filtering, search, and expandable rows
2. **Expanded Row Details** - View level descriptions, messages, and protected content
3. **Advanced Filtering** - Multiple filter dropdowns for efficient level management
4. **State Persistence** - Table remembers your preferences across sessions


## For Developers

### Hooks and Filters

The plugin provides several hooks for customization:

#### Customize Default Sorting

Change the default sort column and direction:

```php
add_filter( 'pmpro_level_explorer_default_order', function( $order ) {
    return array( 1, 'asc' ); // Sort by Name (column 1) ascending
    // Column indices: 0=ID, 1=Name, 2=Group, 3=Members, etc.
} );
```

#### Customize Page Length

Change the default number of levels per page:

```php
add_filter( 'pmpro_level_explorer_page_length', function( $length ) {
    return 50; // Show 50 levels per page
} );
```

#### Customize Length Menu

Change the available page length options:

```php
add_filter( 'pmpro_level_explorer_length_menu', function( $menu ) {
    return array( 10, 25, 50, 100 ); // Custom options
} );
```

#### Disable State Saving

Disable table state persistence (sort order, filters, search, etc.):

```php
add_filter( 'pmpro_level_explorer_state_save', '__return_false' );
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

See [CHANGELOG.md](CHANGELOG.md) for version history.

## Upgrade Notice

### 1.4.3
Code optimization release: Replaced custom DOM manipulation with native DataTables features. Improved performance and maintainability.

### 1.4.2
Simplified Custom Trials filter using native DataTables features. Added configurable state saving option. Significantly reduced code complexity.

### 1.4.1
Enhanced UI with better filter organization and expand/collapse functionality. Custom Trials filter repositioned for better UX.

### 1.4.0
Major update: Added table state persistence, expand/collapse all functionality, and improved filter management.

### 1.3.5
Added Orders column, checkout links, and enhanced child row details. Improved space utilization and user experience.

## Support

- **Issues:** [GitHub Issues](https://github.com/raphaelsuzuki/pmpro-level-explorer/issues)
- **Documentation:** This README and inline help
- **Updates:** Automatic via Git Updater

## Contributing

Contributions are welcome! Please submit pull requests or open issues on GitHub.

- **Repository:** https://github.com/raphaelsuzuki/pmpro-level-explorer
- **Bug Reports:** Use GitHub Issues for bug reports and feature requests
- **Pull Requests:** Follow WordPress Coding Standards

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Disclaimer

This repository and its documentation were created with the assistance of AI. While efforts have been made to ensure accuracy and completeness, no guarantee is provided. Use at your own risk. Always test in a safe environment before deploying to production.
