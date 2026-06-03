# Contentstack PHP Utils SDK:

Contentstack is a headless CMS with an API-first approach. It is a CMS that developers can use to build powerful cross-platform applications in their favorite languages. Build your application frontend, and Contentstack will take care of the rest. Read More.

This guide will help you get started with Contentstack PHP Utils SDK to build apps powered by Contentstack.

## Prerequisites

-   PHP version 5.5.0 or later
    
## SDK Installation and Setup

To set up the Utils SDK in your PHP project, install it via gem:
```sh
composer require contentstack/utils
```
  
If you are using Contentstack PHP SDK, then “contentstack/utils” is already imported into your project.

## Usage

Let’s learn how you can use Utils SDK to render embedded items.

### Create Render Option:

To render embedded items on the front-end, use the renderOptions function, and define the UI elements you want to show in the front-end of your website, as shown in the example code below:
```php
<?php  

declare(strict_types=1);  

namespace Sample\App;  
  
use Contentstack\Utils\Resource\EntryEmbedable;  
use Contentstack\Utils\Resource\RenderableInterface;  
use Contentstack\Utils\Resource\EmbeddedObject;  
use Contentstack\Utils\Model\Option;  
use Contentstack\Utils\Model\Metadata;  
use Contentstack\Utils\Enum\StyleType;
use Contentstack\Utils\Enum\NodeType;
use Contentstack\Utils\Enum\MarkType;

class  CustomOption  extends  Option {  
	function renderMark(MarkType $markType, string $text): string 
    {
        switch ($markType)
        {
            case MarkType::get(MarkType::BOLD):
                return "<b>".$text."</b>";
            default:
                return parent::renderMark($markType, $text);
        }
    }
    function renderNode(string $nodeType, object $node, string $innerHtml): string 
    {
        switch ($nodeType)
        {
            case "p":
                return "<p class='class-id'>".$innerHtml."</p>";
            case "h1":
                return "<h1 class='class-id'>".$innerHtml."</h1>";
            default:
                return parent::renderNode($nodeType, $node, $innerHtml);
        }
    }
	function renderOptions(array $embeddedObject, Metadata $metadata): string  
	{  
		switch ($metadata->getStyleType()) {  
			case StyleType::get(StyleType::BLOCK):  
				if ($metadata->contentTypeUID === 'product') {  
					return  "<div>  
							<h2 >".$embeddedObject["title"]."</h2>  
							<img src=".$embeddedObject["product_image"]["url"]. "alt=".$embeddedObject["product_image"]["title"]."/>  
							<p>".$embeddedObject["price"]."</p>  
							</div>"  
				}  
			case StyleType::get(StyleType::INLINE):  
				return  "<span><b>".$embeddedObject["title"]."</b> -".$embeddedObject["description"]."</span>";  
			case StyleType::get(StyleType::LINK):  
				return  "<a href=".$metadata->getAttribute("href")->value  
.">".$metadata->getText()."</a>"  
			case StyleType::get(StyleType::DISPLAY):  
				return  "<img src=".$metadata->getAttribute("src")->value." alt='".$metadata->getAttribute("alt")->value." />";  
			case StyleType::get(StyleType::DOWNLOAD):  
				return  "<a href=".$metadata->getAttribute("href")->value  
.">".$metadata->getText()."</a>"  
		}  
        return parent::renderOptions($embeddedObject, $metadata);
	}  
}
```
## Basic Queries

Contentstack Utils SDK lets you interact with the Content Delivery APIs and retrieve embedded items from the RTE field of an entry.

### Fetch Embedded Item(s) from a Single Entry:
#### Render HTML RTE Embedded object

To get an embedded item of a single entry, you need to provide the stack API key, environment name, delivery token, content type’s UID, and entry’s UID. Then, use the `Contentstack::renderContent` function as shown below:
  
