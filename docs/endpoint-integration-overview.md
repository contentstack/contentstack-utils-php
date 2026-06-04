# Endpoint Feature — Integration Overview

## The Problem Being Solved

Before this feature, Contentstack hosts were either **hardcoded** in the delivery SDK (`cdn.contentstack.io`) or manually constructed with string concatenation (`$region.'-cdn.contentstack.com'`). There was no single authoritative source for all regions and all services. The endpoint feature solves this by providing one function to resolve any URL for any region/service.

---

## Part 1 — The Data Source (`regions.json`)

Contentstack maintains a live registry at:
```
https://artifacts.contentstack.com/regions.json
```

Structure:
```
{
  "regions": [
    {
      "id": "na",                          ← canonical region ID
      "alias": ["us","aws-na","AWS-NA"...] ← all accepted aliases
      "isDefault": true,
      "endpoints": {
        "contentDelivery":    "https://cdn.contentstack.io",
        "contentManagement":  "https://api.contentstack.io",
        "graphqlDelivery":    "https://graphql.contentstack.com",
        "auth":               "https://auth-api.contentstack.com",
        "preview":            "https://rest-preview.contentstack.com",
        ... 18 services total
      }
    },
    { "id": "eu",       ... },
    { "id": "au",       ... },
    { "id": "azure-na", ... },
    { "id": "azure-eu", ... },
    { "id": "gcp-na",   ... },
    { "id": "gcp-eu",   ... }   ← 7 regions total
  ]
}
```

This file is **not committed** to the repository. It is downloaded automatically at install time and lives at `src/assets/regions.json`. No runtime HTTP calls in normal operation — works fully offline once downloaded.

---

## Part 2 — Keeping Regions Up To Date (`refresh-regions`)

Contentstack occasionally adds new regions or services. The workflow to update is:

```bash
# Pull the latest registry from Contentstack and overwrite the local file
composer refresh-regions

# What this runs internally:
# php scripts/download-regions.php
# → curl https://artifacts.contentstack.com/regions.json
# → writes to src/assets/regions.json

# Since regions.json is in .gitignore, no commit is needed —
# every developer and CI environment gets it fresh on composer install
```

This mirrors exactly how the JS SDK handles it — except JS fetches at build/publish time via an npm `prebuild` script. PHP downloads it via a `post-install-cmd` composer hook, with a runtime fallback on the first API call.

---

## Part 3 — How `regions.json` Gets to Disk

Unlike the JS SDK (which bundles the file at publish time), the PHP SDK downloads it in three layers:

```
Layer 1 — composer install / composer update  (root package only)
  │
  └── post-install-cmd fires
        → @php scripts/download-regions.php
        → curl https://artifacts.contentstack.com/regions.json
        → writes src/assets/regions.json
        → "contentstack/utils: regions.json downloaded (7 regions)."

Layer 2 — Runtime fallback  (when package is used as a dependency)
  │
  └── First call to Endpoint::getContentstackEndpoint()
        → file_exists('src/assets/regions.json') === false
        → Endpoint::downloadAndSave() runs silently
        → writes src/assets/regions.json
        → continues normally

Layer 3 — Static cache  (fastest, zero I/O after first call)
  │
  └── $regionsData already set in memory
        → return cached data immediately
        → no disk reads, no network calls
```

`src/assets/regions.json` is listed in `.gitignore` — it is never committed. Every environment provisions it independently.

---

## Part 4 — `Endpoint::getContentstackEndpoint()` — How Resolution Works

```
Endpoint::getContentstackEndpoint('na', 'contentDelivery', false)
                    │                │              │
                    │                │              └── omitHttps: keep https://
                    │                └── service: which URL to return
                    └── region: ID or any alias
```

Step-by-step inside [src/Endpoint.php](../src/Endpoint.php):

