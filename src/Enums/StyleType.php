<?php

declare(strict_types=1);

namespace Contentstack\Utils\Enums;

use MabeEnum\Enum;

class StyleType extends Enum
{
    const BLOCK = 'block';
    const INLINE = 'inline';
    const LINK = 'link';
    const DOWNLOAD = 'download';
    const DISPLAY = 'display';
}