```php
use Contentstack\Contentstack;  
use Contentstack\Utils\Model\Option;  
  
$stack = Contentstack::Stack('<API_KEY>', '<ENVIRONMENT_SPECIFIC_DELIVERY_TOKEN>', '<ENVIRONMENT>');  
$entry = $stack->ContentType('<CONTENT_TYPE_UID>')->Entry('<ENTRY_UID>')->includeEmbeddedItems()->toJSON()->fetch();  
$render_rich_text = Contentstack::renderContent($entry['rte_field_uid'], new Option($entry));
```

If you want to render embedded items using the CustomOption function, you can refer to the code below:
```php
$rendered_rich_text = Contentstack.renderContent($entry['rte_field_uid'], new CustomOption($entry));
```
#### Render Supercharged RTE contents
To get a single entry, you need to provide the stack API key, environment name, delivery token, content type and entry UID. Then, use `Contentstack::jsonToHtml` function as shown below:


```php
use Contentstack\Contentstack;  
use Contentstack\Utils\Model\Option;  
  
$stack = Contentstack::Stack('<API_KEY>', '<ENVIRONMENT_SPECIFIC_DELIVERY_TOKEN>', '<ENVIRONMENT>');  
$entry = $stack->ContentType('<CONTENT_TYPE_UID>')->Entry('<ENTRY_UID>')->includeEmbeddedItems()->toJSON()->fetch();  
$render_rich_text = Contentstack::jsonToHtml($entry['rte_field_uid'], new Option($entry));
```

If you want to render embedded items using the CustomOption function, you can refer to the code below:
```php
$rendered_rich_text = Contentstack.jsonToHtml($entry['rte_field_uid'], new CustomOption($entry));
```
### Fetch Embedded Item(s) from Multiple Entries
#### Render HTML RTE Embedded object

To get embedded items from multiple entries, you need to provide the stack API key, environment name, delivery token, and content type’s UID.
```php
use Contentstack\Contentstack;  
use Contentstack\Utils\Model\Option;  
  
$stack = Contentstack::Stack('<API_KEY>', '<ENVIRONMENT_SPECIFIC_DELIVERY_TOKEN>', '<ENVIRONMENT>');  
$result = $stack->ContentType('<CONTENT_TYPE_UID>')->Query()->toJSON()->includeEmbeddedItems()->find()  
for($i = 0; $i < count($result[0]); $i++) {  
	$entry = $result[0][$i];  
	$render_rich_text = Contentstack::renderContent($entry['rich_text_content'], new Option($entry));  
}
```

#### Render Supercharged RTE contents
To get a single entry, you need to provide the stack API key, environment name, delivery token, content type UID. Then, use `Contentstack::jsonToHtml` function as shown below:

```php
use Contentstack\Contentstack;  
use Contentstack\Utils\Model\Option;  
  
$stack = Contentstack::Stack('<API_KEY>', '<ENVIRONMENT_SPECIFIC_DELIVERY_TOKEN>', '<ENVIRONMENT>');  
$result = $stack->ContentType('<CONTENT_TYPE_UID>')->Query()->toJSON()->includeEmbeddedItems()->find()  
for($i = 0; $i < count($result[0]); $i++) {  
	$entry = $result[0][$i];  
	$render_rich_text = Contentstack::jsonToHtml($entry['rich_text_content'], new Option($entry));  
}
```
#### GQL 

To Convert JSON RTE content to HTML from GQL API. Use `GQL::jsonToHtml` function as shown below:

```php
use Contentstack\Utils\GQL;
use Contentstack\Utils\Model\Option;  
// GQL fetch API
... 
	$render_html_text = GQL::jsonToHtml($entry->rich_text_content,, new Option());
...
```

---

## Endpoint Resolution

The SDK ships with a built-in endpoint resolver that returns the correct Contentstack API URL for any region and any service — no hardcoded URLs needed.

### How `regions.json` is provisioned

`regions.json` is **not committed** to your project. It is downloaded automatically:

