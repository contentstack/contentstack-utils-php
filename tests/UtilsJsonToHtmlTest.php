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
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(Blank, trim(preg_replace('/\s\s+/', '', $result)));
    }

    public function testShouldReturnEmptyStringForBlankDocumentArray(): void
    {
        $jsonObject = json_decode(BlankDocument);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([Blank], $result);
    }

    public function testShouldReturnH1TagOnNonChild(): void
    {
        $jsonObject = json_decode(H1NonChildJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals('<h1></h1>', $result);
    }

    public function testShouldReturnStringForPlainTextDocument(): void
    {   
        $jsonObject = json_decode(PlainTextJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(PlainTextHtml, trim(preg_replace('/\s\s+/', '', $result)));
    }

    public function testShouldReturnStringForPlainTextDocumentArray(): void
    {
        $jsonObject = json_decode(PlainTextJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([PlainTextHtml], $result);
    }

    public function testShouldReturnEmptyStringOnAssetReference(): void
    {
        $jsonObject = json_decode(AssetReferenceJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals('', $result);
    }

    public function testShouldReturnEmptyStringOnArrayAssetReference(): void
    {
        $jsonObject = json_decode(AssetReferenceJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([''], $result);
    }

    public function testShouldReturnEmbeddedAssetOnAssetReference(): void
    {
        $jsonObject = json_decode(AssetReferenceJson);
        $result = Utils::jsonToHtml($jsonObject, new Option(EmbedObjectMock::embeddedModel('', '', 'blt44asset')));
        $this->assertEquals(AssetReferenceHtml, $result);
    }

    public function testShouldReturnEmbeddedAssetOnArrayAssetReference(): void
    {
        $jsonObject = json_decode(AssetReferenceJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option(EmbedObjectMock::embeddedModel('', '', 'blt44asset')));
        $this->assertEquals([AssetReferenceHtml], $result);
    }

    public function testShouldReturnEmbeddedEntryOnEntryBlockReference(): void
    {
        $jsonObject = json_decode(EntryReferenceBlockJson);
        $result = Utils::jsonToHtml($jsonObject, new Option(EmbedObjectMock::embeddedModel('', 'blttitleuid', 'blt44asset')));
        $this->assertEquals(EntryReferenceBlockHtml, $result);
    }

    public function testShouldReturnEmbeddedEntryOnArrayEntryBlockReference(): void
    {
        $jsonObject = json_decode(EntryReferenceBlockJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option(EmbedObjectMock::embeddedModel('', 'blttitleuid', 'blt44asset')));
        $this->assertEquals([EntryReferenceBlockHtml], $result);
    }

    public function testShouldReturnEmbeddedEntryOnEntryLinkReference(): void
    {
        $jsonObject = json_decode(EntryReferenceLinkJson);
        $result = Utils::jsonToHtml($jsonObject, new Option(EmbedObjectMock::embeddedModel('', 'bltemmbedEntryuid', 'blt44asset')));
        $this->assertEquals(EntryReferenceLinkHtml, $result);
    }

    public function testShouldReturnEmbeddedEntryOnArrayEntryLinkReference(): void
    {
        $jsonObject = json_decode(EntryReferenceLinkJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option(EmbedObjectMock::embeddedModel('', 'bltemmbedEntryuid', 'blt44asset')));
        $this->assertEquals([EntryReferenceLinkHtml], $result);
    }

    public function testShouldReturnEmbeddedEntryOnEntryInlineReference(): void
    {
        $jsonObject = json_decode(EntryReferenceInlineJson);
        $result = Utils::jsonToHtml($jsonObject, new Option(EmbedObjectMock::embeddedModel('', 'blttitleUpdateuid', 'blt44asset')));
        $this->assertEquals(EntryReferenceInlineHtml, $result);
    }

    public function testShouldReturnEmbeddedEntryOnArrayEntryInlineReference(): void
    {
        $jsonObject = json_decode(EntryReferenceInlineJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option(EmbedObjectMock::embeddedModel('', 'blttitleUpdateuid', 'blt44asset')));
        $this->assertEquals([EntryReferenceInlineHtml], $result);
    }

    public function testShouldReturnParagraphOnParagraph(): void
    {
        $jsonObject = json_decode(ParagraphJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(ParagraphHtml, $result);
    }

    public function testShouldReturnParagraphOnArrayParagraph(): void
    {
        $jsonObject = json_decode(ParagraphJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([ParagraphHtml], $result);
    }

    public function testShouldReturnLinkTagOnLink(): void
    {
        $jsonObject = json_decode(LinkInPJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(LinkInPHtml, $result);
    }

    public function testShouldReturnLinkTagOnArrayLink(): void
    {
        $jsonObject = json_decode(LinkInPJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([LinkInPHtml], $result);
    }

    public function testShouldReturnImageTagOnImage(): void
    {
        $jsonObject = json_decode(ImgJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(ImgHtml, $result);
    }

    public function testShouldReturnImageTagOnArrayImage(): void
    {
        $jsonObject = json_decode(ImgJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([ImgHtml], $result);
    }

    public function testShouldReturnEmbedTagOnEmbed(): void
    {
        $jsonObject = json_decode(EmbedJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(EmbedHtml, $result);
    }

    public function testShouldReturnEmbedTagOnArrayEmbed(): void
    {
        $jsonObject = json_decode(EmbedJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([EmbedHtml], $result);
    }

    public function testShouldReturnH1TagOnH1(): void
    {
        $jsonObject = json_decode(H1Json);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(H1Html, $result);
    }

    public function testShouldReturnH1TagOnArrayH1(): void
    {
        $jsonObject = json_decode(H1Json);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([H1Html], $result);
    }

    public function testShouldReturnH2TagOnH2(): void
    {
        $jsonObject = json_decode(H2Json);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(H2Html, $result);
    }

    public function testShouldReturnH2TagOnArrayH2(): void
    {
        $jsonObject = json_decode(H2Json);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([H2Html], $result);
    }

    public function testShouldReturnH3TagOnH3(): void
    {
        $jsonObject = json_decode(H3Json);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(H3Html, $result);
    }

    public function testShouldReturnH3TagOnArrayH3(): void
    {
        $jsonObject = json_decode(H3Json);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([H3Html], $result);
    }

    public function testShouldReturnH4TagOnH4(): void
    {
        $jsonObject = json_decode(H4Json);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(H4Html, $result);
    }

    public function testShouldReturnH4TagOnArrayH4(): void
    {
        $jsonObject = json_decode(H4Json);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([H4Html], $result);
    }

    public function testShouldReturnH5TagOnH5(): void
    {
        $jsonObject = json_decode(H5Json);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(H5Html, $result);
    }

    public function testShouldReturnH5TagOnArrayH5(): void
    {
        $jsonObject = json_decode(H5Json);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([H5Html], $result);
    }

    public function testShouldReturnH6TagOnH6(): void
    {
        $jsonObject = json_decode(H6Json);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(H6Html, $result);
    }

    public function testShouldReturnH6TagOnArrayH6(): void
    {
        $jsonObject = json_decode(H6Json);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([H6Html], $result);
    }

    public function testShouldReturnOrderListTagOnOrderList(): void
    {
        $jsonObject = json_decode(OrderListJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(OrderListHtml, $result);
    }

    public function testShouldReturnOrderListTagOnArrayOrderList(): void
    {
        $jsonObject = json_decode(OrderListJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([OrderListHtml], $result);
    }

    public function testShouldReturnUnOrderListTagOnUnOrderList(): void
    {
        $jsonObject = json_decode(UnorderListJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(UnorderListHtml, $result);
    }

    public function testShouldReturnUnOrderListTagOnArrayUnOrderList(): void
    {
        $jsonObject = json_decode(UnorderListJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([UnorderListHtml], $result);
    }

    public function testShouldReturnHrTagOnUnHr(): void
    {
        $jsonObject = json_decode(HRJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals('<hr>', $result);
    }

    public function testShouldReturnHrTagOnArrayHr(): void
    {
        $jsonObject = json_decode(HRJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals(['<hr>'], $result);
    }

    public function testShouldReturnTableContentOnTable(): void
    {
        $jsonObject = json_decode(TableJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(TableHtml, $result);
    }

    public function testShouldReturnTableContentTagOnArrayTable(): void
    {
        $jsonObject = json_decode(TableJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([TableHtml], $result);
    }

    public function testShouldReturnBlockquoteTagOnBlockquote(): void
    {
        $jsonObject = json_decode(BlockquoteJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(BlockquoteHtml, $result);
    }

    public function testShouldReturnBlockquoteTagOnArrayBlockquote(): void
    {
        $jsonObject = json_decode(BlockquoteJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([BlockquoteHtml], $result);
    }

    public function testShouldReturnCodeTagOnUnCode(): void
    {
        $jsonObject = json_decode(CodeJson);
        $result = Utils::jsonToHtml($jsonObject, new Option());
        $this->assertEquals(CodeHtml, $result);
    }

    public function testShouldReturnCodeTagOnArrayCode(): void
    {
        $jsonObject = json_decode(CodeJson);
        $result = Utils::jsonArrayToHtml([$jsonObject], new Option());
        $this->assertEquals([CodeHtml], $result);
    }}