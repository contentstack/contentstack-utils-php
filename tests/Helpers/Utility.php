<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

class Utility 
{

     /**
     * DEBUGGING MESSAGE
     * 
     * @param object  $input - object to debug
     * @param boolean $exit  - to exit on debug
     * 
     * @return object
     * */
    public static function debug($input, $exit = false)
    {
        echo "<pre>";
            print_r($input);
        echo "</pre>";
        if ($exit) { 
            exit();
        }
    }

    public static function getElement($xml, $query): \DOMNodeList
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($xml);
        $xpath = new \DOMXPath($doc);
        return $xpath->query($query);
    }

    public static function getOuterHTML($element): string
    {
        $doc = new \DOMDocument();
        $doc->appendChild($doc->importNode($element, true));
        return $doc->saveHTML();
    }
}