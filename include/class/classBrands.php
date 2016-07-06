<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";

class classBrands extends classCloneData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "brands";
        $this->sPrimaryKeyFiled = "brandid";
        parent::__construct($iId);
    }

    public function getBrandByProductId ($iProductid) {
        return func_query_first("SELECT *  FROM ".self::$sql_tbl[$this->sPrimaryTable]  ." INNER JOIN xcart_products xp USING (".$this->sPrimaryKeyFiled.") WHERE productid = $iProductid");
    }
}