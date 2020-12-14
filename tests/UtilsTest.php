<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';
require_once __DIR__ . '/Mock/Constants.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Enum\EmbedItemType;

use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{

    public static $defaultRender;

    public function setUp(): void{
        UtilsTest::$defaultRender = new Option(EmbedObjectMock::embeddedModel(''));
    }
    public function tearDown(): void{ }

    public function testRenderBlankString(): void
    {   
        $embedString = Blank;
        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals($embedString, $sss);
    }

    public function testRenderArrayBlankString(): void
    {
        $sss = Utils::renderContents([Blank], new Option(EmbedObjectMock::embeddedModel([Blank])));
        $this->assertEquals([Blank], $sss);
    }

    public function testRenderString(): void
    {   
        $embedString = "<h1>TEST </h2>";
        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals('<h1>TEST </h2>', $sss);
    }

    public function testRenderArrayString(): void
    {        
        $embedString = "<h1>TEST </h2>";
        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals(['<h1>TEST </h2>'], $sss);
    }

    public function testNonHtmlString(): void
    {   
        $embedString = NoHTML;
        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals($embedString, $sss);
    }

    public function testNonHtmlArrayString(): void
    {        
        $embedString = NoHTML;
        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals([$embedString], $sss);
    }

    public function testHtmlString(): void
    {   
        $embedString = SimpleHTML;
        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals($embedString, $sss);
    }

    public function testHtmlArrayString(): void
    {        
        $embedString = SimpleHTML;
        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals([$embedString], $sss);
    }

    
    public function testEmbeddedBlankItems(): void
    {   
        $embedString = UnexpectedClose;
        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedBlankItems($embedString)));
        $this->assertEquals('', $sss);
    }

    public function testEmbeddedBlankItemsArray(): void
    {        
        $embedString = UnexpectedClose;
        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedBlankItems([$embedString])));
        $this->assertEquals([''], $sss);
    }

    public function testUnexpectedClose(): void
    {   
        $embedString = UnexpectedClose;
        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals('<span>uid</span>', $sss);
    }

    public function testUnexpectedCloseArray(): void
    {        
        $embedString = UnexpectedClose;
        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals(['<span>uid</span>'], $sss);
    }

    public function testNoChildmodel(): void
    {   
        $embedString = NoChildNode;
        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals('<span>uid</span>', $sss);
    }

    public function testNoChildmodelArray(): void
    {        
        $embedString = NoChildNode;
        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals(['<span>uid</span>'], $sss);
    }

    public function testAssetDisplay(): void
    {   
        $embedString = AssetDisplay;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals('<img src="URL" alt="title" />', $sss);
    }

    public function testAssetDisplayArray(): void
    {        
        $embedString = AssetDisplay;
        
        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals(['<img src="URL" alt="title" />'], $sss);
    }

    public function testEntryBlock(): void
    {   
        $embedString = EntryBlock;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div>', $sss);
    }

    public function testEntryBlockArray(): void
    {        
        $embedString = EntryBlock;

        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div>'], $sss);
    }

    public function testEntryInline(): void
    {   
        $embedString = EntryInline;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<span>blt55f6d8cbd7e03a1f</span>', $sss);
    }

    public function testEntryInlineArray(): void
    {        
        $embedString = EntryInline;

        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<span>blt55f6d8cbd7e03a1f</span>'], $sss);
    }

    public function testEntryLink(): void
    {   
        $embedString = EntryLink;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<a href="blt55f6d8cbd7e03a1f">{{title}}
</a>', $sss);
    }

    public function testEntryLinkArray(): void
    {        
        $embedString = EntryLink;

        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<a href="blt55f6d8cbd7e03a1f">{{title}}
</a>'], $sss);
    }

    public function testAsset(): void
    {   
        $embedString = AssetEmbed;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('<p></p>
<p></p>
', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals('<img src="URL" alt="title" /><p></p>
<p></p>
', $sss);
    }

    public function testAssetArray(): void
    {        
        $embedString = AssetEmbed;

        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals(['<p></p>
<p></p>
'], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals(['<img src="URL" alt="title" /><p></p>
<p></p>
'], $sss);
    }

    public function testEntryBlockLink(): void
    {   
        $embedString = EntryBlock.EntryLink;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a href="blt55f6d8cbd7e03a1f">{{title}}
</a>', $sss);
    }

    public function testEntryBlockLinkArray(): void
    {        
        $embedString = EntryBlock.EntryLink;

        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a href="blt55f6d8cbd7e03a1f">{{title}}
</a>'], $sss);
    }

    public function testEntryBlockLinkInline(): void
    {   
        $embedString = EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a href="blt55f6d8cbd7e03a1f">{{title}}
</a><span>blt55f6d8cbd7e03a1f</span>', $sss);
    }

    public function testEntryBlockLinkInlineArray(): void
    {        
        $embedString = EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a href="blt55f6d8cbd7e03a1f">{{title}}
</a><span>blt55f6d8cbd7e03a1f</span>'], $sss);
    }


    public function testAllEmbedStyles(): void
    {   
        $embedString = AssetDisplay.EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContent($embedString, UtilsTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new Option(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f', )));
        $this->assertEquals('<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a href="blt55f6d8cbd7e03a1f">{{title}}
</a><span>blt55f6d8cbd7e03a1f</span>', $sss);
    }

    public function testAllEmbedStylesArray(): void
    {        
        $embedString = AssetDisplay.EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContents([$embedString], UtilsTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new Option(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a href="blt55f6d8cbd7e03a1f">{{title}}
</a><span>blt55f6d8cbd7e03a1f</span>'], $sss);
    }
}
