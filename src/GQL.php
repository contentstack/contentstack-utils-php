<?php

declare(strict_types=1);

namespace Contentstack\Utils;

use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\NodeType;
use Contentstack\Utils\Enum\MarkType;

class GQL extends BaseParser
{
    public static function jsonToHtml(object $content, Option $option): array|string {
        $result = array();
        $embeddedItems = $content->embedded_itemsConnection != null ? $content->embedded_itemsConnection->edges : [];

        if (isset($content->json) && isset($content->json->children)) {
            return GQL::nodeChildrenToHtml($content->json->children, $option, function (Metadata $metadata) use ($option, $embeddedItems): string {
                $resultHtml = '';
                $object = GQL::findObject($metadata, $embeddedItems);   
                if (count($object) > 0) {
                    $resultHtml = $option->renderOptions($object, $metadata);
                }
                return $resultHtml;
            });
        } else if (is_array($content->json)) {
            foreach ($content->json as $node) {
                $result[] = GQL::nodeChildrenToHtml($node->children, $option, function (Metadata $metadata) use ($option, $embeddedItems): string {
                    $resultHtml = '';
                    $object = GQL::findObject($metadata, $embeddedItems);   
                    if (count($object) > 0) {
                        $resultHtml = $option->renderOptions($object, $metadata);
                    }
                    return $resultHtml;
                });
            }
        }
        return $result;
    }
    protected static function findObject(Metadata $metadata, array $embeddedItems): array
    {
        
        foreach ($embeddedItems as $entry)
        {
            if ($entry->node) 
            {
                $item = $entry->node;
                if ($item->system && $item->system->uid && $item->system->uid == $metadata->getItemUid())
                {
                    return json_decode(json_encode($item), true);
                }
            }
        } 
        return [];
    }
}