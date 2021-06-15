<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

require_once __DIR__ . '/Helpers/Utility.php';
require_once __DIR__ . '/Mock/EmbedObjectMock.php';

use Contentstack\Utils\Utils;
use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Enum\StyleType;
use Contentstack\Utils\Enum\MarkType;
use Contentstack\Utils\Enum\NodeType;
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
        $this->assertEquals('<div><p>uid</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE));
        $this->assertEquals('<span>uid</span>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK));
        $this->assertEquals('<a href="uid"></a>', $resultString);
    }

    public function testEmbeddedEntry(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK));
        $this->assertEquals('<div><p>title</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE));
        $this->assertEquals('<span>title</span>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK));
        $this->assertEquals('<a href="title"></a>', $resultString);
    }

    public function testEmbeddedAsset(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DISPLAY));
        $this->assertEquals('<img src="URL" alt="title" />', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DOWNLOAD));
        $this->assertEquals('<a href="URL"></a>', $resultString);
    }
    public function testEmbeddedContentTypeEntryWithText(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK, DefaultOptionTest::$text));
        $this->assertEquals('<div><p>uid</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE, DefaultOptionTest::$text));
        $this->assertEquals('<span>uid</span>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedContentType, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK, DefaultOptionTest::$text));
        $this->assertEquals('<a href="uid">Text To set Link</a>', $resultString);
    }

    public function testEmbeddedEntryWithText(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::BLOCK, DefaultOptionTest::$text));
        $this->assertEquals('<div><p>title</p><p>Content type: <span>contentTypeUid</span></p></div>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::INLINE, DefaultOptionTest::$text));
        $this->assertEquals('<span>title</span>', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedEntry, $this->getMetadata(EmbedItemType::ENTRY, StyleType::LINK, DefaultOptionTest::$text));
        $this->assertEquals('<a href="title">Text To set Link</a>', $resultString);
    }

    public function testEmbeddedAssetWithText(): void
    {
        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DISPLAY, DefaultOptionTest::$text));
        $this->assertEquals('<img src="URL" alt="title" />', $resultString);

        $resultString = DefaultOptionTest::$defaultRender->renderOptions(DefaultOptionTest::$embeddedAsset, $this->getMetadata(EmbedItemType::ASSET, StyleType::DOWNLOAD, DefaultOptionTest::$text));
        $this->assertEquals('<a href="URL">Text To set Link</a>', $resultString);
    }

    public function testShouldReturnMarkTypeHtmlContent(): void 
    {        
        $boldString = DefaultOptionTest::$defaultRender->renderMark(MarkType::get(MarkType::BOLD), DefaultOptionTest::$text);
        $this->assertEquals("<strong>".DefaultOptionTest::$text."</strong>", $boldString);
        $italicString = DefaultOptionTest::$defaultRender->renderMark(MarkType::get(MarkType::ITALIC), DefaultOptionTest::$text);
        $this->assertEquals("<em>".DefaultOptionTest::$text."</em>", $italicString);
        $underlineString = DefaultOptionTest::$defaultRender->renderMark(MarkType::get(MarkType::UNDERLINE), DefaultOptionTest::$text);
        $this->assertEquals("<u>".DefaultOptionTest::$text."</u>", $underlineString);
        $strickthroughString = DefaultOptionTest::$defaultRender->renderMark(MarkType::get(MarkType::STRIKE_THROUGH), DefaultOptionTest::$text);
        $this->assertEquals("<strike>".DefaultOptionTest::$text."</strike>", $strickthroughString);
        $inlineCodeString = DefaultOptionTest::$defaultRender->renderMark(MarkType::get(MarkType::INLINE_CODE), DefaultOptionTest::$text);
        $this->assertEquals("<span>".DefaultOptionTest::$text."</span>", $inlineCodeString);
        $subscriptString = DefaultOptionTest::$defaultRender->renderMark(MarkType::get(MarkType::SUBSCRIPT), DefaultOptionTest::$text);
        $this->assertEquals("<sub>".DefaultOptionTest::$text."</sub>", $subscriptString);
        $superscriptString = DefaultOptionTest::$defaultRender->renderMark(MarkType::get(MarkType::SUPERSCRIPT), DefaultOptionTest::$text);
        $this->assertEquals("<sup>".DefaultOptionTest::$text."</sup>", $superscriptString);
    }
    
    function testShouldReturnParagraphHtmlForParagraphnode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::PARAGRAPH), $node, DefaultOptionTest::$text);

        $this->assertEquals("<p>".DefaultOptionTest::$text."</p>", $resultString);
    }

    function testShouldReturnLinkHtmlForLinkNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::LINK), $node, DefaultOptionTest::$text);

        $this->assertEquals("<a href=\"\">".DefaultOptionTest::$text."</a>", $resultString);
    }

    function testShouldReturnImageHtmlForImageNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::IMAGE), $node, DefaultOptionTest::$text);

        $this->assertEquals("<img src=\"\" />".DefaultOptionTest::$text, $resultString);
    }

    function testShouldReturnEmbedHtmlForEmbedNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::EMBED), $node, DefaultOptionTest::$text);

        $this->assertEquals("<iframe src=\"\">".DefaultOptionTest::$text."</iframe>", $resultString);
    }

    function testShouldReturnH1HtmlForH1Node(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::HEADING_1), $node, DefaultOptionTest::$text);

        $this->assertEquals("<h1>".DefaultOptionTest::$text."</h1>", $resultString);
    }

    function testShouldReturnH2HtmlForH2Node(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::HEADING_2), $node, DefaultOptionTest::$text);

        $this->assertEquals("<h2>".DefaultOptionTest::$text."</h2>", $resultString);
    }

    function testShouldReturnH3HtmlForH3Node(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::HEADING_3), $node, DefaultOptionTest::$text);

        $this->assertEquals("<h3>".DefaultOptionTest::$text."</h3>", $resultString);
    }

    function testShouldReturnH4HtmlForH4Node(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::HEADING_4), $node, DefaultOptionTest::$text);

        $this->assertEquals("<h4>".DefaultOptionTest::$text."</h4>", $resultString);
    }

    function testShouldReturnH5HtmlForH5Node(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::HEADING_5), $node, DefaultOptionTest::$text);

        $this->assertEquals("<h5>".DefaultOptionTest::$text."</h5>", $resultString);
    }

    function testShouldReturnH6HtmlForH6Node(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::HEADING_6), $node, DefaultOptionTest::$text);

        $this->assertEquals("<h6>".DefaultOptionTest::$text."</h6>", $resultString);
    }

    function testShouldReturnHRHtmlForHRNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::HR), $node, DefaultOptionTest::$text);

        $this->assertEquals("<hr>", $resultString);
    }

    function testShouldReturnTableHtmlForTableNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TABLE), $node, DefaultOptionTest::$text);

        $this->assertEquals("<table>".DefaultOptionTest::$text."</table>", $resultString);
    }

    function testShouldReturnTableHeaderHtmlForTableHeaderNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TABLE_HEADER), $node, DefaultOptionTest::$text);

        $this->assertEquals("<thead>".DefaultOptionTest::$text."</thead>", $resultString);
    }

    function testShouldReturnTableBodyHtmlForTableBodyNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TABLE_BODY), $node, DefaultOptionTest::$text);

        $this->assertEquals("<tbody>".DefaultOptionTest::$text."</tbody>", $resultString);
    }

    function testShouldReturnTableFooterHtmlForTableFooterNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TABLE_FOOTER), $node, DefaultOptionTest::$text);

        $this->assertEquals("<tfoot>".DefaultOptionTest::$text."</tfoot>", $resultString);
    }

    function testShouldReturnTableRowHtmlForTableRowNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TABLE_ROW), $node, DefaultOptionTest::$text);

        $this->assertEquals("<tr>".DefaultOptionTest::$text."</tr>", $resultString);
    }

    function testShouldReturnTableHeadHtmlForTableHeadNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TABLE_HEAD), $node, DefaultOptionTest::$text);

        $this->assertEquals("<th>".DefaultOptionTest::$text."</th>", $resultString);
    }

    function testShouldReturnTableDataHtmlForTableDataNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TABLE_DATA), $node, DefaultOptionTest::$text);

        $this->assertEquals("<td>".DefaultOptionTest::$text."</td>", $resultString);
    }

    function testShouldReturnblockquoteHtmlForblockquoteNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::BLOCK_QUOTE), $node, DefaultOptionTest::$text);

        $this->assertEquals("<blockquote>".DefaultOptionTest::$text."</blockquote>", $resultString);
    }

    function testShouldReturnCodeHtmlForCodeNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::CODE), $node, DefaultOptionTest::$text);

        $this->assertEquals("<code>".DefaultOptionTest::$text."</code>", $resultString);
    }

    function testShouldReturnReferenceHtmlForReferenceNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::REFERENCE), $node, DefaultOptionTest::$text);

        $this->assertEquals(DefaultOptionTest::$text, $resultString);
    }

    function testShouldTextReferenceHtmlForTextNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::TEXT), $node, DefaultOptionTest::$text);

        $this->assertEquals(DefaultOptionTest::$text, $resultString);
    }

    function testShouldTextDocumentHtmlForDocumentNode(): void 
    {
        $node = json_decode(BlankDocument);

        $resultString = DefaultOptionTest::$defaultRender->renderNode(NodeType::get(NodeType::DOCUMENT), $node, DefaultOptionTest::$text);

        $this->assertEquals(DefaultOptionTest::$text, $resultString);
    }
}