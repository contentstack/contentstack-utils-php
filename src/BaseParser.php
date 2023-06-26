<?php

declare(strict_types=1);

namespace Contentstack\Utils;

use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\NodeType;
use Contentstack\Utils\Enum\MarkType;

class BaseParser
{

    protected static function nodeChildrenToHtml(array $nodes, Option $option, $renderEmbed): string {
        return \implode('', \array_map(function (object $node) use ($option, $renderEmbed): string {
            return BaseParser::nodeToHtml($node, $option, $renderEmbed);
        }, $nodes));
    }

    protected static function nodeToHtml(object $node, Option $option, $renderEmbed): string {
        $resultHtml = '';
        if (isset($node->type)) {
            switch ($node->type) {
                case NodeType::get(NodeType::REFERENCE)->getValue():
                    $resultHtml = BaseParser::referenceToHtml($node, $renderEmbed);
                    break;
                default:
                    $innerHtml = "";
                    if (isset($node->children)) 
                    {
                        $innerHtml = BaseParser::nodeChildrenToHtml($node->children, $option, $renderEmbed);
                    }
                    $resultHtml = $option->renderNode(
                        $node->type, 
                        $node, 
                        $innerHtml
                    );
                    break;
            }
        } else {
            $resultHtml = BaseParser::textToHtml($node, $option);
        }
        return $resultHtml;
    }

    protected static function textToHtml(object $node, Option $option) 
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
        if (isset($node->break) && $node->break) {
            $text = $option->renderMark(MarkType::get(MarkType::BREAK), $text);
        }
        return $text;
    }

    protected static function referenceToHtml(object $node, $renderEmbed) 
    {
        $metadata = new Metadata($node);
        return $renderEmbed($metadata);
    }

    protected static function findEmbeddedObject(\DOMDocument $doc): array {
        $xpath = new \DOMXPath($doc);
        $elements = $xpath->query('//*[contains(@class, "embedded-asset") or contains(@class, "embedded-entry")]');
        $metadataArray = array();
        foreach ($elements as $node) {
            $metadataArray[] = new Metadata($node);
        }
        return $metadataArray;
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