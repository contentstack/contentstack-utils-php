<?php
declare(strict_types=1);

namespace Contentstack\Utils\Resource;

use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\MarkType;
use Contentstack\Utils\Enum\NodeType;

interface RenderableInterface 
{
    /**
     * @param $embeddedObject - Embedded object content of type Asset/Entry 
     * @param $metadata - Tag details and attributes
     */
    function renderOptions(array $embeddedObject, Metadata $metadata): string;

    /**
     * @param $markType - MarkType for the text content
     * @param $text - Text content for rendering
     */
    function renderMark(MarkType $markType, string $text): string;

    /**
     * @param $nodeType - NodeType for the text content
     * @param $node - Json node content for rendering
     * @param $innerHtml - Child Html content for the node
     */
    function renderNode(string $nodeType, object $node, string $innerHtml): string;
}