<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';

use Contentstack\Utils\Model\Metadata;
use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Enum\StyleType;
use PHPUnit\Framework\TestCase;
// $doc = new \DOMDocument();
// @$doc->loadHTML('<figure class="embedded-entry block-entry" data-sys-entry-uid="blttitleUpdateUID" data-sys-entry-locale="en-us" data-sys-content-type-uid="embeddedrte" sys-style-type="block" type="entry"></figure>\n<p></p>\n<figure class="embedded-asset" data-sys-asset-filelink="https://contentstack.image/v3/assets/blturl/bltassetEmbuid/5f57ae45c83b840a87d92910/html5.png" data-sys-asset-uid="bltassetEmbuid" data-sys-asset-filename="svg-logo-text.png" data-sys-asset-contenttype="image/png" type="asset" sys-style-type="display"></figure>');
// $xpath = new \DOMXPath($doc);
// $elements = $xpath->query('//*[contains(@class, "embedded-asset") or contains(@class, "embedded-entry")]');
// foreach ($elements as $entry) {
//     Utility::debug($entry->attributes);
// }

class MetadataTest extends TestCase
{
    public function setUp(): void{ }
    public function tearDown(): void{ }
    /**
     * Test that true does in fact equal true
     */
    public function testBlankAttributes(): void
    {
        $metadata = new Metadata(Utility::getElement('<h1>TEST</h1>', '//h1')[0]);
        $this->assertEquals(EmbedItemType::ENTRY(), $metadata->getItemType());
        $this->assertEquals(StyleType::BLOCK(), $metadata->getStyleType());
        $this->assertEquals('', $metadata->getItemUid());
        $this->assertEquals('', $metadata->getContentTypeUid());
        $this->assertEquals('TEST', $metadata->getText());
    }

    public function testWrongAttributes(): void
    {
        $metadata = new Metadata(Utility::getElement('<h1 type="" sys-style-type="" data-sys-entry-uid="" data-sys-content-type-uid="">TEST</h1>', '//h1')[0]);
        $this->assertEquals(EmbedItemType::ENTRY(), $metadata->getItemType());
        $this->assertEquals(StyleType::BLOCK(), $metadata->getStyleType());
        $this->assertEquals('', $metadata->getItemUid());
        $this->assertEquals('', $metadata->getContentTypeUid());
        $this->assertEquals('TEST', $metadata->getText());
    }

    public function testAttributes(): void
    {
        $metadata = new Metadata(Utility::getElement('<h1 type="asset" sys-style-type="inline" data-sys-entry-uid="uid" data-sys-content-type-uid="contentType">
TEST</h1>', '//h1')[0]);
        $this->assertEquals(EmbedItemType::ASSET(), $metadata->getItemType());
        $this->assertEquals(StyleType::INLINE(), $metadata->getStyleType());
        $this->assertEquals('uid', $metadata->getItemUid());
        $this->assertEquals('contentType', $metadata->getContentTypeUid());
        $this->assertEquals('
TEST', $metadata->getText());
    }

    public function testAssetUidAttributes(): void
    {
        $html = '<h1 type="asset" sys-style-type="inline" data-sys-asset-uid="assetuid">TEST</h1>';
        $element = Utility::getElement($html, '//h1')[0];
        $metadata = new Metadata($element);
        $this->assertEquals(EmbedItemType::ASSET(), $metadata->getItemType());
        $this->assertEquals(StyleType::INLINE(), $metadata->getStyleType());
        $this->assertEquals('assetuid', $metadata->getItemUid());
        $this->assertEquals('', $metadata->getContentTypeUid());
        $this->assertEquals('TEST', $metadata->getText());
        $this->assertEquals(Utility::getOuterHTML($element), $metadata->getOuterHTML());
        $this->assertEquals($element->attributes, $metadata->getAttributes());
    }
}