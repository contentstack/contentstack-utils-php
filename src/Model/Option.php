<?php

declare(strict_types=1);

namespace Contentstack\Utils\Model;

use Contentstack\Utils\Resource\EntryEmbedable;
use Contentstack\Utils\Resource\RenderableInterface;
use Contentstack\Utils\Resource\EmbeddedObject;
use Contentstack\Utils\Enum\StyleType;
use Contentstack\Utils\Enum\MarkType;
use Contentstack\Utils\Enum\NodeType;

class Option implements RenderableInterface {

    /**
     * @var EntryEmbedable
     */
     
    public $entry;

    public function __construct(array $entry = null)
    {
        $this->entry = $entry;
    }
    
    function renderMark(MarkType $markType, string $text): string
    {
        $resultString = "";

        switch ($markType) {
            case MarkType::get(MarkType::BOLD):
                $resultString = "<strong>".$text."</strong>";
                break;
            case MarkType::get(MarkType::ITALIC):
                $resultString = "<em>".$text."</em>";
                break;
            case MarkType::get(MarkType::UNDERLINE):
                $resultString = "<u>".$text."</u>";
                break;
            case MarkType::get(MarkType::STRIKE_THROUGH):
                $resultString = "<strike>".$text."</strike>";
                break;
            case MarkType::get(MarkType::INLINE_CODE):
                $resultString = "<span>".$text."</span>";
                break;
            case MarkType::get(MarkType::SUBSCRIPT):
                $resultString = "<sub>".$text."</sub>";
                break;
            case MarkType::get(MarkType::SUPERSCRIPT):
                $resultString = "<sup>".$text."</sup>";
                break;
        }
        return $resultString;
    }

    function renderNode(string $nodeType, object $node, string $innerHtml): string 
    {
        $resultString = "";
        $attrs = get_object_vars($node->attrs);
        switch ($nodeType) 
        {
            case NodeType::get(NodeType::PARAGRAPH)->getValue():
                $resultString = "<p>".$innerHtml."</p>";
                break;
            case NodeType::get(NodeType::LINK)->getValue():
                $resultString = "<a href=\"".($attrs["href"] ?? "")."\">".$innerHtml."</a>";
                break;
            case NodeType::get(NodeType::IMAGE)->getValue():
                $resultString = "<img src=\"".($attrs["src"] ?? "")."\" />".$innerHtml;
                break;
            case NodeType::get(NodeType::EMBED)->getValue():
                $resultString = "<iframe src=\"".($attrs["src"] ?? "")."\">".$innerHtml."</iframe>";
                break;
            case NodeType::get(NodeType::HEADING_1)->getValue():
                $resultString = "<h1>".$innerHtml."</h1>";
                break;
            case NodeType::get(NodeType::HEADING_2)->getValue():
                $resultString = "<h2>".$innerHtml."</h2>";
                break;
            case NodeType::get(NodeType::HEADING_3)->getValue():
                $resultString = "<h3>".$innerHtml."</h3>";
                break;
            case NodeType::get(NodeType::HEADING_4)->getValue():
                $resultString = "<h4>".$innerHtml."</h4>";
                break;
            case NodeType::get(NodeType::HEADING_5)->getValue():
                $resultString = "<h5>".$innerHtml."</h5>";
                break;
            case NodeType::get(NodeType::HEADING_6)->getValue():
                $resultString = "<h6>".$innerHtml."</h6>";
                break;
            case NodeType::get(NodeType::ORDER_LIST)->getValue():
                $resultString = "<ol>".$innerHtml."</ol>";
                break;
            case NodeType::get(NodeType::UNORDER_LIST)->getValue():
                $resultString = "<ul>".$innerHtml."</ul>";
                break;
            case NodeType::get(NodeType::LIST_ITEM)->getValue():
                $resultString = "<li>".$innerHtml."</li>";
                break;
            case NodeType::get(NodeType::HR)->getValue():
                $resultString = "<hr>";
                break;
            case NodeType::get(NodeType::TABLE)->getValue():
                $resultString = "<table>".$innerHtml."</table>";
                break;
            case NodeType::get(NodeType::TABLE_HEADER)->getValue():
                $resultString = "<thead>".$innerHtml."</thead>";
                break;
            case NodeType::get(NodeType::TABLE_BODY)->getValue():
                $resultString = "<tbody>".$innerHtml."</tbody>";
                break;
            case NodeType::get(NodeType::TABLE_FOOTER)->getValue():
                $resultString = "<tfoot>".$innerHtml."</tfoot>";
                break;
            case NodeType::get(NodeType::TABLE_ROW)->getValue():
                $resultString = "<tr>".$innerHtml."</tr>";
                break;
            case NodeType::get(NodeType::TABLE_HEAD)->getValue():
                $resultString = "<th>".$innerHtml."</th>";
                break;
            case NodeType::get(NodeType::TABLE_DATA)->getValue():
                $resultString = "<td>".$innerHtml."</td>";
                break;
            case NodeType::get(NodeType::BLOCK_QUOTE)->getValue():
                $resultString = "<blockquote>".$innerHtml."</blockquote>";
                break;
            case NodeType::get(NodeType::CODE)->getValue():
                $resultString = "<code>".$innerHtml."</code>";
                break;
            default:
                $resultString = $innerHtml;
                break;
        }
        return $resultString;
    }

    function renderOptions(array $embeddedObject, Metadata $metadata): string
    {
        $resultString = "";
        switch ($metadata->getStyleType()) {
            case StyleType::get(StyleType::BLOCK): 
                $resultString =  "<div><p>" . ($embeddedObject["title"] ?? $embeddedObject["uid"]) . "</p><p>Content type: <span>". $embeddedObject["_content_type_uid"] ."</span></p></div>";
            break;
            case StyleType::get(StyleType::INLINE): 
                $resultString =  "<span>".($embeddedObject["title"] ?? $embeddedObject["uid"])."</span>";
            break;
            case StyleType::get(StyleType::LINK): 
                $resultString =  "<a href=\"".($metadata->getAttribute("href")->value ?? $embeddedObject["url"] ?? $embeddedObject["title"] ?? $embeddedObject["uid"] )."\">".($metadata->getText() ?? $embeddedObject["title"] ?? $embeddedObject["uid"])."</a>";
            break;
            case StyleType::get(StyleType::DISPLAY): 
                $resultString =  "<img src=\"".($metadata->getAttribute("src")->value ?? $embeddedObject["url"] )."\" alt=\"".($metadata->getAttribute("alt")->value ?? $embeddedObject["title"] ?? $embeddedObject["filename"] ?? $embeddedObject["uid"])."\" />";
            break;
            case StyleType::get(StyleType::DOWNLOAD): 
                $resultString =  "<a href=\"".($metadata->getAttribute("href")->value ?? $embeddedObject["url"])."\">".($metadata->getText() ?? $embeddedObject["filename"]?? $embeddedObject["title"] ?? $embeddedObject["uid"])."</a>";
            break;
        }
        return $resultString;
    }
}