<?php

declare(strict_types=1);

namespace Contentstack\Utils;
class Utils
{
    /**
     * 
     *
     * @param string $content RTE content to render embedded objects
     *
     * @return string Returns RTE content with render embedded objects
     */
    public static function renderContent(string $content): string
    {
        return $content;
    }

    /**
     * 
     *
     * @param string $content RTE content to render embedded objects
     *
     * @return string Returns RTE content with render embedded objects
     */
    public static function renderContents(string ...$contents): array
    {
        $result = array();
        foreach ($contents as $content) {
            $result[] = $content;
        }
        return $result;
    }

}
