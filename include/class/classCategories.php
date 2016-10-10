<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";

class classCategories extends classCloneData
{
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "categories";
        $this->sPrimaryKeyFiled = "categoryid";

        parent::__construct($iId);

    }

    public function getParentCategory ($iParentCategory) {
        return func_query_first_cell("SELECT categoryid FROM ".self::$sql_tbl[$this->sPrimaryTable]." WHERE parentid=$iParentCategory");
    }

    public function getCategoryPathasArray($iCategoryId) {
        return explode("/", func_query_first_cell("SELECT categoryid_path FROM ".self::$sql_tbl[$this->sPrimaryTable]." WHERE categoryid=$iCategoryId"));
    }

    public function getCategoryPath($iCategoryId) {
        return func_query_first_cell("SELECT categoryid_path FROM ".self::$sql_tbl[$this->sPrimaryTable]." WHERE categoryid=$iCategoryId");
    }

    public function getCategoryInfo($iCategoryId) {
        return func_query_first("SELECT * FROM ".self::$sql_tbl[$this->sPrimaryTable]." WHERE categoryid=$iCategoryId");
    }

    public function getCategoryByIdAndStoreFront($iCategoryId, $iStoreFronId) {
        return func_query_first_cell("SELECT ".$this->sPrimaryKeyFiled." FROM ".self::$sql_tbl[$this->sPrimaryTable]." WHERE ".$this->sPrimaryKeyFiled."=$iCategoryId AND storefrontid = $iStoreFronId");
    }

    public function getCategoryByNameAndStoreFront($sCategoryName, $iStoreFronId) {
        return func_query_first_cell("SELECT categoryid FROM ".self::$sql_tbl[$this->sPrimaryTable]." WHERE category='".addslashes($sCategoryName)."' AND storefrontid = $iStoreFronId");
    }

    public function setCategoryPath($iCategoryId, $sCategoryPath) {
        $sCategoryPath = ltrim($sCategoryPath,"/");
        func_array2update($this->sPrimaryTable, array("categoryid_path" => $sCategoryPath), "categoryid = '$iCategoryId'");
    }

    public function updateCategoryCleanUrl($iCategoryId) {
        $aCategory = $this->getCategoryInfo($iCategoryId);
        $clean_url = func_clean_url_autogenerate('C', $iCategoryId, array('category' => $aCategory['category']));
        db_query("DELETE FROM ". self::$sql_tbl['clean_urls']. " WHERE resource_type='C' AND resource_id=$iCategoryId");
        func_clean_url_add($clean_url, 'C', $iCategoryId);
    }

    public function createNewCategory($aCategory) {
        $inewcategoryid = func_array2insert($this->sPrimaryTable, $aCategory);
        $this->setCategoryPath($inewcategoryid, $aCategory['categoryid_path']."/".$inewcategoryid);
        $this->updateCategoryCleanUrl($inewcategoryid);
        return $inewcategoryid;
    }

    public function cloneCategory ($iCategoryId, $aParams) {
        $aCategory = $this->getCategoryInfo($iCategoryId);
        $iCategoryid = $this->getCategoryByNameAndStoreFront($aCategory['category'], $aParams['d_main_sf']);

        if (empty($iCategoryid)) {
            unset($aCategory[$this->sPrimaryKeyFiled]);
            $aCategory['storefrontid'] = $aParams['d_main_sf'];
            $aCategory['parentid'] = $aParams['parentid'];
            $aCategory['is_bold'] = "N";
            $aCategory['order_by'] = "10";
            $aCategory['categoryid_path'] = $this->getCategoryPath($aParams['parentid']).$iCategoryid;

            array_walk_recursive($aCategory, array(__CLASS__,'recursive_escape'));

            return $this->createNewCategory($aCategory);
        } else { //category exists
            return $iCategoryid;
        }
    }

    public function updateProductsInChildCategories($iParentCategoryId, $sStatus)
    {
        $bResult = false;
        if (!empty($iParentCategoryId)) {
            $sSQL = "UPDATE
             ". self::$sql_tbl['categories']. " c,
                ". self::$sql_tbl['categories']. " c2,
                 ". self::$sql_tbl['products']. " p
                 INNER JOIN ". self::$sql_tbl['products_categories']. " pc
                    ON pc.productid = p.productid
            SET p.pc_classify_status = '$sStatus'
            WHERE p.forsale = 'Y' AND c.categoryid = $iParentCategoryId
            AND c2.categoryid_path LIKE CONCAT(c.categoryid_path, '%') AND pc.categoryid = c2.categoryid";
            $bResult = db_query($sSQL);
        }
        return $bResult;
    }

}