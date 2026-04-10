---
name: php-style
description: Use when applying PSR-2, running PHPCS, or matching src/tests layout and strict_types in this repo.
---

# PHP style & layout – Contentstack Utils PHP

## When to use

- Fixing or avoiding PHPCS violations
- Adding new classes under `src/` or tests under `tests/`
- Aligning with existing `declare(strict_types=1);` usage

## Instructions

### Coding standard

- **PHPCS** ruleset: [`phpcs.xml.dist`](../../phpcs.xml.dist) extends **PSR-2** (`<rule ref="PSR2" />`).
- Check: `composer check-style`; fix: `composer fix-style` (both target `src` and `tests` per `composer.json` scripts).

### File layout

- **Source:** `src/` — namespaces mirror directories (e.g. `Contentstack\Utils\Model\Option` → `src/Model/Option.php`).
- **Tests:** `tests/` — namespace `Contentstack\Tests\Utils\` per `composer.json` `autoload-dev`.

### PHP version

- `composer.json` requires `php: >=7.2`; avoid language features that require newer versions unless the project explicitly raises the minimum.

### Strict typing

- New PHP files in `src/` should use `declare(strict_types=1);` consistently with existing classes.
