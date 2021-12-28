<?php
namespace Xcart;

/**
 * @deprecated deprecated class
 */
class Countries extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['code'];
        $this->sPrimaryTable = 'countries';
        parent::__construct($aParams);
    }
}