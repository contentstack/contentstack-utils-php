---
name: dev-workflow
description: Use when running Composer, CI, or PRs to master/next — installs, tests, and branch policy for this repo.
---

# Dev workflow – Contentstack Utils PHP

## When to use

- Installing dependencies or running the test suite locally
- Understanding what GitHub Actions run on push/PR
- Opening or reviewing a PR and matching branch expectations

## Instructions

### Local setup

- From the repo root: `composer install` (use `composer install --ignore-platform-reqs` only if you must match CI’s relaxed install; see `.github/workflows/ci.yml`).
- Run tests: `composer test` (runs PHPUnit as defined in `composer.json`).

### Lint

- Check: `composer check-style` (PHPCS on `src` and `tests`).
- Autofix where supported: `composer fix-style` (PHPCBF).

### CI

- [`ci.yml`](../../.github/workflows/ci.yml): `composer validate`, cache + `composer install`, then `composer run-script test`.
- Lint is not currently enforced in that workflow; still run `check-style` before pushing.

### Branch / PR policy

- [`check-branch.yml`](../../.github/workflows/check-branch.yml): PRs **into `master`** must use branch **`next`** as the head branch (automation comments and fails otherwise).
- Prefer feature branches (not your fork’s default branch name as the only source) per [`CONTRIBUTING.md`](../../CONTRIBUTING.md).

### Releases / versioning

- Project follows SemVer per [`CONTRIBUTING.md`](../../CONTRIBUTING.md); public API changes belong in changelog/release notes as the maintainers require.
