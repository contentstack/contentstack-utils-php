<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';
require_once __DIR__ . '/Mock/JsonMock.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Enum\EmbedItemType;

use PHPUnit\Framework\TestCase;

class UtilsJsonToHtmlTest extends TestCase
{

    public static $defaultRender;

    public function setUp(): void{
        UtilsTest::$defaultRender = new Option(EmbedObjectMock::embeddedModel(''));
    }
    public function tearDown(): void{ }

    public function testShouldReturnEmptyStringForBlankDocument(): void
    {   
        $jsonObject = json_decode(BlankDocument);
        $sss = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(Blank, trim(preg_replace('/\s\s+/', '', $sss)));
    }

    public function testShouldReturnEmptyStringForBlankDocumentArray(): void
    {
        $jsonObject = json_decode(BlankDocument);
        $sss = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([Blank], $sss);
    }

    public function testShouldReturnStringForPlainTextDocument(): void
    {   
        $jsonObject = json_decode(PlainTextJson);
        $sss = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(PlainTextHtml, trim(preg_replace('/\s\s+/', '', $sss)));
    }

    public function testShouldReturnStringForPlainTextDocumentArray(): void
    {
        $jsonObject = json_decode(PlainTextJson);
        $sss = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([PlainTextHtml], $sss);
    }
}