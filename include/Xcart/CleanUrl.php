<?php

namespace Xcart;


class CleanUrl extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['resource_id', 'resource_type'];
        $this->sPrimaryTable = 'clean_urls';
        parent::__construct($aParams);
    }

    public function getUrl()
    {
        return $this->getField('clean_url');
    }
}