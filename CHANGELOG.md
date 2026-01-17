# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.5.0] - 2025-01-17

### Added
- New "Members/Orders" filter dropdown with options: Has Members, No Active Members, Has Orders, Never had Orders
- Visual feedback for active filters - dropdown titles become bold and show selected option (e.g., "Custom Trials: Disabled")
- Smooth table loading with fade-in effect to eliminate flashing on page load

### Changed
- Column "Billing Limit" renamed to "Recurring Limit" with improved display format showing "X Cycle(s)"
- Billing cycle periods now display with "(s)" suffix (e.g., "2 Month(s)" instead of "2 Months")
- Enhanced filter UX with prefix display showing filter category and selected value

### Fixed
- Resolved pagination issue where table rows were being appended/prepended instead of properly replaced
- Improved DataTables DOM management and event handling for better stability

### Improved
- Refactored JavaScript code for better maintainability and performance
- Enhanced state persistence for all filter types including the new Members/Orders filter
- Better visual hierarchy with bold styling for active filter states

## [1.4.3] - 2025-12-13

### Changed
- Replaced manual search placeholder setting with native DataTables `language.searchPlaceholder` option
- Simplified Reset Filters functionality using DataTables method chaining for better performance
- Optimized filter value collection using native array methods and functional programming
- Removed unnecessary render function from ID column that just returned data as-is

### Improved
- Reduced custom DOM manipulation in favor of native DataTables features
- Better performance with fewer DOM updates through method chaining
- Cleaner, more maintainable code following DataTables best practices
- More efficient unique value filtering for dropdown options

## [1.4.2] - 2025-12-13

### Added
- Hidden Custom Trial column for native DataTables filtering
- `pmpro_level_explorer_state_save` filter to disable table state persistence

### Changed
- Simplified Custom Trials filter using DataTables native columnDefs instead of complex custom logic
- Removed manual state management for Custom Trials filter in favor of native column filtering
- Uses DataTables `columnDefs` with `visible: false` for proper column hiding

### Fixed
- Custom Trials filter now uses native DataTables state saving/restoration
- Significantly reduced code complexity (~50 lines of custom logic removed)
- More reliable and maintainable filter implementation

## [1.4.1] - 2025-12-12

### Added
- Expand All/Collapse All button for managing all child rows at once
- Custom Trials filter dropdown (independent of column display)

### Changed
- Merged DataTables control (expand/collapse caret) with ID column to save horizontal space
- Removed Custom Trial column but kept filter functionality
- Repositioned Custom Trials filter after Billing Limits dropdown for better organization
- Made "Yes" in Allow Signups column clickable (removed redundant "Checkout" text)

### Fixed
- Custom Trials filter now properly resets with Reset Filters button
- Improved filter dropdown positioning and organization
- Enhanced expand/collapse button state management

## [1.4.0] - 2025-12-09

### Added
- Table state persistence: automatically remembers sort order, page length, filters, and search across sessions
- Filter dropdowns now restore previously selected values on page reload

### Changed
- Reset Filters button now clears saved state and resets table to defaults without page reload

## [1.3.5] - 2025-12-09

### Added
- Orders column with clickable count linking to filtered orders page
- Checkout link in Allow Signups column when signups are enabled
- Clickable member count linking to filtered members list
- Separated Protected Posts from Protected Pages in child rows
- Group ID now displayed inline with group name (e.g., "Group Name (ID: 5)")
- Plural support for billing cycle periods (e.g., "3 Months", "1 Day")

### Changed
- Removed standalone Group ID column (merged into Group column)
- Changed default sorting back to ID descending
- Updated child row labels to clarify IDs are displayed (e.g., "Protected Post IDs")
- Removed Checkout link from Actions column (kept in Allow Signups)

### Fixed
- Allow Signups filter dropdown now shows clean "Yes/No" options
- Fixed pagination lengthMenu configuration
- Fixed filter column indices after adding Orders column

## [1.3.0] - 2025-12-09

### Added
- Expandable child rows showing level details (description, confirmation message, account message)
- Protected categories and pages display in child rows with clickable edit links
- Native DataTables control column for expanding/collapsing rows

### Changed
- Removed Categories and Pages columns from main table (moved to child rows)
- Child row displays "No [field]" message when content is missing

### Security
- Added `wp_kses_post()` sanitization for description, confirmation, and account message fields

## [1.2.0] - 2025-11-30

### Added
- Group ID column to levels table (displayed after Group column)
- `pmpro_section_inside` class to DataTables wrapper for better styling integration
- `pmpro_section_actions` class to pagination row for consistent layout
- Separate CHANGELOG.md file following Keep a Changelog format

### Changed
- Default sorting changed to Group ID ascending (from ID descending)
- README.md now references CHANGELOG.md for version history
- Merged Description section into main paragraph in README

### Fixed
- Added `defaultContent` fallback for group_id column to prevent JavaScript errors when data is missing

## [1.1.0] - 2025-11-30

### Added
- Developer hooks for customization:
  - `pmpro_level_explorer_default_order` - Customize default sort column/direction
  - `pmpro_level_explorer_page_length` - Customize default page length
  - `pmpro_level_explorer_length_menu` - Customize pagination options
- Robust dependency checking with `function_exists()`
- Text domain loading for translations

### Changed
- Default sort to ID descending (newest levels first)
- "Add New Level" button now links to advanced level template
- Moved asset enqueuing to `admin_enqueue_scripts` hook
- Guarded constant definitions for better compatibility

### Fixed
- Proper capability checks in render method
- PHPCS compliance with proper database query annotations
- Improved WordPress Coding Standards compliance

## [1.0.0] - 2024-11-30

### Added
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

[unreleased]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.4.3...HEAD
[1.4.3]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.4.2...v1.4.3
[1.4.2]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.4.1...v1.4.2
[1.4.1]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.4.0...v1.4.1
[1.4.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.3.5...v1.4.0
[1.3.5]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.3.0...v1.3.5
[1.3.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/releases/tag/v1.0.0
