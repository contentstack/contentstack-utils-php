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
        $this->assertEquals($metadata->getItemType(), EmbedItemType::ENTRY());
        $this->assertEquals($metadata->getStyleType(), StyleType::BLOCK());
        $this->assertEquals($metadata->getItemUid(), '');
        $this->assertEquals($metadata->getContentTypeUid(), '');
        $this->assertEquals($metadata->getText(), 'TEST');
    }

    public function testWrongAttributes(): void
    {
        $metadata = new Metadata(Utility::getElement('<h1 type="" sys-style-type="" data-sys-entry-uid="" data-sys-content-type-uid="">TEST</h1>', '//h1')[0]);
        $this->assertEquals($metadata->getItemType(), EmbedItemType::ENTRY());
        $this->assertEquals($metadata->getStyleType(), StyleType::BLOCK());
        $this->assertEquals($metadata->getItemUid(), '');
        $this->assertEquals($metadata->getContentTypeUid(), '');
        $this->assertEquals($metadata->getText(), 'TEST');
    }

    public function testAttributes(): void
    {
        $metadata = new Metadata(Utility::getElement('<h1 type="asset" sys-style-type="inline" data-sys-entry-uid="uid" data-sys-content-type-uid="contentType">
TEST</h1>', '//h1')[0]);
        $this->assertEquals($metadata->getItemType(), EmbedItemType::ASSET());
        $this->assertEquals($metadata->getStyleType(), StyleType::INLINE());
        $this->assertEquals($metadata->getItemUid(), 'uid');
        $this->assertEquals($metadata->getContentTypeUid(), 'contentType');
        $this->assertEquals($metadata->getText(), '
TEST');
    }

    public function testAssetUidAttributes(): void
    {
        $html = '<h1 type="asset" sys-style-type="inline" data-sys-asset-uid="assetuid">TEST</h1>';
        $element = Utility::getElement($html, '//h1')[0];
        $metadata = new Metadata($element);
        $this->assertEquals($metadata->getItemType(), EmbedItemType::ASSET());
        $this->assertEquals($metadata->getStyleType(), StyleType::INLINE());
        $this->assertEquals($metadata->getItemUid(), 'assetuid');
        $this->assertEquals($metadata->getContentTypeUid(), '');
        $this->assertEquals($metadata->getText(), 'TEST');
        $this->assertEquals($metadata->getOuterHTML(), Utility::getOuterHTML($element));
        $this->assertEquals($metadata->getAttributes(), $element->attributes);
    }
}