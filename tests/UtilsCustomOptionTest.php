<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';
require_once __DIR__ . '/Mock/Constants.php';
require_once __DIR__ . '/Mock/CustomOptionMock.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Enum\EmbedItemType;

use PHPUnit\Framework\TestCase;

class UtilsCustomOptionTest extends TestCase
{

    public static $defaultRender;

    public function setUp(): void{
        UtilsCustomOptionTest::$defaultRender = new CustomOptionMock(EmbedObjectMock::embeddedModel(''));
    }
    public function tearDown(): void{ }

    public function testRenderBlankString(): void
    {   
        $embedString = Blank;
        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals($embedString, $sss);
    }

    public function testRenderArrayBlankString(): void
    {
        $sss = Utils::renderContents([Blank], new CustomOptionMock(EmbedObjectMock::embeddedModel([Blank])));
        $this->assertEquals([Blank], $sss);
    }

    public function testRenderString(): void
    {   
        $embedString = "<h1>TEST </h2>";
        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals('<h1>TEST </h2>', $sss);
    }

    public function testRenderArrayString(): void
    {        
        $embedString = "<h1>TEST </h2>";
        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals(['<h1>TEST </h2>'], $sss);
    }

    public function testNonHtmlString(): void
    {   
        $embedString = NoHTML;
        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals($embedString, $sss);
    }

    public function testNonHtmlArrayString(): void
    {        
        $embedString = NoHTML;
        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals([$embedString], $sss);
    }

    public function testHtmlString(): void
    {   
        $embedString = SimpleHTML;
        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals($embedString, $sss);
    }