| When | How |
|---|---|
| `composer install` or `composer update` | `post-install-cmd` runs `scripts/download-regions.php` |
| First call to `getContentstackEndpoint()` when file is missing | Runtime fallback downloads and caches the file |
| Manual refresh | `composer refresh-regions` |

```sh
# Refresh when Contentstack adds new regions or services
composer refresh-regions
```

---

### `getContentstackEndpoint()`

Available on both `Endpoint` and `Utils` (identical behaviour):

```php
use Contentstack\Utils\Endpoint;
use Contentstack\Utils\Utils;

Endpoint::getContentstackEndpoint(string $region, string $service, bool $omitHttps): string|array
Utils::getContentstackEndpoint(string $region, string $service, bool $omitHttps): string|array
```

| Parameter | Type | Default | Description |
|---|---|---|---|
| `$region` | `string` | `'us'` | Region ID or any accepted alias (see table below) |
| `$service` | `string` | `''` | Service key. When empty, all endpoints for the region are returned as an array |
| `$omitHttps` | `bool` | `false` | When `true`, strips `https://` from the returned URL(s) |

---

### Supported Regions

| Region | Canonical ID | Accepted Aliases |
|---|---|---|
| AWS North America | `na` | `us`, `aws-na`, `aws_na`, `NA`, `US`, `AWS-NA`, `AWS_NA` |
| AWS Europe | `eu` | `aws-eu`, `aws_eu`, `EU`, `AWS-EU`, `AWS_EU` |
| AWS Australia | `au` | `aws-au`, `aws_au`, `AU`, `AWS-AU`, `AWS_AU` |
| Azure North America | `azure-na` | `azure_na`, `AZURE-NA`, `AZURE_NA` |
| Azure Europe | `azure-eu` | `azure_eu`, `AZURE-EU`, `AZURE_EU` |
| GCP North America | `gcp-na` | `gcp_na`, `GCP-NA`, `GCP_NA` |
| GCP Europe | `gcp-eu` | `gcp_eu`, `GCP-EU`, `GCP_EU` |

Alias matching is **case-insensitive** and accepts both `-` and `_` separators.

---

### Available Service Keys

| Key | Description |
|---|---|
| `contentDelivery` | Content Delivery API (CDN) — for fetching published entries and assets |
| `contentManagement` | Content Management API — for creating and updating content |
| `graphqlDelivery` | GraphQL Delivery API |
| `graphqlPreview` | GraphQL Live Preview |
| `preview` | REST Live Preview |
| `auth` | Authentication API |
| `application` | Contentstack web application URL |
| `images` | Image Delivery |
| `assets` | Asset Delivery |
| `automate` | Workflow Automation API |
| `launch` | Contentstack Launch API |
| `developerHub` | Developer Hub API |
| `brandKit` | Brand Kit API |
| `genAI` | Generative AI / Knowledge Vault |
| `personalizeManagement` | Personalization Management API |
| `personalizeEdge` | Personalization Edge API |
| `composableStudio` | Composable Studio API |
| `assetManagement` | Asset Management API |

---

### Examples

#### Get a single service URL

```php
use Contentstack\Utils\Endpoint;

// AWS North America — Content Delivery
$url = Endpoint::getContentstackEndpoint('na', 'contentDelivery');
// → "https://cdn.contentstack.io"

// AWS Europe — Content Management
$url = Endpoint::getContentstackEndpoint('eu', 'contentManagement');
// → "https://eu-api.contentstack.com"

// Azure North America — GraphQL Delivery
$url = Endpoint::getContentstackEndpoint('azure-na', 'graphqlDelivery');
// → "https://azure-na-graphql.contentstack.com"

// GCP Europe — Auth
$url = Endpoint::getContentstackEndpoint('gcp-eu', 'auth');
// → "https://gcp-eu-auth-api.contentstack.com"
```

#### Use an alias instead of the canonical ID

