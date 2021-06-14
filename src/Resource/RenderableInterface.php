<?php
declare(strict_types=1);

namespace Contentstack\Utils\Resource;

use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\MarkType;

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
}