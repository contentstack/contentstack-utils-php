<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Enums\EmbedItemType;

class ExampleTest extends \PHPUnit\Framework\TestCase
{

  public function setUp(): void{ }
  public function tearDown(): void{ }
    /**
     * Test that true does in fact equal true
     */
    public function testTrueIsTrue()
    {
        $sss = Utils::renderContent('s');
        $this->assertEquals('s', $sss);
    }


    public function testRenderContents()
    {
        $sss = Utils::renderContents('s');
        $this->assertEquals(['s'], $sss);
    }
}
