# Contentstack Utils PHP – Agent guide

**Universal entry point** for contributors and AI agents. Detailed conventions live in **`skills/*/SKILL.md`**.

## What this repo is

| Field | Detail |
| --- | --- |
| **Name:** | [contentstack/utils](https://packagist.org/packages/contentstack/utils) · [GitHub](https://github.com/contentstack/contentstack-utils-php) |
| **Purpose:** | PHP library for rendering Contentstack Rich Text (RTE) content and GraphQL-shaped JSON to HTML, including embedded entries and custom render options. |
| **Out of scope (if any):** | Not an HTTP client or full Contentstack Delivery SDK; it focuses on parsing/rendering utilities used alongside other Contentstack PHP packages. |

## Tech stack (at a glance)

| Area | Details |
| --- | --- |
| Language | PHP `>=7.2` (Composer); CI runs on PHP 8.3. `declare(strict_types=1);` in source files. |
| Build | [Composer](https://getcomposer.org/) — `composer.json`, `composer.lock`. No compile step; autoload PSR-4 `Contentstack\Utils\` → `src/`. |
| Tests | PHPUnit 9.x via `composer test` / `phpunit`; suite in `tests/`, config `phpunit.xml`. |
| Lint / coverage | PHP_CodeSniffer (PSR-2 ruleset) — `composer check-style` / `composer fix-style`; config `phpcs.xml.dist`. Coverage/logging paths under `build/` when generated. |
| Other | Dev dependency `marc-mabe/php-enum` for enums. |

## Commands (quick reference)

| Command type | Command |
| --- | --- |
| Install deps | `composer install` |
| Test | `composer test` |
| Lint | `composer check-style` |
| Format (fix) | `composer fix-style` |

**CI:** [`.github/workflows/ci.yml`](.github/workflows/ci.yml) — validates `composer.json` / lockfile, installs dependencies, runs `composer run-script test`. Other workflows: `sca-scan.yml`, `policy-scan.yml`, `issues-jira.yml`, `check-branch.yml`.

## Where the documentation lives: skills

| Skill | Path | What it covers |
| --- | --- | --- |
| Dev workflow | [`skills/dev-workflow/SKILL.md`](skills/dev-workflow/SKILL.md) | Branches, CI, Composer scripts, PR expectations |
| Contentstack Utils API | [`skills/contentstack-utils/SKILL.md`](skills/contentstack-utils/SKILL.md) | Public API, namespaces, extension points (`Option`, `RenderableInterface`) |
| PHP style & layout | [`skills/php-style/SKILL.md`](skills/php-style/SKILL.md) | PSR-2, PHPCS, file layout under `src/` |
| Testing | [`skills/testing/SKILL.md`](skills/testing/SKILL.md) | PHPUnit layout, mocks, coverage output |
| Code review | [`skills/code-review/SKILL.md`](skills/code-review/SKILL.md) | PR checklist aligned with this repo |

An index with “when to use” hints is in [`skills/README.md`](skills/README.md).

## Using Cursor (optional)

If you use **Cursor**, [`.cursor/rules/README.md`](.cursor/rules/README.md) only points to **`AGENTS.md`**—same docs as everyone else.
