<?php
namespace Xcart;

class Reconciliation extends Data
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

    public function getDescription() {
        return $this->getField('description_csv');
    }

    public function findReconciliationOrder() {
        
    }
}