<?php

declare(strict_types=1);

namespace Contentstack\Utils\Model;

use Contentstack\Utils\Resource\EntryEmbedable;
use Contentstack\Utils\Resource\RenderableInterface;
use Contentstack\Utils\Resource\EmbeddedObject;
use Contentstack\Utils\Enum\StyleType;
use Contentstack\Utils\Enum\MarkType;

class Option implements RenderableInterface {

    /**
     * @var EntryEmbedable
     */
     
    public $entry;

    public function __construct(array $entry = null)
    {
        $this->entry = $entry;
    }
    
    function renderMark(MarkType $markType, string $text): string
    {
        $resultString = "";

        switch ($markType) {
            case MarkType::get(MarkType::BOLD):
                $resultString = "<strong>".$text."</strong>";
                break;
            case MarkType::get(MarkType::ITALIC):
                $resultString = "<em>".$text."</em>";
                break;
            case MarkType::get(MarkType::UNDERLINE):
                $resultString = "<u>".$text."</u>";
                break;
            case MarkType::get(MarkType::STRIKE_THROUGH):
                $resultString = "<strike>".$text."</strike>";
                break;
            case MarkType::get(MarkType::INLINE_CODE):
                $resultString = "<span>".$text."</span>";
                break;
            case MarkType::get(MarkType::SUBSCRIPT):
                $resultString = "<sub>".$text."</sub>";
                break;
            case MarkType::get(MarkType::SUPERSCRIPT):
                $resultString = "<sup>".$text."</sup>";
                break;
        }
        return $resultString;
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