```
Call arrives → getContentstackEndpoint('na', 'contentDelivery', false)

1. Guard check
   region === '' → throw InvalidArgumentException immediately

2. loadRegions()  [runs only once per PHP process]
   First call:  file_get_contents('src/assets/regions.json')
                json_decode() → store in static $regionsData
   Subsequent:  return cached $regionsData  ← no disk reads

3. Normalize input
   strtolower(trim('na')) → 'na'

4. findRegionByIdOrAlias()
   Pass 1 — match by id:
     regions[0]['id'] === 'na' ✓  → return this region row

   Pass 2 — match by alias (only if Pass 1 fails):
     e.g. 'AWS-NA' → strtolower → 'aws-na'
     scan alias[] of each region until match found

   No match → throw InvalidArgumentException('Invalid region: ...')

5. Service lookup
   service === 'contentDelivery'
   → $regionRow['endpoints']['contentDelivery']
   → "https://cdn.contentstack.io"
   Key missing → throw InvalidArgumentException('Service not found')

6. omitHttps check
   false → return "https://cdn.contentstack.io"  ← full URL
   true  → preg_replace('/^https?:\/\//', '') → "cdn.contentstack.io"

7. No service provided (service === '')
   → return entire $regionRow['endpoints'] array
   → with omitHttps: strip scheme from every value
```

---

## Part 5 — `Utils::getContentstackEndpoint()` — Backward Compatibility

[src/Utils.php](../src/Utils.php) exposes the same function as a static proxy so existing code using `Utils::` doesn't need to change import paths:

```php
// In Utils.php — just a thin pass-through, zero logic here
public static function getContentstackEndpoint(
    string $region = 'us',
    string $service = '',
    bool $omitHttps = false
) {
    return Endpoint::getContentstackEndpoint($region, $service, $omitHttps);
}

// Both calls produce identical results:
Endpoint::getContentstackEndpoint('na', 'contentDelivery');
Utils::getContentstackEndpoint('na', 'contentDelivery');
```

---

## Part 6 — Integration with the PHP Delivery SDK (what `test.php` does)

```
test.php execution trace:
─────────────────────────────────────────────────────────────────

1. Load autoloaders
   require vendor/autoload.php                        ← utils-php (has new Endpoint class)
   require contentstack-php/vendor/autoload.php       ← delivery SDK
   (utils-php loads first → its Contentstack\Utils\* classes win)

2. Resolve endpoint
   Endpoint::getContentstackEndpoint('na', 'contentDelivery')
   → "https://cdn.contentstack.io"    [for display]

   Endpoint::getContentstackEndpoint('na', 'contentDelivery', true)
   → "cdn.contentstack.io"            [host without scheme, for setHost()]

3. Create delivery SDK Stack
   Contentstack::Stack(API_KEY, DELIVERY_TOKEN, 'production')
   → Stack object, host defaults to 'cdn.contentstack.io' (NA default)

4. Override host with endpoint-resolved value
   $stack->setHost('cdn.contentstack.io')
   → host is now authoritative from regions.json, not hardcoded

5. Fetch entries
   $stack->ContentType('mega_menu')->Query()->toJSON()->find()
   → HTTP GET https://cdn.contentstack.io/v3/content_types/mega_menu/entries
              ?environment=production
   → Returns 2 entries: "Region", "Topics Navigation"

Output:
   Total entries fetched: 2
   Entry #1 → bltc85890659eefc7c2  "Region"
   Entry #2 → blt3d9080b4eba8defa  "Topics Navigation"
```

The full `test.php` source is at [test.php](../test.php).

---

## Part 7 — Switching Regions in Practice

The key benefit: changing **one string** switches every URL automatically.

```php
// NA (default)
$host = Endpoint::getContentstackEndpoint('na', 'contentDelivery', true);
// → cdn.contentstack.io

// EU
$host = Endpoint::getContentstackEndpoint('eu', 'contentDelivery', true);
// → eu-cdn.contentstack.com

// Azure EU
$host = Endpoint::getContentstackEndpoint('azure-eu', 'contentDelivery', true);
// → azure-eu-cdn.contentstack.com

// GCP NA
$host = Endpoint::getContentstackEndpoint('gcp-na', 'contentDelivery', true);
// → gcp-na-cdn.contentstack.com

// Then the same Stack setup works for any region:
$stack = Contentstack::Stack($API_KEY, $DELIVERY_TOKEN, $ENV);
$stack->setHost($host);
```

Reading the region from an environment variable is the recommended pattern:

