<?php

declare(strict_types=1);

namespace Contentstack\Utils;

use Contentstack\Utils\Model\Option;

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
        return $content;
    }

    /**
     * 
     *
     * @param string $content RTE content to render embedded objects
     * @param Option $option Option containing Entry and RendarableInterface 
     *
     * @return string Returns RTE content with render embedded objects
     */
    public static function renderContents(array $contents, Option $option): array
    {
        $result = array();
        foreach ($contents as $content) {
            $result[] = $content;
        }
        return $result;
    }

}
