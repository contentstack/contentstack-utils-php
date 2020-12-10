<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Enum\StyleType;
use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Model\Option;
use PHPUnit\Framework\TestCase;
class DefaultOptionTest extends TestCase
{
    public static $defaultRender;
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
        DefaultOptionTest::$defaultRender = new Option(EmbedObjectMock::embeddedModel(''));
        DefaultOptionTest::$embeddedEntry = EmbedObjectMock::embeddedEntryModel();
        DefaultOptionTest::$embeddedContentType = EmbedObjectMock::embeddedContentTypeUidModel();
        DefaultOptionTest::$embeddedAsset = EmbedObjectMock::embeddedAssetModel();
    }
    public function tearDown(): void{ }
  
    public function testEmbeddedContentTypeEntry(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK));
        $this->assertEquals($resultString, '<div><p>uid</p><p>Content type: <span>contentTypeUid</span></p></div>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE));
        $this->assertEquals($resultString, '<span>uid</span>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK));
        $this->assertEquals($resultString, '<a href=""></a>');
    }

    public function testEmbeddedEntry(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK));
        $this->assertEquals($resultString, '<div><p>title</p><p>Content type: <span>contentTypeUid</span></p></div>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE));
        $this->assertEquals($resultString, '<span>title</span>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK));
        $this->assertEquals($resultString, '<a href=""></a>');
    }

    public function testEmbeddedAsset(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DISPLAY));
        $this->assertEquals($resultString, '<img src="URL" alt="asset" />');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DOWNLOAD));
        $this->assertEquals($resultString, '<a href="URL"></a>');
    }
    public function testEmbeddedContentTypeEntryWithText(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<div><p>uid</p><p>Content type: <span>contentTypeUid</span></p></div>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<span>uid</span>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<a href="">Text To set Link</a>');
    }

    public function testEmbeddedEntryWithText(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<div><p>title</p><p>Content type: <span>contentTypeUid</span></p></div>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<span>title</span>');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<a href="">Text To set Link</a>');
    }

    public function testEmbeddedAssetWithText(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DISPLAY, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<img src="URL" alt="asset" />');

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DOWNLOAD, DefaultOptionTest::$text));
        $this->assertEquals($resultString, '<a href="URL">Text To set Link</a>');
    }
}