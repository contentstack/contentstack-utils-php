<?php

declare(strict_types=1);

namespace Contentstack\Utils\Model;

use Contentstack\Utils\Resource\EntryEmbedable;
use Contentstack\Utils\Resource\RenderableInterface;
use Contentstack\Utils\Resource\EmbeddedObject;
use Contentstack\Utils\Enum\StyleType;

class Option implements RenderableInterface {

    /**
     * @var EntryEmbedable
     */
     
    public $entry;

    public function __construct(array $entry)
    {
        $this->entry = $entry;
    }

    function renderOptions(array $embeddedObject, Metadata $metadata): string
    {
        $resultString = "";
        switch ($metadata->getStyleType()) {
            case StyleType::get(StyleType::BLOCK): 
                $resultString =  "<div><p>" . ($embeddedObject["title"] ?? $embeddedObject["uid"]) . "</p><p>Content type: <span>". $embeddedObject["_content_type_uid"] ."</span></p></div>";
            break;
            case StyleType::get(StyleType::INLINE): 
                $resultString =  "<span>".($embeddedObject["title"] ?? $embeddedObject["uid"])."</span>";
            break;
            case StyleType::get(StyleType::LINK): 
                $resultString =  "<a href=\"".($metadata->getAttribute("href")->value ?? $embeddedObject["url"] ?? $embeddedObject["title"] ?? $embeddedObject["uid"] )."\">".($metadata->getText() ?? $embeddedObject["title"] ?? $embeddedObject["uid"])."</a>";
            break;
            case StyleType::get(StyleType::DISPLAY): 
                $resultString =  "<img src=\"".($metadata->getAttribute("src")->value ?? $embeddedObject["url"] )."\" alt=\"".($metadata->getAttribute("alt")->value ?? $embeddedObject["title"] ?? $embeddedObject["filename"] ?? $embeddedObject["uid"])."\" />";
            break;
            case StyleType::get(StyleType::DOWNLOAD): 
                $resultString =  "<a href=\"".($metadata->getAttribute("href")->value ?? $embeddedObject["url"])."\">".($metadata->getText() ?? $embeddedObject["filename"]?? $embeddedObject["title"] ?? $embeddedObject["uid"])."</a>";
            break;
        }
        return $resultString;
    }
}