<?php

declare(strict_types=1);

namespace Contentstack\Utils\Model;

use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Enum\StyleType;

class Metadata {

    public function __construct(\DOMElement $element)
    {
        $this->itemType = !empty($element->getAttribute('type'))? EmbedItemType::byValue($element->getAttribute('type')) : EmbedItemType::get(EmbedItemType::ENTRY); 
        $this->styleType = !empty($element->getAttribute('sys-style-type')) ? StyleType::byValue($element->getAttribute('sys-style-type')) : StyleType::get(StyleType::BLOCK); 
        $this->itemUid = !empty($element->getAttribute('data-sys-entry-uid')) ? $element->getAttribute('data-sys-entry-uid') : $element->getAttribute('data-sys-asset-uid');
        $this->contentTypeUid = $element->getAttribute('data-sys-content-type-uid'); 
        $this->text = $element->textContent;
        $this->attributes = $element->attributes;
        $this->element = $element;
    }

    /* Properties */
    /**
     * @var EmbedItemType
     */
    protected $itemType;

    /**
     * @var StyleType
     */
    protected $styleType;

     /**
     * @var string
     */
    protected $itemUid;

    /**
     * @var string
     */
    protected $contentTypeUid;
    
    /**
     * @var string
     */
    protected $text;

    /**
     * @var DOMNamedNodeMap
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $element;

    /* Methods */
    public function getItemType(): EmbedItemType
    {
        return $this->itemType;
    }

    public function getStyleType(): StyleType
    {
        return $this->styleType;
    }

    public function getItemUid(): string
    {
        return $this->itemUid;
    }

    public function getContentTypeUid(): string
    {
        return $this->contentTypeUid;
    }

    public function getAttributes(): \DOMNamedNodeMap
    {
        return $this->attributes;
    }

    public function getAttribute(string $name): string {
        return $this->element->getAttribute($name);
    }

    public function getOuterHTML(): string
    {
        $doc = new \DOMDocument();
        $doc->appendChild($doc->importNode($this->element, true));
        return $doc->saveHTML();
    }

    public function getText(): string
    {
        return $this->text;
    }
}