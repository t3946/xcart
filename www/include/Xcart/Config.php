<?php
namespace Xcart;

class Config extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['name'];
        $this->sPrimaryTable = 'config';
        parent::__construct($aParams);
    }

    public function getValue()
    {
        return $this->getField('value');
    }

    public function setValue($sValue)
    {
        $this->setField('value', $sValue);
        return $this;
    }
}