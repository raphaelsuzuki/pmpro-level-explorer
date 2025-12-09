# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[unreleased]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.3.5...HEAD
[1.3.5]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.3.0...v1.3.5
[1.3.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/releases/tag/v1.0.0
