<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";

class classBrands extends classCloneData
{
    public function __construct()
    {
        parent::__construct();
        $this->sPrimaryTable = "brands";
        $this->sPrimaryKeyFiled = "brandid";
    }

    public function getBrandByProductId ($iProductid) {
        return func_query_first("SELECT *  FROM ".$this->sql_tbl[$this->sPrimaryTable]  ." INNER JOIN xcart_products xp USING (".$this->sPrimaryKeyFiled.") WHERE productid = $iProductid");
    }
}