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