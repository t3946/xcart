<?php

namespace Xcart;


class CleanUrl extends Data
{
    const CLEANURL_TYPE_PRODUCT = 'P';
    const CLEANURL_TYPE_BRAND = 'M';
    const CLEANURL_TYPE_CATEGORY = 'C';
    const CLEANURL_TYPE_STATIC_PAGE = 'S';

    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['resource_id', 'resource_type'];
        $this->sPrimaryTable = 'clean_urls';
        parent::__construct($aParams);
    }

    public function getUrl()
    {
        global $xcart_web_dir;
        $sUrl = null;
        if ($this->getField('clean_url')) {
            $sUrl =  $xcart_web_dir . "/" . $this->getField('clean_url') . "/";
        }
        return $sUrl;
    }
}