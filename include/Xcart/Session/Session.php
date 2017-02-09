<?php

namespace Session;

use Xcart\Data;

class Session extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['sessid'];
        $this->sPrimaryTable = 'sessions_data';
        parent::__construct($aParams);
    }

    public function getSessionId()
    {
        global $XCARTSESSID;
        return $XCARTSESSID;
    }
}