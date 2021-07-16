<?php

declare(strict_types=1);

namespace Contentstack\Utils\Model;

use Contentstack\Utils\Enum\EmbedItemType;
use Contentstack\Utils\Enum\StyleType;

class Metadata {

    public function __construct(object $element)
    {
        if ($element instanceof \DOMElement) {
            $this->itemType = !empty($element->getAttribute('type'))? EmbedItemType::byValue($element->getAttribute('type')) : EmbedItemType::get(EmbedItemType::ENTRY); 
            $this->styleType = !empty($element->getAttribute('sys-style-type')) ? StyleType::byValue($element->getAttribute('sys-style-type')) : StyleType::get(StyleType::BLOCK); 
            $this->itemUid = !empty($element->getAttribute('data-sys-entry-uid')) ? $element->getAttribute('data-sys-entry-uid') : $element->getAttribute('data-sys-asset-uid');
            $this->contentTypeUid = $element->getAttribute('data-sys-content-type-uid'); 
            $this->text = $element->textContent;
            $this->attributes = $element->attributes;
            $this->element = $element;
        } else {
            $this->attributes = get_object_vars($element->attrs);
            $this->itemType = !empty($this->attributes->type)? EmbedItemType::byValue($this->attributes->type) : EmbedItemType::get(EmbedItemType::ENTRY); 
            $this->styleType = !empty($this->attributes['display-type']) ? StyleType::byValue($this->attributes['display-type']) : StyleType::get(StyleType::BLOCK); 
            $this->itemUid = !empty($this->attributes['entry-uid']) ? $this->attributes['entry-uid'] : $this->attributes['asset-uid'];
            $this->contentTypeUid = $this->attributes['content-type-uid']; 
            $this->text = ($element->children && count($element->children) > 0) ? $element->children[0]->text : '';
            $this->element = $element;
        }
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

    public function getAttributes(): object
    {
        return $this->attributes;
    }

    public function getAttribute(string $name): ?string {
        if ($this->element instanceof \DOMElement) {
            return $this->element->getAttribute($name);
        } else if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function getOuterHTML(): string
    {
        $doc = $this->element->ownerDocument;
        return $doc->saveHTML($this->element);
    }

    public function getText(): string
    {
        return $this->text;
    }
}