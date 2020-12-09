<?php
declare(strict_types=1);

namespace Contentstack\Utils\Resource;

use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Model\Metadata;

interface RenderableInterface 
{
    function renderOptions(array $embeddedObject, Metadata $metadata): string;
}