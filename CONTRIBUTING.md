# Contributing to PMPro Level Explorer

Thank you for your interest in contributing! Please follow these guidelines to ensure a smooth contribution process.

## Pull Requests

1.  Fork the repository and create your branch from `main`.
2.  Ensure your code follows [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
3.  All Pull Requests must pass the CI checks (PHPCS and PHPUnit) before being merged.

## Local Development Tools

We use Composer to manage development dependencies and run tests.

### PHP Coding Standards (PHPCS)

To check your code for standards violations according to the WordPress Coding Standards:

```bash
composer phpcs
```

To automatically fix many common coding standards violations:

```bash
composer phpcbf
```

### Unit Testing (PHPUnit)

To run the test suite locally:

1.  **Prepare the test environment:**
    You need a local MySQL database for testing. Run the following command to set up the WordPress test library (replace `db_user` and `db_password` as needed):
    ```bash
    bash bin/install-wp-tests.sh wordpress_tests db_user 'db_password' localhost latest
    ```

2.  **Run the tests:**
    ```bash
    composer test
    ```

3.  **Run tests with code coverage:**
    ```bash
    composer test:coverage
    ```

## CI/CD Pipeline

This repository uses GitHub Actions for:
- **PHPCS:** Verifying WordPress Coding Standards.
- **PHPUnit:** Running tests across multiple PHP (7.4, 8.0, 8.1) and WordPress versions.
- **Dependabot:** Managing automated dependency updates.
