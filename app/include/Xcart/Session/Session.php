<?php

namespace Session;

use Xcart\Data;

/**
 * @deprecated deprecated class
 */
class Session extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['sessid'];
        $this->sPrimaryTable = 'sessions_data';
        parent::__construct($aParams);
    }
}