# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[unreleased]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.2.0...HEAD
[1.2.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/raphaelsuzuki/pmpro-level-explorer/releases/tag/v1.0.0
