<?php

namespace Xcart\Surfing;


use Xcart\Data;

class Referer extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['referer_id'];
        $this->sPrimaryTable = 'referers';
        parent::__construct($aParams);
    }
}