<?php
namespace Xcart;

class Category extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['categoryid'];
        $this->sPrimaryTable = 'categories';
        parent::__construct($aParams);
    }

    public function getPath()
    {
        return $this->getField('categoryid_path');
    }
}