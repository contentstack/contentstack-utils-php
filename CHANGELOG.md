# Changelog
## [1.3.0](https://github.com/contentstack/contentstack-utils-php/tree/v1.3.0) (2026-06-03)
  - Added `Endpoint::getContentstackEndpoint()` for dynamic region-aware URL resolution
  - Added `Utils::getContentstackEndpoint()` proxy for backward-compatible access
  - Bundled `regions.json` is now downloaded at `composer install` / `composer update` via `post-install-cmd`; the file is not committed to the repository
  - Added runtime fallback in `Endpoint::loadRegions()` — downloads `regions.json` on first use when the file is absent (e.g. when the package is installed as a dependency)
  - Added `composer refresh-regions` script to manually pull the latest regions from Contentstack
  - Supports 7 regions (AWS NA/EU/AU, Azure NA/EU, GCP NA/EU) and 18 service endpoint keys

## [1.2.0](https://github.com/contentstack/contentstack-utils-php/tree/v1.2.0) (2023-06-27)
  - Support for the br tag and support for nested assets in the the image
## [1.1.0](https://github.com/contentstack/contentstack-utils-php/tree/v1.1.0) (2021-07-16)
  - JSON RTE to html content functionality for GQL API added
## [1.0.1](https://github.com/contentstack/contentstack-utils-php/tree/v1.0.1) (2021-07-16)
  - JSON RTE to html content functionality added

## [1.0.0](https://github.com/contentstack/contentstack-utils-php/tree/v1.0.0) (2021-04-05)
  - Initial release for Contentstack Utils SDK
