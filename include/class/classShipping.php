<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";

class classShipping extends classCloneData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "shipping";
        $this->sPrimaryKeyFiled = "shippingid";
        parent::__construct($iId);
    }

    public function getName() {

        return $this->getField('shipping');
    }

}