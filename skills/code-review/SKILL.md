---
name: code-review
description: Use when reviewing or preparing a PR — tests, style, SemVer, docs, and branch rules for this repository.
---

# Code review – Contentstack Utils PHP

## When to use

- Authoring a pull request into this repo
- Reviewing someone else’s PR for completeness and repo policy

## Instructions

### Checklist

- **Tests:** New or changed behavior has PHPUnit coverage; `composer test` passes.
- **Style:** `composer check-style` passes (PSR-2 via PHPCS).
- **Docs:** User-visible behavior changes reflected in [`README.md`](../../README.md) or other maintainer-facing docs as appropriate.
- **API / SemVer:** Public signatures and documented behavior follow semantic versioning expectations per [`CONTRIBUTING.md`](../../CONTRIBUTING.md).
- **Branch policy:** PRs targeting **`master`** must come from the **`next`** branch per [`.github/workflows/check-branch.yml`](../../.github/workflows/check-branch.yml); confirm with the team if process changes.
- **Commits:** Prefer coherent history; squash noisy WIP commits when requested.

### Severity (optional labels)

- **Blocker:** Breaks tests, CI, or documented security/policy requirements.
- **Major:** Missing tests for non-trivial logic, public API breakage without version strategy, or policy violations (e.g. wrong base branch).
- **Minor:** Style nits, naming, or internal refactors with no contract change.

## References

- [`CONTRIBUTING.md`](../../CONTRIBUTING.md)
- [`PULL_REQUEST_TEMPLATE.md`](../../PULL_REQUEST_TEMPLATE.md)
- [`dev-workflow/SKILL.md`](../dev-workflow/SKILL.md)
