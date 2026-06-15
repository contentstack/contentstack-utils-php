---
name: testing
description: Use when writing PHPUnit tests, mocks under tests/Mock, or coverage/output under build/ for this package.
---

# Testing – Contentstack Utils PHP

## When to use

- Adding or updating unit tests for `Utils`, `GQL`, models, or helpers
- Extending mocks/fixtures under `tests/Mock` or `tests/Helpers`
- Interpreting PHPUnit config, coverage, or CI test runs

## Instructions

### Runner and config

- **Command:** `composer test` → PHPUnit (`phpunit/phpunit` ^9.3).
- **Config:** [`phpunit.xml`](../../phpunit.xml) — bootstrap `vendor/autoload.php`, suite directory `tests/`, whitelist coverage on `src/`.

### Layout

- Test classes live directly under `tests/` (e.g. `UtilsTest.php`, `GQLTest.php`, `MetadataTest.php`) plus **`tests/Mock/`** (`JsonMock.php`, `EmbedObjectMock.php`, etc.) and **`tests/Helpers/`** (`Utility.php`).

### Coverage and reports

- `phpunit.xml` logs JUnit, coverage (HTML, text, Clover), and testdox output under **`build/`** (e.g. `build/coverage/`, `build/logs/`). Add `build/` to `.gitignore` if generating locally; do not commit generated artifacts unless the project asks for them.

### Expectations for contributions

- [`CONTRIBUTING.md`](../../CONTRIBUTING.md) requires tests for changes; new behavior should include PHPUnit coverage in the same PR where feasible.

### Credentials

- Tests use mocks and fixtures; do not add real API keys or stack secrets to the repo.
