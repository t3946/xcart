<?php

global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classReconciliation extends classData
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'reconciliations';
        parent::__construct($aParams);
    }

    public function getAction() {
        return $this->getField('action');
    }
}