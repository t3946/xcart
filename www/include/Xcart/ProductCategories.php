<?php

namespace Xcart;


class ProductCategories extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['categoryid','productid','main'];
        $this->sPrimaryTable = 'products_categories';
        parent::__construct($aParams);
    }

    public function isMain()
    {
        return ($this->getField('main') == 'Y');
    }
}