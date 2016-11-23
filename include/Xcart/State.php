<?php

namespace Xcart;


class State extends Data
{
    public function __construct($aOrderData = null)
    {
        $this->aPrimaryKeys = ['stateid'];
        $this->sPrimaryTable = 'states';

        parent::__construct($aOrderData);
    }

    public function getStateId()
    {
        return $this->getField('stateid');
    }
}