    public function testHtmlArrayString(): void
    {        
        $embedString = SimpleHTML;
        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals([$embedString], $sss);
    }

    
    public function testEmbeddedBlankItems(): void
    {   
        $embedString = UnexpectedClose;
        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedBlankItems($embedString)));
        $this->assertEquals('', $sss);
    }

    public function testEmbeddedBlankItemsArray(): void
    {        
        $embedString = UnexpectedClose;
        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedBlankItems([$embedString])));
        $this->assertEquals([''], $sss);
    }

    public function testUnexpectedClose(): void
    {   
        $embedString = UnexpectedClose;
        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals('<span class="embedded-asset" type="asset" data-sys-entry-uid="uid" data-sys-content-type-uid="data-sys-content-type-uid" style="display:inline;" sys-style-type="inline" >uid</span>', $sss);
    }

    public function testUnexpectedCloseArray(): void
    {        
        $embedString = UnexpectedClose;
        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals(['<span class="embedded-asset" type="asset" data-sys-entry-uid="uid" data-sys-content-type-uid="data-sys-content-type-uid" style="display:inline;" sys-style-type="inline" >uid</span>'], $sss);
    }

    public function testNoChildmodel(): void
    {   
        $embedString = NoChildNode;
        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString)));
        $this->assertEquals('<span class="embedded-asset" type="asset" data-sys-entry-uid="uid" data-sys-content-type-uid="data-sys-content-type-uid" style="display:inline;" sys-style-type="inline" >uid</span>', $sss);
    }

    public function testNoChildmodelArray(): void
    {        
        $embedString = NoChildNode;
        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString])));
        $this->assertEquals(['<span class="embedded-asset" type="asset" data-sys-entry-uid="uid" data-sys-content-type-uid="data-sys-content-type-uid" style="display:inline;" sys-style-type="inline" >uid</span>'], $sss);
    }

    public function testAssetDisplay(): void
    {   
        $embedString = AssetDisplay;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals('<img class="embedded-asset" type="asset" data-sys-asset-uid="blt8d49bb742bcf2c83" style="display:inline;" sys-style-type="display"  />', $sss);
    }

    public function testAssetDisplayArray(): void
    {        
        $embedString = AssetDisplay;
        
        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals(['<img class="embedded-asset" type="asset" data-sys-asset-uid="blt8d49bb742bcf2c83" style="display:inline;" sys-style-type="display"  />'], $sss);
    }

    public function testEntryBlock(): void
    {   
        $embedString = EntryBlock;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div>', $sss);
    }

    public function testEntryBlockArray(): void
    {        
        $embedString = EntryBlock;

        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div>'], $sss);
    }

    public function testEntryInline(): void
    {   
        $embedString = EntryInline;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<span class="embedded-entry inline-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="inline" >blt55f6d8cbd7e03a1f</span>', $sss);
    }

    public function testEntryInlineArray(): void
    {        
        $embedString = EntryInline;

        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<span class="embedded-entry inline-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="inline" >blt55f6d8cbd7e03a1f</span>'], $sss);
    }

    public function testEntryLink(): void
    {   
        $embedString = EntryLink;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a>', $sss);
    }

    public function testEntryLinkArray(): void
    {        
        $embedString = EntryLink;

        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a>'], $sss);
    }

    public function testAsset(): void
    {   
        $embedString = AssetEmbed;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('<p></p>
<p></p>
', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals('<img class="embedded-asset" data-sys-asset-filelink="https://images.contentstack.com/v3/assets/blt77263d300aee3e6b/blt8d49bb742bcf2c83/5f744bfcb3d3d20813386c10/clitud.jpeg" data-sys-asset-uid="blt8d49bb742bcf2c83" data-sys-asset-filename="Cuvier-67_Autruche_d_Afrique.jpg" data-sys-asset-contenttype="image/jpeg" data-sys-asset-alt="Cuvier-67_Autruche_d_Afrique.jpg" data-sys-asset-caption="somecaption" data-sys-asset-link="http://abc.com" data-sys-asset-position="center" data-sys-asset-isnewtab="true" type="asset" sys-style-type="display"  /><p></p>
<p></p>
', $sss);
    }

    public function testAssetArray(): void
    {        
        $embedString = AssetEmbed;

        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals(['<p></p>
<p></p>
'], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], '', 'blt8d49bb742bcf2c83')));
        $this->assertEquals(['<img class="embedded-asset" data-sys-asset-filelink="https://images.contentstack.com/v3/assets/blt77263d300aee3e6b/blt8d49bb742bcf2c83/5f744bfcb3d3d20813386c10/clitud.jpeg" data-sys-asset-uid="blt8d49bb742bcf2c83" data-sys-asset-filename="Cuvier-67_Autruche_d_Afrique.jpg" data-sys-asset-contenttype="image/jpeg" data-sys-asset-alt="Cuvier-67_Autruche_d_Afrique.jpg" data-sys-asset-caption="somecaption" data-sys-asset-link="http://abc.com" data-sys-asset-position="center" data-sys-asset-isnewtab="true" type="asset" sys-style-type="display"  /><p></p>
<p></p>
'], $sss);
    }

    public function testEntryBlockLink(): void
    {   
        $embedString = EntryBlock.EntryLink;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a>', $sss);
    }

    public function testEntryBlockLinkArray(): void
    {        
        $embedString = EntryBlock.EntryLink;

        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a>'], $sss);
    }

    public function testEntryBlockLinkInline(): void
    {   
        $embedString = EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals('<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a><span class="embedded-entry inline-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="inline" >blt55f6d8cbd7e03a1f</span>', $sss);
    }

    public function testEntryBlockLinkInlineArray(): void
    {        
        $embedString = EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a><span class="embedded-entry inline-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="inline" >blt55f6d8cbd7e03a1f</span>'], $sss);
    }


    public function testAllEmbedStyles(): void
    {   
        $embedString = AssetDisplay.EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContent($embedString, UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals('', $sss);

        $sss = Utils::renderContent($embedString, new CustomOptionMock(EmbedObjectMock::embeddedModel($embedString, 'blt55f6d8cbd7e03a1f', )));
        $this->assertEquals('<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a><span class="embedded-entry inline-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="inline" >blt55f6d8cbd7e03a1f</span>', $sss);
    }

    public function testAllEmbedStylesArray(): void
    {        
        $embedString = AssetDisplay.EntryBlock.EntryLink.EntryInline;

        $sss = Utils::renderContents([$embedString], UtilsCustomOptionTest::$defaultRender);
        $this->assertEquals([''], $sss);

        $sss = Utils::renderContents([$embedString], new CustomOptionMock(EmbedObjectMock::embeddedModel([$embedString], 'blt55f6d8cbd7e03a1f')));
        $this->assertEquals(['<div class="embedded-entry block-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" sys-style-type="block" ><p>blt55f6d8cbd7e03a1f</p><p>Content type: <span>contentTypeUid</span></p></div><a class="embedded-entry link-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="link" >{{title}}
</a><span class="embedded-entry inline-entry" type="entry" data-sys-entry-uid="blt55f6d8cbd7e03a1f" data-sys-content-type-uid="article" style="display:inline;" sys-style-type="inline" >blt55f6d8cbd7e03a1f</span>'], $sss);
    }
}
