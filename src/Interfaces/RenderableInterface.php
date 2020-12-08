<?php
declare(strict_types=1);

namespace Contentstack\Utils;

use Contentstack\Utils\Enums\EmbedItemType;
use Contentstack\Utils\Models\Metadata;

interface RenderableInterface 
{
    function renderOption();
}