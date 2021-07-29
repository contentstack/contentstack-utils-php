<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';
require_once __DIR__ . '/Mock/Constants.php';

use Contentstack\Utils\GQL;
use Contentstack\Utils\Model\Option;
use Contentstack\Utils\Enum\EmbedItemType;

use PHPUnit\Framework\TestCase;

class GQLTest extends TestCase
{

    protected static function gqlEntry(string $node, string $items = '""') {
        return '{
            "uid": "EntryUID",
            "single_rte": {
                "json": '.$node.',
                "embedded_itemsConnection": '.$items.'
            },
            "multiple_rte": {
                "json": ['.$node.'],
                "embedded_itemsConnection": '.$items.'
            }
        }';
    }

    public static $defaultRender;

    public function setUp(): void{
        GQLTest::$defaultRender = new Option(EmbedObjectMock::embeddedModel(''));
    }

    public function tearDown(): void{ }

    public function testShouldReturnEmptyStringForBlankDocument(): void
    {   
        $jsonString = GQLTest::gqlEntry(BlankDocument);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(Blank, trim(preg_replace('/\s\s+/', '', $result)));
        $this->assertEquals([Blank], $arrayResult);
    }

    public function testShouldReturnH1TagOnNonChild(): void
    {
        $jsonString = GQLTest::gqlEntry(H1NonChildJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals('<h1></h1>', $result);
        $this->assertEquals(['<h1></h1>'], $arrayResult);
    }

    public function testShouldReturnStringForPlainTextDocument(): void
    {
        $jsonString = GQLTest::gqlEntry(PlainTextJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(PlainTextHtml, $result);
        $this->assertEquals([PlainTextHtml], $arrayResult);
    }

    public function testShouldReturnParagraphOnParagraph(): void
    {
        $jsonString = GQLTest::gqlEntry(ParagraphJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(ParagraphHtml, $result);
        $this->assertEquals([ParagraphHtml], $arrayResult);
    }

    public function testShouldReturnLinkTagOnLink(): void
    {
        $jsonString = GQLTest::gqlEntry(LinkInPJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(LinkInPHtml, $result);
        $this->assertEquals([LinkInPHtml], $arrayResult);
    }

    public function testShouldReturnImageTagOnImage(): void
    {
        $jsonString = GQLTest::gqlEntry(ImgJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(ImgHtml, $result);
        $this->assertEquals([ImgHtml], $arrayResult);
    }

    public function testShouldReturnEmbedTagOnEmbed(): void
    {
        $jsonString = GQLTest::gqlEntry(EmbedJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(EmbedHtml, $result);
        $this->assertEquals([EmbedHtml], $arrayResult);
    }

    public function testShouldReturnH1TagOnH1(): void
    {
        $jsonString = GQLTest::gqlEntry(H1Json);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(H1Html, $result);
        $this->assertEquals([H1Html], $arrayResult);
    }

    public function testShouldReturnH2TagOnH2(): void
    {
        $jsonString = GQLTest::gqlEntry(H2Json);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(H2Html, $result);
        $this->assertEquals([H2Html], $arrayResult);
    }

    public function testShouldReturnH1TagOnH3(): void
    {
        $jsonString = GQLTest::gqlEntry(H3Json);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(H3Html, $result);
        $this->assertEquals([H3Html], $arrayResult);
    }

    public function testShouldReturnH1TagOnH4(): void
    {
        $jsonString = GQLTest::gqlEntry(H4Json);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(H4Html, $result);
        $this->assertEquals([H4Html], $arrayResult);
    }

    public function testShouldReturnH1TagOnH5(): void
    {
        $jsonString = GQLTest::gqlEntry(H5Json);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(H5Html, $result);
        $this->assertEquals([H5Html], $arrayResult);
    }

    public function testShouldReturnH1TagOnH6(): void
    {
        $jsonString = GQLTest::gqlEntry(H6Json);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(H6Html, $result);
        $this->assertEquals([H6Html], $arrayResult);
    }

    public function testShouldReturnOrderListTagOnOrderList(): void
    {
        $jsonString = GQLTest::gqlEntry(OrderListJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(OrderListHtml, $result);
        $this->assertEquals([OrderListHtml], $arrayResult);
    }

    public function testShouldReturnUnOrderListTagOnUnOrderList(): void
    {
        $jsonString = GQLTest::gqlEntry(UnorderListJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(UnorderListHtml, $result);
        $this->assertEquals([UnorderListHtml], $arrayResult);
    }

    public function testShouldReturnHrTagOnUnHr(): void
    {
        $jsonString = GQLTest::gqlEntry(HRJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals('<hr>', $result);
        $this->assertEquals(['<hr>'], $arrayResult);
    }

    public function testShouldReturnTableContentOnTable(): void
    {
        $jsonString = GQLTest::gqlEntry(TableJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(TableHtml, $result);
        $this->assertEquals([TableHtml], $arrayResult);
    }

    public function testShouldReturnBlockquoteTagOnBlockquote(): void
    {
        $jsonString = GQLTest::gqlEntry(BlockquoteJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(BlockquoteHtml, $result);
        $this->assertEquals([BlockquoteHtml], $arrayResult);
    }

    public function testShouldReturnCodeTagOnUnCode(): void
    {
        $jsonString = GQLTest::gqlEntry(CodeJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(CodeHtml, $result);
        $this->assertEquals([CodeHtml], $arrayResult);
    }

    public function testShouldReturnEmptyStringOnAssetReference(): void
    {
        $jsonString = GQLTest::gqlEntry(AssetReferenceJson);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals('', $result);
        $this->assertEquals([''], $arrayResult);
    }

    public function testShouldReturnEmbeddedAssetOnAssetReference(): void
    {
        $jsonString = GQLTest::gqlEntry(AssetReferenceJson, EmbedEdges);
        $jsonObject = json_decode($jsonString);

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals(AssetReferenceHtml, $result);
        $this->assertEquals([AssetReferenceHtml], $arrayResult);
    }

    public function testShouldReturnEmbeddedEntryOnEntryBlockReference(): void
    {
        $jsonString = GQLTest::gqlEntry(EntryReferenceBlockJson, EmbedEdges);
        $jsonObject = json_decode($jsonString);
        $expectedResult = '<div><p>Update this title</p><p>Content type: <span>content_block</span></p></div>';

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals($expectedResult, $result);
        $this->assertEquals([$expectedResult], $arrayResult);
    }

    public function testShouldReturnEmbeddedEntryOnEntryLinkReference(): void
    {
        $jsonString = GQLTest::gqlEntry(EntryReferenceLinkJson, EmbedEdges);
        $jsonObject = json_decode($jsonString);
        $expectedResult = '<a href="Entry with embedded entry">/copy-of-entry-final-02</a>';

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals($expectedResult, $result);
        $this->assertEquals([$expectedResult], $arrayResult);
    }

    public function testShouldReturnEmbeddedEntryOnEntryInlineReference(): void
    {
        $jsonString = GQLTest::gqlEntry(EntryReferenceInlineJson, EmbedEdges);
        $jsonObject = json_decode($jsonString);
        $expectedResult = '<span>updated title</span>';

        $result = GQL::jsonToHtml($jsonObject->single_rte, new Option());
        $arrayResult = GQL::jsonToHtml($jsonObject->multiple_rte, new Option());

        $this->assertEquals($expectedResult, $result);
        $this->assertEquals([$expectedResult], $arrayResult);
    }
}
