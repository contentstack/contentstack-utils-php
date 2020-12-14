<?php

declare(strict_types=1);

namespace Contentstack\Tests\Utils;

class EmbedObjectMock {
    public static function embeddedBlankItems($rte)
    {
        return array(
            'rte' => $rte,
        );
    }
    public static function embeddedModel($rte, $contentTypeUid = 'uid', $assetUid = 'uid')
    {
        return array(
            'rte' => $rte,
            '_embedded_items' => array('rte'=> array(EmbedObjectMock::embeddedContentTypeUidModel($contentTypeUid), EmbedObjectMock::embeddedAssetModel($assetUid))),
        );
    }

    public static function embeddedContentTypeUidModel($uid = 'uid') {
        return array(
            'uid'=> $uid,
            '_content_type_uid'=> 'contentTypeUid'
        );
    }

    public static function embeddedContentTypeRenderString() {
        
    }
    public static function embeddedEntryModel($uid = 'uid'){
        return array(
            'uid'=> $uid,
            'title'=> 'title',
            '_content_type_uid'=> 'contentTypeUid'
        );
    }

    public static function embeddedAssetModel($uid = 'uid') {
        return array(
            'uid'=> $uid,
            'title'=> 'title',
            'file_name'=> 'fileName',
            'url'=> 'URL'
        );
    }
}