```php
// All of these return the same NA content delivery URL
Endpoint::getContentstackEndpoint('us',     'contentDelivery'); // → https://cdn.contentstack.io
Endpoint::getContentstackEndpoint('na',     'contentDelivery'); // → https://cdn.contentstack.io
Endpoint::getContentstackEndpoint('aws-na', 'contentDelivery'); // → https://cdn.contentstack.io
Endpoint::getContentstackEndpoint('AWS_NA', 'contentDelivery'); // → https://cdn.contentstack.io
```

#### Get a URL without the `https://` scheme

Pass `true` as the third argument when you need just the hostname (e.g. for `Stack::setHost()`):

```php
$host = Endpoint::getContentstackEndpoint('eu', 'contentDelivery', true);
// → "eu-cdn.contentstack.com"
```

#### Get all endpoints for a region

Omit the `$service` argument to receive the full associative array:

```php
$endpoints = Endpoint::getContentstackEndpoint('au');
// → [
//     'contentDelivery'    => 'https://au-cdn.contentstack.com',
//     'contentManagement'  => 'https://au-api.contentstack.com',
//     'graphqlDelivery'    => 'https://au-graphql.contentstack.com',
//     'auth'               => 'https://au-auth-api.contentstack.com',
//     ...17 more keys
//   ]

// With omitHttps
$hosts = Endpoint::getContentstackEndpoint('au', '', true);
// → [
//     'contentDelivery'   => 'au-cdn.contentstack.com',
//     'contentManagement' => 'au-api.contentstack.com',
//     ...
//   ]
```

#### Via `Utils` (same result, no import change needed)

```php
use Contentstack\Utils\Utils;

$url = Utils::getContentstackEndpoint('gcp-na', 'contentDelivery');
// → "https://gcp-na-cdn.contentstack.com"
```

---

### Integration with the PHP Delivery SDK

Use `getContentstackEndpoint()` to resolve the host dynamically, then pass it to `Stack::setHost()`:

```php
use Contentstack\Contentstack;
use Contentstack\Utils\Endpoint;

$region = 'eu'; // change this one value to switch regions

// Resolve the content delivery host for the chosen region
$host = Endpoint::getContentstackEndpoint($region, 'contentDelivery', true);
// → "eu-cdn.contentstack.com"

// Initialise the delivery SDK
$stack = Contentstack::Stack('<API_KEY>', '<DELIVERY_TOKEN>', '<ENVIRONMENT>');
$stack->setHost($host);

// Fetch entries — all requests now go to the EU CDN
$result = $stack->ContentType('<CONTENT_TYPE_UID>')->Query()->toJSON()->find();
```

#### Switching regions without changing any other code

```php
$regions = ['na', 'eu', 'au', 'azure-na', 'azure-eu', 'gcp-na', 'gcp-eu'];

foreach ($regions as $region) {
    $host  = Endpoint::getContentstackEndpoint($region, 'contentDelivery', true);
    $stack = Contentstack::Stack('<API_KEY>', '<DELIVERY_TOKEN>', '<ENVIRONMENT>');
    $stack->setHost($host);

    $result = $stack->ContentType('<CONTENT_TYPE_UID>')->Query()->toJSON()->find();
    echo "{$region}: " . count($result[0]) . " entries\n";
}
```

---

### Error Handling

```php
use Contentstack\Utils\Endpoint;

// Empty region
try {
    Endpoint::getContentstackEndpoint('');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // → "Empty region provided. Please put valid region."
}

// Unknown region
try {
    Endpoint::getContentstackEndpoint('unknown-region', 'contentDelivery');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // → "Invalid region: unknown-region"
}

// Unknown service
try {
    Endpoint::getContentstackEndpoint('na', 'unknownService');
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage();
    // → "Service "unknownService" not found for region "na""
}

// regions.json missing and no network access
try {
    Endpoint::getContentstackEndpoint('na', 'contentDelivery');
} catch (\RuntimeException $e) {
    echo $e->getMessage();
    // → "contentstack/utils: regions.json not found and could not be downloaded.
    //    Run "composer install" or "composer refresh-regions" and ensure network access."
}
```