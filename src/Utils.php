<?php

declare(strict_types=1);

namespace Contentstack\Utils;

use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\NodeType;
use Contentstack\Utils\Enum\MarkType;

class Utils extends BaseParser
{
    /**
     * 
     *
     * @param string $content RTE content to render embedded objects
     * @param Option $option Option containing Entry and RendarableInterface 
     *
     * @return string Returns RTE content with render embedded objects
     */
    public static function renderContent(string $content, Option $option): string
    {
        if ($content) {
            $doc = new \DOMDocument();
            @$doc->loadHTML($content);
            $body = $doc->getElementsByTagName('body');
            $resultString = Utils::innerHTML($body->item(0));
            $metadataArray = Utils::findEmbeddedObject($doc);
            if ($metadataArray) {
                foreach ($metadataArray as $metadata) {
                    $object = Utils::findObject($metadata, $option->entry);
                    $replaceString = '';
                    if (count($object) > 0) {
                        $replaceString = $option->renderOptions($object, $metadata);
                    }
                    $resultString = str_replace($metadata->getOuterHTML(), $replaceString, $resultString, $i);    
                }
                return $resultString;
            }
        }
        return $content;
    }

    /**
     * 
     *
     * @param string $content RTE content to render embedded objects
     * @param Option $option Option containing Entry and RenderableInterface 
     *
     * @return string Returns RTE content with render embedded objects
     */
    public static function renderContents(array $contents, Option $option): array
    {
        $result = array();
        foreach ($contents as $content) {
            $result[] = Utils::renderContent($content, $option);
        }
        return $result;
    }

    public static function jsonArrayToHtml(array $contents, Option $option): array {
        $result = array();
        foreach ($contents as $content) {
            $result[] = Utils::jsonToHtml($content, $option);
        }
        return $result;
    }

    public static function jsonToHtml(object $content, Option $option): string {
        $resultHtml = '';
        if (isset($content->children)) {
            $resultHtml = Utils::nodeChildrenToHtml($content->children, $option, function (Metadata $metadata) use ($option): string {
                $resultHtml = '';
                if ($option->entry) {
                    $object = Utils::findObject($metadata, $option->entry);   
                    if (count($object) > 0) {
                        $resultHtml = $option->renderOptions($object, $metadata);
                    }
                }
                return $resultHtml;
            });
        }
        return $resultHtml;
    }

    protected static function findObject(Metadata $metadata, array $entry): array
    {
        if (array_key_exists('_embedded_items', $entry)) 
        {
            foreach ($entry["_embedded_items"] as $key => $value)
            {
                foreach ($value as $object) 
                { 
                    if ($object["uid"] && $object["uid"] == $metadata->getItemUid())
                    {
                        return $object;
                    }
                }
            } 
        }
        return [];
    }
}
