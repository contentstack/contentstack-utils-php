<?php

declare(strict_types=1);

namespace Contentstack\Utils\Enum;

use MabeEnum\Enum;

class MarkType extends Enum
{
    const BOLD = 'bold';
    const ITALIC = 'italic';
    const UNDERLINE = 'underline';
    
    const STRIKE_THROUGH = 'strikethrough';
    const INLINE_CODE = 'inlineCode';
    
    const SUBSCRIPT = 'subscript';
    const SUPERSCRIPT = 'superscript';
}