<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

use Contentstack\Utils\Resource\EntryEmbedable;
use Contentstack\Utils\Resource\RenderableInterface;
use Contentstack\Utils\Resource\EmbeddedObject;
use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\StyleType;

class CustomOptionMock extends Option {

    function renderOptions(array $embeddedObject, Metadata $metadata): string
    {
        $attributeStrig = "";
        $resultString = "";

        foreach($metadata->getAttributes() as $attribute_name => $attribute_node)
        { 
            $attributeStrig = $attributeStrig."{$attribute_name}=\"{$attribute_node->value}\" ";
        }

        switch ($metadata->getStyleType()) {
            case StyleType::get(StyleType::BLOCK): 
                $resultString =  "<div ".$attributeStrig."><p>" . ($embeddedObject["title"] ?? $embeddedObject["uid"]) . "</p><p>Content type: <span>". $embeddedObject["_content_type_uid"] ."</span></p></div>";
            break;
            case StyleType::get(StyleType::INLINE): 
                $resultString =  "<span ".$attributeStrig.">".($embeddedObject["title"] ?? $embeddedObject["uid"])."</span>";
            break;
            case StyleType::get(StyleType::LINK): 
                $resultString =  "<a ".$attributeStrig.">".($metadata->getText() ?? $embeddedObject["title"] ?? $embeddedObject["uid"])."</a>";
            break;
            case StyleType::get(StyleType::DISPLAY): 
                $resultString =  "<img ".$attributeStrig." />";
            break;
            case StyleType::get(StyleType::DOWNLOAD): 
                $resultString =  "<a ".$attributeStrig.">".($metadata->getText() ?? $embeddedObject["filename"]?? $embeddedObject["title"] ?? $embeddedObject["uid"])."</a>";
            break;
        }
        return $resultString;
    }
}