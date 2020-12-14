<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';
require_once __DIR__ . '/Mock/CustomOptionMock.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Enum\StyleType;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Model\Option;
use PHPUnit\Framework\TestCase;

class CustomOptionTest extends TestCase
{
    public static $customeRender;
    public static $embeddedEntry;
    public static $embeddedContentType;
    public static $embeddedAsset;
    public static $text = "Text To set Link";

    public function getMetadata($itemType, $styleType, $linkText = null)
    {
        $html = "<test type={$itemType} sys-style-type={$styleType}>{$linkText}</test>";
        $element = Utility::getElement($html, '//test')[0];
        return new Metadata($element);
    }

    public function setUp(): void
    {
        CustomOptionTest::$customeRender = new CustomOptionMock(EmbedObjectMock::embeddedModel(''));
        CustomOptionTest::$embeddedEntry = EmbedObjectMock::embeddedEntryModel();
        CustomOptionTest::$embeddedContentType = EmbedObjectMock::embeddedContentTypeUidModel();
        CustomOptionTest::$embeddedAsset = EmbedObjectMock::embeddedAssetModel();
    }
    public function tearDown(): void{ }
  
    public function testEmbeddedContentTypeEntry(): void
    {
        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK));
        $this->assertEquals('<div type="entry" sys-style-type="block" ><p>uid</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE));
        $this->assertEquals('<span type="entry" sys-style-type="inline" >uid</span>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK));
        $this->assertEquals('<a type="entry" sys-style-type="link" ></a>', $resultString);
    }

    public function testEmbeddedEntry(): void
    {
        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK));
        $this->assertEquals('<div type="entry" sys-style-type="block" ><p>title</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE));
        $this->assertEquals('<span type="entry" sys-style-type="inline" >title</span>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK));
        $this->assertEquals('<a type="entry" sys-style-type="link" ></a>', $resultString);
    }

    public function testEmbeddedAsset(): void
    {
        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DISPLAY));
        $this->assertEquals('<img type="asset" sys-style-type="display"  />', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DOWNLOAD));
        $this->assertEquals('<a type="asset" sys-style-type="download" ></a>', $resultString);
    }
    public function testEmbeddedContentTypeEntryWithText(): void
    {
        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK, CustomOptionTest::$text));
        $this->assertEquals('<div type="entry" sys-style-type="block" ><p>uid</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE, CustomOptionTest::$text));
        $this->assertEquals('<span type="entry" sys-style-type="inline" >uid</span>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK, CustomOptionTest::$text));
        $this->assertEquals('<a type="entry" sys-style-type="link" >Text To set Link</a>', $resultString);
    }

    public function testEmbeddedEntryWithText(): void
    {
        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK, CustomOptionTest::$text));
        $this->assertEquals('<div type="entry" sys-style-type="block" ><p>title</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE, CustomOptionTest::$text));
        $this->assertEquals('<span type="entry" sys-style-type="inline" >title</span>', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK, CustomOptionTest::$text));
        $this->assertEquals('<a type="entry" sys-style-type="link" >Text To set Link</a>', $resultString);
    }

    public function testEmbeddedAssetWithText(): void
    {
        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DISPLAY, CustomOptionTest::$text));
        $this->assertEquals('<img type="asset" sys-style-type="display"  />', $resultString);

        $resultString = CustomOptionTest::$customeRender->renderOptions(CustomOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DOWNLOAD, CustomOptionTest::$text));
        $this->assertEquals('<a type="asset" sys-style-type="download" >Text To set Link</a>', $resultString);
    }
}