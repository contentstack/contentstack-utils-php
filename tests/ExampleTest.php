<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Enum\EmbedItemType;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{

  public function setUp(): void{ }
  public function tearDown(): void{ }
    /**
     * Test that true does in fact equal true
     */
    public function testTrueIsTrue(): void
    {
        $sss = Utils::renderContent('s', new Option(EmbedObjectMock::embeddedModel('s')));
        $this->assertEquals('s', $sss);
    }


    public function testRenderContents(): void
    {
        $sss = Utils::renderContents(['s'], new Option(EmbedObjectMock::embeddedModel('s')));
        $this->assertEquals(['s'], $sss);
    }
}
