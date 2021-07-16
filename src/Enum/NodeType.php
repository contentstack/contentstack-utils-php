<?php

declare(strict_types=1);

namespace Contentstack\Utils\Enum;

use MabeEnum\Enum;

class NodeType extends Enum
{
    const DOCUMENT = 'doc';
    const PARAGRAPH = 'p';
    
    const LINK = 'a';
    const IMAGE = 'img';
    const EMBED = 'embed';

    const HEADING_1 = 'h1';
    const HEADING_2 = 'h2';
    const HEADING_3 = 'h3';
    const HEADING_4 = 'h4';
    const HEADING_5 = 'h5';
    const HEADING_6 = 'h6';
  
    const ORDER_LIST = 'ol';
    const UNORDER_LIST = 'ul';
    const LIST_ITEM = 'li';
  
    const HR = 'hr';

    const TABLE = 'table';
    const TABLE_HEADER = 'thead';
    const TABLE_BODY = 'tbody';
    const TABLE_FOOTER = 'tfoot';
    const TABLE_ROW = 'tr';
    const TABLE_HEAD = 'th';
    const TABLE_DATA = 'td';

    const BLOCK_QUOTE = 'blockquote';
    const CODE = 'code';

    const TEXT = 'text';
    const REFERENCE = 'reference';
}