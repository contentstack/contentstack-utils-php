<?php

declare(strict_types=1);

namespace Contentstack\Utils\Interfaces;

abstract class EntryEmbedable 
{
    /**
     * @var array
     */
    public $embeddedEntries;

    /**
     * @var array
     */
    public $embeddedAssets;
}