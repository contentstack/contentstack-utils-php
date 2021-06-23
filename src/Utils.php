<?php

declare(strict_types=1);

namespace Contentstack\Utils;

use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\NodeType;
use Contentstack\Utils\Enum\MarkType;

class Utils
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
            $resultHtml = Utils::nodeChildrenToHtml($content->children, $option);
        }
        return $resultHtml;
    }

    private static function nodeChildrenToHtml(array $nodes, Option $option): string {
        return \implode('', \array_map(function (object $node) use ($option): string {
            return Utils::nodeToHtml($node, $option);
        }, $nodes));
    }

    private static function nodeToHtml(object $node, Option $option): string {
        $resultHtml = '';
        if (isset($node->type)) {
            switch ($node->type) {
                case NodeType::get(NodeType::REFERENCE)->getValue():
                    $resultHtml = Utils::referenceToHtml($node, $option);
                    break;
                default:
                    $innerHtml = "";
                    if (isset($node->children)) 
                    {
                        $innerHtml = Utils::nodeChildrenToHtml($node->children, $option);
                    }
                    $resultHtml = $option->renderNode(
                        $node->type, 
                        $node, 
                        $innerHtml
                    );
                    break;
            }
        } else {
            $resultHtml = Utils::textToHtml($node, $option);
        }
        return $resultHtml;
    }

    private static function textToHtml(object $node, Option $option) 
    {
        $text = $node->text;
        if (isset($node->superscript) && $node->superscript) {
            $text = $option->renderMark(MarkType::get(MarkType::SUPERSCRIPT), $text);
        }
        if (isset($node->subscript) && $node->subscript) {
            $text = $option->renderMark(MarkType::get(MarkType::SUBSCRIPT), $text);
        }
        if (isset($node->inlineCode) && $node->inlineCode) {
            $text = $option->renderMark(MarkType::get(MarkType::INLINE_CODE), $text);
        }
        if (isset($node->strikethrough) && $node->strikethrough) {
            $text = $option->renderMark(MarkType::get(MarkType::STRIKE_THROUGH), $text);
        }
        if (isset($node->underline) && $node->underline) {
            $text = $option->renderMark(MarkType::get(MarkType::UNDERLINE), $text);
        }
        if (isset($node->italic) && $node->italic) {
            $text = $option->renderMark(MarkType::get(MarkType::ITALIC), $text);
        }
        if (isset($node->bold) && $node->bold) {
            $text = $option->renderMark(MarkType::get(MarkType::BOLD), $text);
        }
        return $text;
    }

    private static function referenceToHtml(object $node, Option $option) 
    {
        $resultHtml = '';
        if ($option->entry) {
            $metadata = new Metadata($node);
            $object = Utils::findObject($metadata, $option->entry);
            if (count($object) > 0) {
                $resultHtml = $option->renderOptions($object, $metadata);
            }
        }
        return $resultHtml;
    }

    private static function findEmbeddedObject(\DOMDocument $doc): array {
        $xpath = new \DOMXPath($doc);
        $elements = $xpath->query('//*[contains(@class, "embedded-asset") or contains(@class, "embedded-entry")]');
        $metadataArray = array();
        foreach ($elements as $node) {
            $metadataArray[] = new Metadata($node);
        }
        return $metadataArray;
    }

    private static function findObject(Metadata $metadata, array $entry): array
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

    static function innerHTML(\DOMElement $element) 
    { 
        $doc = $element->ownerDocument;
        $html = '';
        foreach ($element->childNodes as $node) {
            $html .= $doc->saveHTML($node);
        }
        return $html;
    } 
}
