# Endpoint Resolution — Contentstack PHP Utils SDK

## Overview

The endpoint resolution feature provides a single function — `getContentstackEndpoint()` — that returns the correct Contentstack API URL for any **region** and any **service**, without hardcoding host strings in your application.

It mirrors the JavaScript utils SDK implementation and is backed by the official Contentstack regions registry at `https://artifacts.contentstack.com/regions.json`.

---

## Table of Contents

1. [How It Works](#1-how-it-works)
2. [Setup — regions.json](#2-setup--regionsjson)
3. [API Reference](#3-api-reference)
4. [Supported Regions](#4-supported-regions)
5. [Available Service Keys](#5-available-service-keys)
6. [Complete URL Reference](#6-complete-url-reference)
7. [Usage Examples](#7-usage-examples)
8. [Integration with PHP Delivery SDK](#8-integration-with-php-delivery-sdk)
9. [Error Handling](#9-error-handling)
10. [Keeping regions.json Up to Date](#10-keeping-regionsjson-up-to-date)
11. [Architecture](#11-architecture)

---

## 1. How It Works

```
Your Code
   │
   └── Endpoint::getContentstackEndpoint('eu', 'contentDelivery')
                         │
                         ├── 1. Check static cache (zero I/O after first call)
                         ├── 2. Read src/assets/regions.json from disk
                         ├── 3. Download from artifacts.contentstack.com (fallback)
                         │
                         ├── Normalize region → lowercase + trim
                         ├── Match by canonical ID → match by alias
                         ├── Look up service key in endpoints map
                         └── Return "https://eu-cdn.contentstack.com"
```

**Key design principles:**

- **No hardcoded URLs** — all hosts come from `regions.json`
- **No runtime HTTP calls** in normal operation — file is read from disk
- **Zero production dependencies** — uses only PHP built-ins
- **Single static cache** — `regions.json` is parsed once per PHP process
- **Backward compatible** — available on both `Endpoint` and `Utils` classes

---

## 2. Setup — regions.json

`regions.json` is **not committed** to the repository. It is downloaded automatically and lives only on disk at `src/assets/regions.json`.

### Automatic download on `composer install`

```bash
composer install
# Output:
# > @php scripts/download-regions.php
# contentstack/utils: regions.json downloaded (7 regions).
```

This also fires on `composer update`.

### When the package is used as a dependency

When another project runs `composer require contentstack/utils`, Composer does not run the library's `post-install-cmd` scripts. In that case, `Endpoint::loadRegions()` detects the missing file and **downloads it automatically on the first call** — no manual step needed.

### Manual refresh

```bash
composer refresh-regions
# contentstack/utils: regions.json downloaded (7 regions).
```

Run this whenever Contentstack announces new regions or services.

### Resolution priority

```
1. Static cache          → fastest, zero I/O, lives for the PHP process lifetime
2. src/assets/regions.json on disk  → written by composer install script
3. Live download         → fallback when file is absent (e.g. fresh dependency install)
```

---

## 3. API Reference

### `Endpoint::getContentstackEndpoint()`

```php
namespace Contentstack\Utils;

public static function getContentstackEndpoint(
    string $region    = 'us',   // Region ID or alias
    string $service   = '',     // Service key. Empty = return all endpoints
    bool   $omitHttps = false   // true = strip https:// from result
): string|array
```

Also available as a proxy on `Utils`:

```php
use Contentstack\Utils\Utils;

Utils::getContentstackEndpoint(string $region, string $service, bool $omitHttps): string|array
```

Both calls produce identical results.

### Parameters

| Parameter | Type | Default | Required | Description |
|---|---|---|---|---|
| `$region` | `string` | `'us'` | No | Region ID (`na`, `eu`, `au`, `azure-na`, `azure-eu`, `gcp-na`, `gcp-eu`) or any accepted alias. Case-insensitive. |
| `$service` | `string` | `''` | No | Service key (e.g. `contentDelivery`, `contentManagement`). When empty, the full endpoint map for the region is returned as an associative array. |
| `$omitHttps` | `bool` | `false` | No | When `true`, strips `https://` from every returned URL. Useful when passing the host to `Stack::setHost()`. |

### Return values

| `$service` | `$omitHttps` | Return type | Example |
|---|---|---|---|
| `'contentDelivery'` | `false` | `string` | `"https://eu-cdn.contentstack.com"` |
| `'contentDelivery'` | `true` | `string` | `"eu-cdn.contentstack.com"` |
| `''` (empty) | `false` | `array<string,string>` | `['contentDelivery' => 'https://...', ...]` |
| `''` (empty) | `true` | `array<string,string>` | `['contentDelivery' => 'eu-cdn...', ...]` |

### Exceptions

| Exception | Thrown when |
|---|---|
| `\InvalidArgumentException` | `$region` is an empty string |
| `\InvalidArgumentException` | `$region` does not match any known ID or alias |
| `\InvalidArgumentException` | `$service` is not found in the region's endpoint map |
| `\RuntimeException` | `regions.json` is missing and cannot be downloaded |
| `\RuntimeException` | `regions.json` exists but contains invalid JSON |

---

## 4. Supported Regions

| Canonical ID | Cloud | Location | Default | Accepted Aliases |
|---|---|---|---|---|
| `na` | AWS | North America | ✓ | `us`, `aws-na`, `aws_na`, `NA`, `US`, `AWS-NA`, `AWS_NA` |
| `eu` | AWS | Europe | | `aws-eu`, `aws_eu`, `EU`, `AWS-EU`, `AWS_EU` |
| `au` | AWS | Australia | | `aws-au`, `aws_au`, `AU`, `AWS-AU`, `AWS_AU` |
| `azure-na` | Azure | North America | | `azure_na`, `AZURE-NA`, `AZURE_NA` |
| `azure-eu` | Azure | Europe | | `azure_eu`, `AZURE-EU`, `AZURE_EU` |
| `gcp-na` | GCP | North America | | `gcp_na`, `GCP-NA`, `GCP_NA` |
| `gcp-eu` | GCP | Europe | | `gcp_eu`, `GCP-EU`, `GCP_EU` |

**Alias matching rules:**
- Case-insensitive — `EU`, `eu`, `Eu` all resolve to the same region
- Both `-` and `_` separators are accepted — `azure-na` and `azure_na` are equivalent
- Leading/trailing whitespace is stripped automatically

---

## 5. Available Service Keys

| Key | Description |
|---|---|
| `contentDelivery` | Content Delivery API (CDN) — for fetching published entries and assets |
| `contentManagement` | Content Management API — for creating, updating, and deleting content |
| `graphqlDelivery` | GraphQL Delivery API |
| `graphqlPreview` | GraphQL Live Preview API |
| `preview` | REST Live Preview API |
| `auth` | Authentication API |
| `application` | Contentstack web application |
| `images` | Image Delivery — optimised image serving |
| `assets` | Asset Delivery — non-image file storage |
| `automate` | Workflow Automation API |
| `launch` | Contentstack Launch API |
| `developerHub` | Developer Hub API |
| `brandKit` | Brand Kit API |
| `genAI` | Generative AI / Knowledge Vault |
| `personalizeManagement` | Personalization Management API |
| `personalizeEdge` | Personalization Edge API |
| `composableStudio` | Composable Studio API |
| `assetManagement` | Asset Management API (NA only) |

> **Note:** Not all service keys are present in every region. If you request a service that does not exist for a given region, an `InvalidArgumentException` is thrown.

---

## 6. Complete URL Reference

### AWS North America (`na`)

| Service | URL |
|---|---|
| `application` | `https://app.contentstack.com` |
| `contentDelivery` | `https://cdn.contentstack.io` |
| `contentManagement` | `https://api.contentstack.io` |
| `auth` | `https://auth-api.contentstack.com` |
| `graphqlDelivery` | `https://graphql.contentstack.com` |
| `preview` | `https://rest-preview.contentstack.com` |
| `graphqlPreview` | `https://graphql-preview.contentstack.com` |
| `images` | `https://images.contentstack.io` |
| `assets` | `https://assets.contentstack.io` |
| `automate` | `https://automations-api.contentstack.com` |
| `launch` | `https://launch-api.contentstack.com` |
| `developerHub` | `https://developerhub-api.contentstack.com` |
| `brandKit` | `https://brand-kits-api.contentstack.com` |
| `genAI` | `https://ai.contentstack.com/brand-kits` |
| `personalizeManagement` | `https://personalize-api.contentstack.com` |
| `personalizeEdge` | `https://personalize-edge.contentstack.com` |
| `composableStudio` | `https://composable-studio-api.contentstack.com` |
| `assetManagement` | `https://am-api.contentstack.com` |

### AWS Europe (`eu`)

| Service | URL |
|---|---|
| `application` | `https://eu-app.contentstack.com` |
| `contentDelivery` | `https://eu-cdn.contentstack.com` |
| `contentManagement` | `https://eu-api.contentstack.com` |
| `auth` | `https://eu-auth-api.contentstack.com` |
| `graphqlDelivery` | `https://eu-graphql.contentstack.com` |
| `preview` | `https://eu-rest-preview.contentstack.com` |
| `graphqlPreview` | `https://eu-graphql-preview.contentstack.com` |
| `images` | `https://eu-images.contentstack.com` |
| `assets` | `https://eu-assets.contentstack.com` |
| `automate` | `https://eu-prod-automations-api.contentstack.com` |
| `launch` | `https://eu-launch-api.contentstack.com` |
| `developerHub` | `https://eu-developerhub-api.contentstack.com` |
| `brandKit` | `https://eu-brand-kits-api.contentstack.com` |
| `genAI` | `https://eu-ai.contentstack.com/brand-kits` |
| `personalizeManagement` | `https://eu-personalize-api.contentstack.com` |
| `personalizeEdge` | `https://eu-personalize-edge.contentstack.com` |
| `composableStudio` | `https://eu-composable-studio-api.contentstack.com` |

### AWS Australia (`au`)

| Service | URL |
|---|---|
| `contentDelivery` | `https://au-cdn.contentstack.com` |
| `contentManagement` | `https://au-api.contentstack.com` |
| `auth` | `https://au-auth-api.contentstack.com` |
| `graphqlDelivery` | `https://au-graphql.contentstack.com` |
| `preview` | `https://au-rest-preview.contentstack.com` |
| `graphqlPreview` | `https://au-graphql-preview.contentstack.com` |
| `images` | `https://au-images.contentstack.com` |
| `assets` | `https://au-assets.contentstack.com` |

### Azure North America (`azure-na`)

| Service | URL |
|---|---|
| `contentDelivery` | `https://azure-na-cdn.contentstack.com` |
| `contentManagement` | `https://azure-na-api.contentstack.com` |
| `auth` | `https://azure-na-auth-api.contentstack.com` |
| `graphqlDelivery` | `https://azure-na-graphql.contentstack.com` |
| `preview` | `https://azure-na-rest-preview.contentstack.com` |
| `graphqlPreview` | `https://azure-na-graphql-preview.contentstack.com` |
| `images` | `https://azure-na-images.contentstack.com` |
| `assets` | `https://azure-na-assets.contentstack.com` |

### Azure Europe (`azure-eu`)

| Service | URL |
|---|---|
| `contentDelivery` | `https://azure-eu-cdn.contentstack.com` |
| `contentManagement` | `https://azure-eu-api.contentstack.com` |
| `auth` | `https://azure-eu-auth-api.contentstack.com` |
| `graphqlDelivery` | `https://azure-eu-graphql.contentstack.com` |

### GCP North America (`gcp-na`)

| Service | URL |
|---|---|
| `contentDelivery` | `https://gcp-na-cdn.contentstack.com` |
| `contentManagement` | `https://gcp-na-api.contentstack.com` |
| `auth` | `https://gcp-na-auth-api.contentstack.com` |
| `graphqlDelivery` | `https://gcp-na-graphql.contentstack.com` |

### GCP Europe (`gcp-eu`)

| Service | URL |
|---|---|
| `contentDelivery` | `https://gcp-eu-cdn.contentstack.com` |
| `contentManagement` | `https://gcp-eu-api.contentstack.com` |
| `auth` | `https://gcp-eu-auth-api.contentstack.com` |
| `graphqlDelivery` | `https://gcp-eu-graphql.contentstack.com` |

---

## 7. Usage Examples

### Basic — get a single URL

```php
use Contentstack\Utils\Endpoint;

// Full URL with https://
$url = Endpoint::getContentstackEndpoint('na', 'contentDelivery');
// → "https://cdn.contentstack.io"

$url = Endpoint::getContentstackEndpoint('eu', 'contentManagement');
// → "https://eu-api.contentstack.com"

$url = Endpoint::getContentstackEndpoint('au', 'graphqlDelivery');
// → "https://au-graphql.contentstack.com"

$url = Endpoint::getContentstackEndpoint('azure-na', 'auth');
// → "https://azure-na-auth-api.contentstack.com"

$url = Endpoint::getContentstackEndpoint('gcp-eu', 'preview');
// → "https://gcp-eu-rest-preview.contentstack.com"
```

### Using region aliases

All aliases resolve to the same canonical region — use whichever form suits your config:

```php
// All four return "https://cdn.contentstack.io"
Endpoint::getContentstackEndpoint('na',     'contentDelivery');
Endpoint::getContentstackEndpoint('us',     'contentDelivery');
Endpoint::getContentstackEndpoint('aws-na', 'contentDelivery');
Endpoint::getContentstackEndpoint('AWS_NA', 'contentDelivery');

// All three return "https://eu-cdn.contentstack.com"
Endpoint::getContentstackEndpoint('eu',     'contentDelivery');
Endpoint::getContentstackEndpoint('EU',     'contentDelivery');
Endpoint::getContentstackEndpoint('aws_eu', 'contentDelivery');

// Both return "https://azure-na-cdn.contentstack.com"
Endpoint::getContentstackEndpoint('azure-na', 'contentDelivery');
Endpoint::getContentstackEndpoint('AZURE_NA', 'contentDelivery');
```

### Strip `https://` for use as a hostname

Pass `true` as the third argument when you need just the host:

```php
$host = Endpoint::getContentstackEndpoint('eu', 'contentDelivery', true);
// → "eu-cdn.contentstack.com"

$host = Endpoint::getContentstackEndpoint('gcp-na', 'contentManagement', true);
// → "gcp-na-api.contentstack.com"

$host = Endpoint::getContentstackEndpoint('azure-eu', 'auth', true);
// → "azure-eu-auth-api.contentstack.com"
```

### Get all endpoints for a region

Omit `$service` to receive the complete associative array:

```php
$endpoints = Endpoint::getContentstackEndpoint('eu');
// → [
//     'application'          => 'https://eu-app.contentstack.com',
//     'contentDelivery'      => 'https://eu-cdn.contentstack.com',
//     'contentManagement'    => 'https://eu-api.contentstack.com',
//     'auth'                 => 'https://eu-auth-api.contentstack.com',
//     'graphqlDelivery'      => 'https://eu-graphql.contentstack.com',
//     'preview'              => 'https://eu-rest-preview.contentstack.com',
//     'graphqlPreview'       => 'https://eu-graphql-preview.contentstack.com',
//     'images'               => 'https://eu-images.contentstack.com',
//     'assets'               => 'https://eu-assets.contentstack.com',
//     'automate'             => 'https://eu-prod-automations-api.contentstack.com',
//     'launch'               => 'https://eu-launch-api.contentstack.com',
//     'developerHub'         => 'https://eu-developerhub-api.contentstack.com',
//     'brandKit'             => 'https://eu-brand-kits-api.contentstack.com',
//     'genAI'                => 'https://eu-ai.contentstack.com/brand-kits',
//     'personalizeManagement'=> 'https://eu-personalize-api.contentstack.com',
//     'personalizeEdge'      => 'https://eu-personalize-edge.contentstack.com',
//     'composableStudio'     => 'https://eu-composable-studio-api.contentstack.com',
//   ]

// With omitHttps — all schemes stripped
$hosts = Endpoint::getContentstackEndpoint('eu', '', true);
// → [
//     'contentDelivery'   => 'eu-cdn.contentstack.com',
//     'contentManagement' => 'eu-api.contentstack.com',
//     ...
//   ]

echo $endpoints['contentDelivery'];  // https://eu-cdn.contentstack.com
echo $hosts['contentManagement'];    // eu-api.contentstack.com
```

### Reading region from an environment variable

```php
use Contentstack\Utils\Endpoint;

// Set in your .env or server config: CONTENTSTACK_REGION=eu
$region = getenv('CONTENTSTACK_REGION') ?: 'na';

$cdnUrl = Endpoint::getContentstackEndpoint($region, 'contentDelivery');
$apiUrl = Endpoint::getContentstackEndpoint($region, 'contentManagement');

echo $cdnUrl; // https://eu-cdn.contentstack.com
echo $apiUrl; // https://eu-api.contentstack.com
```

### Accessing specific services from the full map

```php
$endpoints = Endpoint::getContentstackEndpoint('azure-na');

$cdnUrl     = $endpoints['contentDelivery'];
$apiUrl     = $endpoints['contentManagement'];
$graphqlUrl = $endpoints['graphqlDelivery'];
$previewUrl = $endpoints['preview'];
$authUrl    = $endpoints['auth'];

// Use in your app config
$config = [
    'cdn'     => $cdnUrl,
    'api'     => $apiUrl,
    'graphql' => $graphqlUrl,
];
```

### Via `Utils` — backward-compatible shorthand

```php
use Contentstack\Utils\Utils;

// Identical to Endpoint::getContentstackEndpoint()
$url = Utils::getContentstackEndpoint('eu', 'contentDelivery');
// → "https://eu-cdn.contentstack.com"

$host = Utils::getContentstackEndpoint('gcp-na', 'contentManagement', true);
// → "gcp-na-api.contentstack.com"

$all = Utils::getContentstackEndpoint('au');
// → associative array of all AU endpoints
```

---

## 8. Integration with PHP Delivery SDK

The `contentDelivery` host resolved from `getContentstackEndpoint()` maps directly to the host the PHP delivery SDK uses for entry and asset fetching.

### Basic setup

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;

$region = 'eu'; // switch this one value to target any region

// Resolve the content delivery host for the chosen region
$host = Endpoint::getContentstackEndpoint($region, 'contentDelivery', true);
// → "eu-cdn.contentstack.com"

// Initialise the delivery SDK
$stack = Contentstack::Stack(
    '<API_KEY>',
    '<DELIVERY_TOKEN>',
    '<ENVIRONMENT>'
);

// Wire the resolved host into the stack
$stack->setHost($host);

// All subsequent requests go to the EU CDN
$result = $stack
    ->ContentType('<CONTENT_TYPE_UID>')
    ->Query()
    ->toJSON()
    ->find();

foreach ($result[0] as $entry) {
    echo $entry['title'] . "\n";
}
```

### Reading region from environment variable (recommended)

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;

$region = getenv('CONTENTSTACK_REGION') ?: 'na';

$stack = Contentstack::Stack(
    getenv('CONTENTSTACK_API_KEY'),
    getenv('CONTENTSTACK_DELIVERY_TOKEN'),
    getenv('CONTENTSTACK_ENVIRONMENT')
);
$stack->setHost(Endpoint::getContentstackEndpoint($region, 'contentDelivery', true));
```

`.env` file:
```dotenv
CONTENTSTACK_REGION=eu
CONTENTSTACK_API_KEY=blt...
CONTENTSTACK_DELIVERY_TOKEN=cs...
CONTENTSTACK_ENVIRONMENT=production
```

### Fetching a single entry

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;

$region = 'azure-na';
$host   = Endpoint::getContentstackEndpoint($region, 'contentDelivery', true);

$stack = Contentstack::Stack('<API_KEY>', '<DELIVERY_TOKEN>', '<ENVIRONMENT>');
$stack->setHost($host);

$entry = $stack
    ->ContentType('<CONTENT_TYPE_UID>')
    ->Entry('<ENTRY_UID>')
    ->toJSON()
    ->fetch();

echo $entry['title'];
```

### Querying entries with filters

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;

$host  = Endpoint::getContentstackEndpoint('gcp-eu', 'contentDelivery', true);
$stack = Contentstack::Stack('<API_KEY>', '<DELIVERY_TOKEN>', '<ENVIRONMENT>');
$stack->setHost($host);

$result = $stack
    ->ContentType('blog_post')
    ->Query()
    ->where('category', 'technology')
    ->limit(10)
    ->toJSON()
    ->find();

$entries = $result[0];
echo "Found: " . count($entries) . " entries\n";
```

### Fetching assets

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;

$host  = Endpoint::getContentstackEndpoint('eu', 'contentDelivery', true);
$stack = Contentstack::Stack('<API_KEY>', '<DELIVERY_TOKEN>', '<ENVIRONMENT>');
$stack->setHost($host);

$asset = $stack->Assets('<ASSET_UID>')->toJSON()->fetch();
echo $asset['url'];
```

### Querying with embedded items (JSON RTE)

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;
use Contentstack\Utils\Model\Option;

$host  = Endpoint::getContentstackEndpoint('na', 'contentDelivery', true);
$stack = Contentstack::Stack('<API_KEY>', '<DELIVERY_TOKEN>', '<ENVIRONMENT>');
$stack->setHost($host);

$result = $stack
    ->ContentType('<CONTENT_TYPE_UID>')
    ->Query()
    ->toJSON()
    ->includeEmbeddedItems()
    ->find();

foreach ($result[0] as $entry) {
    $html = Contentstack::jsonToHtml($entry['json_rte_field'], new Option($entry));
    echo $html;
}
```

### GraphQL with endpoint resolution

```php
use Contentstack\Utils\Endpoint;

$graphqlUrl = Endpoint::getContentstackEndpoint('eu', 'graphqlDelivery');
// → "https://eu-graphql.contentstack.com"

// Use this URL as the base for your GraphQL client
$client = new GraphQLClient($graphqlUrl, [
    'headers' => [
        'access_token' => '<DELIVERY_TOKEN>',
        'api_key'      => '<API_KEY>',
    ]
]);
```

### Switching regions dynamically

Change one variable to redirect all API traffic to a different region:

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;

function createStack(string $region): \Contentstack\Stack\Stack
{
    $host  = Endpoint::getContentstackEndpoint($region, 'contentDelivery', true);
    $stack = Contentstack::Stack(
        getenv('CONTENTSTACK_API_KEY'),
        getenv('CONTENTSTACK_DELIVERY_TOKEN'),
        getenv('CONTENTSTACK_ENVIRONMENT')
    );
    $stack->setHost($host);
    return $stack;
}

// Route traffic by region
$regions = ['na', 'eu', 'au', 'azure-na', 'azure-eu', 'gcp-na', 'gcp-eu'];

foreach ($regions as $region) {
    $stack   = createStack($region);
    $result  = $stack->ContentType('page')->Query()->toJSON()->find();
    $count   = count($result[0]);
    $host    = Endpoint::getContentstackEndpoint($region, 'contentDelivery');
    echo sprintf("%-10s %-45s %d entries\n", $region, $host, $count);
}
```

---

## 9. Error Handling

All exceptions thrown are either `\InvalidArgumentException` (bad input) or `\RuntimeException` (infrastructure/file problem).

### Empty region

```php
use Contentstack\Utils\Endpoint;

try {
    Endpoint::getContentstackEndpoint('');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "Empty region provided. Please put valid region."
}
```

### Unknown region

```php
try {
    Endpoint::getContentstackEndpoint('asia-pacific', 'contentDelivery');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // "Invalid region: asia-pacific"
}
```

### Unknown service key

```php
try {
    Endpoint::getContentstackEndpoint('na', 'cms');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // 'Service "cms" not found for region "na"'
}
```

### regions.json missing and no network

```php
try {
    Endpoint::getContentstackEndpoint('na', 'contentDelivery');
} catch (\RuntimeException $e) {
    echo $e->getMessage();
    // "contentstack/utils: regions.json not found and could not be downloaded.
    //  Run "composer install" or "composer refresh-regions" and ensure network access."
}
```

### Corrupt regions.json

```php
try {
    Endpoint::getContentstackEndpoint('na', 'contentDelivery');
} catch (\RuntimeException $e) {
    echo $e->getMessage();
    // "contentstack/utils: regions.json is corrupt.
    //  Run "composer refresh-regions" to re-download it."
}
```

### Defensive pattern for production code

```php
use Contentstack\Utils\Endpoint;

function resolveHost(string $region, string $service): string
{
    try {
        return Endpoint::getContentstackEndpoint($region, $service, true);
    } catch (\InvalidArgumentException $e) {
        // Bad config — log and fall back to default NA host
        error_log('Endpoint config error: ' . $e->getMessage());
        return 'cdn.contentstack.io';
    } catch (\RuntimeException $e) {
        // Infrastructure problem — log and fall back
        error_log('Endpoint load error: ' . $e->getMessage());
        return 'cdn.contentstack.io';
    }
}
```

---

## 10. Keeping regions.json Up to Date

Contentstack occasionally adds new regions or new service keys. The bundled `regions.json` needs to be refreshed when this happens.

### Refresh manually

```bash
composer refresh-regions
```

### Automate in CI/CD

Add a refresh step before your deploy so the latest regions are always used:

```yaml
# GitHub Actions example
- name: Install PHP dependencies
  run: composer install --no-dev --optimize-autoloader
# regions.json is downloaded automatically by post-install-cmd

# Or refresh explicitly if the file was cached between CI runs
- name: Refresh Contentstack regions
  run: composer refresh-regions
```

### How the download script works

`scripts/download-regions.php` is the script wired to the composer hooks:

1. Tries **PHP curl extension** first — follows redirects, verifies SSL
2. Falls back to **`file_get_contents`** with a stream context
3. Validates the downloaded JSON has a `regions` array before writing
4. Writes to `src/assets/regions.json`
5. Prints the region count on success; warns (non-fatal) on failure

The exit code is always `0` — a download failure is a warning, not a fatal error, because the runtime fallback in `Endpoint::loadRegions()` will attempt the download again on the first API call.

---

## 11. Architecture

### File structure

```
contentstack-utils-php/
├── src/
│   ├── Endpoint.php              ← core implementation
│   ├── Utils.php                 ← proxy method for backward compat
│   └── assets/
│       └── regions.json          ← downloaded at install/runtime, NOT committed
├── scripts/
│   └── download-regions.php      ← called by composer post-install-cmd
├── tests/
│   └── EndpointTest.php          ← 39 tests, 99 assertions
└── composer.json                 ← post-install-cmd, post-update-cmd, refresh-regions
```

### `Endpoint.php` internal flow

```
getContentstackEndpoint($region, $service, $omitHttps)
│
├── Guard: $region === '' → throw InvalidArgumentException
│
├── loadRegions()
│     ├── $regionsData cached? → return cache (zero I/O)
│     ├── file_exists(regions.json)? → read + decode + cache
│     └── else → downloadAndSave() → read + decode + cache
│
├── strtolower(trim($region)) → $normalized
│
├── findRegionByIdOrAlias($regions, $normalized)
│     ├── Pass 1: match $row['id'] === $normalized
│     └── Pass 2: match strtolower($alias) === $normalized for each alias
│     └── null → throw InvalidArgumentException('Invalid region')
│
├── $service provided?
│     ├── YES → $regionRow['endpoints'][$service]
│     │         └── missing? → throw InvalidArgumentException('Service not found')
│     │         └── omitHttps? → stripHttps($url) : $url
│     └── NO  → $regionRow['endpoints']
│               └── omitHttps? → stripHttpsFromMap($endpoints) : $endpoints
│
└── return string|array
```

### Static cache lifetime

`Endpoint::$regionsData` is a `static` class property. In PHP:
- It is initialised to `null`
- Set on the first `loadRegions()` call
- Persists for the entire PHP process lifetime (e.g. the full HTTP request in FPM, or the full CLI run)
- Reset explicitly via `Endpoint::resetCache()` (test use only)

This means `regions.json` is read from disk **once per process**, regardless of how many times `getContentstackEndpoint()` is called.

### Relationship between `Endpoint` and `Utils`

```
Utils::getContentstackEndpoint()   ← thin proxy, no logic
         │
         └── Endpoint::getContentstackEndpoint()   ← all logic lives here
```

`Utils` delegates entirely to `Endpoint`. Both classes are in the `Contentstack\Utils` namespace so no `use` import is needed between them.

### Composer hooks summary

| Hook | When it fires | What it does |
|---|---|---|
| `post-install-cmd` | After `composer install` on the **root** package | Runs `scripts/download-regions.php` |
| `post-update-cmd` | After `composer update` on the **root** package | Runs `scripts/download-regions.php` |
| `refresh-regions` | `composer refresh-regions` (manual) | Runs `scripts/download-regions.php` |
| Runtime fallback | First `getContentstackEndpoint()` call when file is missing | `Endpoint::downloadAndSave()` downloads the file silently |

> **Important:** `post-install-cmd` and `post-update-cmd` only fire when this package **is the root** (i.e. being developed directly). When another project runs `composer require contentstack/utils`, those hooks are skipped — the runtime fallback handles the download transparently.