```php
$region = getenv('CONTENTSTACK_REGION') ?: 'na';
$host   = Endpoint::getContentstackEndpoint($region, 'contentDelivery', true);

$stack  = Contentstack::Stack(
    getenv('CONTENTSTACK_API_KEY'),
    getenv('CONTENTSTACK_DELIVERY_TOKEN'),
    getenv('CONTENTSTACK_ENVIRONMENT')
);
$stack->setHost($host);
```

---

## Part 8 — Accepted Region Aliases

| You pass | Resolves to region |
|---|---|
| `na`, `us`, `aws-na`, `aws_na`, `NA`, `US`, `AWS-NA`, `AWS_NA` | `na` |
| `eu`, `aws-eu`, `aws_eu`, `EU`, `AWS-EU`, `AWS_EU` | `eu` |
| `au`, `aws-au`, `aws_au`, `AU`, `AWS-AU`, `AWS_AU` | `au` |
| `azure-na`, `azure_na`, `AZURE-NA`, `AZURE_NA` | `azure-na` |
| `azure-eu`, `azure_eu`, `AZURE-EU`, `AZURE_EU` | `azure-eu` |
| `gcp-na`, `gcp_na`, `GCP-NA`, `GCP_NA` | `gcp-na` |
| `gcp-eu`, `gcp_eu`, `GCP-EU`, `GCP_EU` | `gcp-eu` |

All matching is **case-insensitive** and accepts both `-` and `_` separators.

---

## Part 9 — Available Service Keys

| Service key | What it points to |
|---|---|
| `contentDelivery` | CDN for published content (used for entry/asset fetching) |
| `contentManagement` | CMA for creating/updating content |
| `graphqlDelivery` | GraphQL delivery API |
| `graphqlPreview` | GraphQL live preview |
| `preview` | REST live preview |
| `auth` | Authentication API |
| `application` | Web app URL |
| `images` | Image delivery |
| `assets` | Asset delivery |
| `automate` | Workflow automation |
| `launch` | Contentstack Launch |
| `developerHub` | Developer Hub API |
| `brandKit` | Brand Kit API |
| `genAI` | Generative AI / Knowledge Vault |
| `personalizeManagement` | Personalization management |
| `personalizeEdge` | Personalization edge |
| `composableStudio` | Composable Studio API |
| `assetManagement` | Asset management API (NA only) |

---

## Part 10 — Error Handling

```php
use Contentstack\Utils\Endpoint;

// Empty region string
try {
    Endpoint::getContentstackEndpoint('');
} catch (\InvalidArgumentException $e) {
    // "Empty region provided. Please put valid region."
}

// Unknown region
try {
    Endpoint::getContentstackEndpoint('asia-pacific', 'contentDelivery');
} catch (\InvalidArgumentException $e) {
    // "Invalid region: asia-pacific"
}

// Unknown service key
try {
    Endpoint::getContentstackEndpoint('na', 'cms');
} catch (\InvalidArgumentException $e) {
    // "Service "cms" not found for region "na""
}

// regions.json missing and no network access
try {
    Endpoint::getContentstackEndpoint('na', 'contentDelivery');
} catch (\RuntimeException $e) {
    // "contentstack/utils: regions.json not found and could not be downloaded.
    //  Run "composer install" or "composer refresh-regions" and ensure network access."
}
```

---

## Part 11 — Files Introduced by This Feature

```
contentstack-utils-php/
│
├── src/
│   ├── Endpoint.php              ← core implementation (new)
│   ├── Utils.php                 ← getContentstackEndpoint() proxy added
│   └── assets/
│       └── regions.json          ← downloaded at install/runtime, NOT committed
│
├── scripts/
│   └── download-regions.php      ← called by composer hooks (new)
│
├── tests/
│   └── EndpointTest.php          ← 39 tests, 99 assertions (new)
│
├── docs/
│   ├── endpoint-integration-overview.md   ← this file (new)
│   └── endpoint-resolution.md            ← full API reference (new)
│
├── composer.json                 ← post-install-cmd, post-update-cmd, refresh-regions added
└── .gitignore                    ← src/assets/regions.json added
```
