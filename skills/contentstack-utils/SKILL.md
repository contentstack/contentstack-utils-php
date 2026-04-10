---
name: contentstack-utils
description: Use when changing Utils, GQL, BaseParser, models, enums, or embed/render behavior — the library’s public surface and integration boundaries.
---

# Contentstack Utils API – Contentstack Utils PHP

## When to use

- Adding or changing HTML/RTE rendering or GraphQL JSON → HTML conversion
- Extending or implementing render callbacks (`Option`, `RenderableInterface`)
- Working with metadata, embedded items, or enum-backed node/mark types

## Instructions

### Package identity

- Composer package: `contentstack/utils` ([Packagist](https://packagist.org/packages/contentstack/utils)); PSR-4 root namespace `Contentstack\Utils\` maps to `src/`.

### Main entry points (illustrative)

- **`Contentstack\Utils\Utils`** ([`src/Utils.php`](../../src/Utils.php)) — static helpers such as `renderContent` / `jsonToHtml` for RTE strings and JSON payloads; extends `BaseParser`.
- **`Contentstack\Utils\GQL`** ([`src/GQL.php`](../../src/GQL.php)) — `jsonToHtml` and related parsing for GraphQL-shaped content; extends `BaseParser`.
- **`Contentstack\Utils\BaseParser`** ([`src/BaseParser.php`](../../src/BaseParser.php)) — shared parsing and embedded-object resolution logic.

### Extension / integration

- **`Contentstack\Utils\Model\Option`** — configure rendering; subclasses override `renderMark`, `renderNode`, `renderOptions`, etc.
- **`Contentstack\Utils\Resource\RenderableInterface`** — contract for types that participate in rendering.
- **`Contentstack\Utils\Model\Metadata`** — embed metadata (e.g. style type, attributes).
- **Enums** under `Contentstack\Utils\Enum\` (`NodeType`, `MarkType`, `StyleType`, `EmbedItemType`) use `marc-mabe/php-enum`.

### Boundaries

- This package does not ship HTTP calls; consumers fetch data with the Contentstack PHP SDK or other clients and pass strings/objects into `Utils` / `GQL`.
- Prefer backward-compatible changes to public method signatures; follow SemVer.
