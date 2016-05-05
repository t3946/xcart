<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";
require_once $xcart_dir."/include/class/classManufacturers.php";
require_once $xcart_dir."/include/class/classCategories.php";

class classProducts extends classCloneData
{
    private $aProductToQueue;
    public $addCounter;
    public $updateCounter;
    private $sQueueTable;

    public function __construct()
    {   parent::__construct();
        $this->init();
    }
    public function init()
    {

        $this->sPrimaryTable = "products";
        $this->sPrimaryKeyFiled = "productid";
        $this->sQueueTable = "clone_products_queue";
        $this->addCounter = 0;
        $this->updateCounter = 0;

        $this->arrCloneTableStructure[] = array("table" => $this->sPrimaryTable,"key_field" => $this->sPrimaryKeyFiled, "primary_key" => $this->sPrimaryKeyFiled);
        $this->arrCloneTableStructure[] = array("table" => "images_D","key_field" => "id", "primary_key" =>"imageid");
        $this->arrCloneTableStructure[] = array("table" => "images_P","key_field" => "id", "primary_key" =>"imageid");
        $this->arrCloneTableStructure[] = array("table" => "images_T","key_field" => "id", "primary_key" =>"imageid");
        $this->arrCloneTableStructure[] = array("table" => "product_files","key_field" => "productid", "primary_key" =>"fileid");
        $this->arrCloneTableStructure[] = array("table" => "product_taxes","key_field" => "productid", "primary_key" =>"productid");
        $this->arrCloneTableStructure[] = array("table" => "products_amz_fields","key_field" => "productid", "primary_key" =>"productid");
        $this->arrCloneTableStructure[] = array("table" => "clean_urls","key_field" => "resource_id", "primary_key" =>"resource_id");
    }

    /**
     * https://s3stores.teamwork.com/tasks/6416520
     *
     * @return bool
     */
    public function cloneProductFunction() {

        $bResult = false;
        $this->getNextProductFromQueue();


        if (empty($this->aProductToQueue)) {
            $this->message[] = "No products in queue";
            return $bResult;
        }

        $aProduct = $this->getProductInfo($this->aProductToQueue[$this->sPrimaryKeyFiled]);

        switch ($this->aProductToQueue["clone"]) {
            case "Y":
                $bResult = $this->cloneProduct($aProduct); //(клонирование продукта)
                break;
            case "N":
                $bResult = $this->updateProduct($aProduct); //ИНАЧЕ (обновление продукта)
                break;
        }

        /*if (!$this->deleteFromQueue()) {
            $this->message[] = "Error delete from Queue table";
            $bResult = false;
        }*/

        return $bResult;

    }

    public function getProductInfo($iProductId) {
        return func_query_first("SELECT * FROM ".$this->sql_tbl[$this->sPrimaryTable]." WHERE productid = $iProductId");
    }

    public function getMainProductCategoriesInfo($iProductId) {
        return func_query("SELECT c.* FROM ".$this->sql_tbl['products_categories']." pc
                                             INNER JOIN ".$this->sql_tbl['categories']." c ON pc.categoryid = c.categoryid WHERE pc.main='Y' AND pc.productid = $iProductId");
    }

    public function getProductVariants($iProductId) {
        return func_query("SELECT * FROM ".$this->sql_tbl['variants']." WHERE productid = $iProductId");
    }

    protected function getNextProductFromQueue () {

        $this->aProductToQueue = func_query_first("SELECT * FROM ".$this->sql_tbl[$this->sQueueTable]." LIMIT 1");
    }

    protected function deleteFromQueue($iManufacturerId) {
        return func_query("DELETE FROM $this->sql_tbl[$this->sQueueTable] WHERE $this->sPrimaryKeyFiled = $this->aProductToQueue[productid] AND manufacturerid = $iManufacturerId");
    }

    protected function getProductMPN($sSKU, $sPrefixManufacturer) {
        if (strpos($sSKU, $sPrefixManufacturer) == 0)
            return preg_replace("/^($sPrefixManufacturer-)/i","", $sSKU);
        else return false;
    }

    protected function getClonedSKU ($originSKU, $sProductMPN) {
        if (!$sProductMPN) return false;

        return $originSKU."-".$sProductMPN;
    }

    protected function IncSuccessAdd() {
        $this->addCounter++;
    }

    protected function IncSuccessUpdate() {
        $this->updateCounter++;
    }

    public function getFilterInfo($iFilterId) {
        return func_query_first("SELECT * FROM ".$this->sql_tbl['cidev_filters']." WHERE f_id=$iFilterId");
    }

    public function getFilterByNameAndStoreFront ($sFilterName, $iStoreFrontId) {
        return func_query_first_cell("SELECT f_id FROM ".$this->sql_tbl['cidev_filters']." WHERE f_name='$sFilterName' AND storefrontid=$iStoreFrontId");
    }

    public function createNewFilter($aFilter) {
        $iFilterId = func_array2insert('cidev_filters', $aFilter);
        return $iFilterId;
    }

    public function getProductFilters ($iProductId){
        return func_query("SELECT * FROM ".$this->sql_tbl['cidev_filter_products']." WHERE productid = $iProductId");
    }

    public function cloneFilter($oFilter,$aParamToClone) {
        $aFilter = $this->getFilterInfo($oFilter['fp_id']);
        $iFilterId = $this->getFilterByNameAndStoreFront($aFilter['f_name'], $aParamToClone['d_main_sf']);
        if (empty($iFilterId)) {
            unset($aFilter['f_id']);
            $aFilter['storefrontid'] = $aParamToClone['d_main_sf'];
            return $this->createNewFilter($aFilter);

        } else return $iFilterId;
    }

    public function getFilterValues ($iFilterId) {
        return func_query("SELECT * FROM ".$this->sql_tbl['cidev_filter_values']." WHERE f_id=$iFilterId");
    }

    public function cloneFilterValues ($oFilter, $iNewFilterId) {
        $aNewFilterValuesId = array();
        $aFilterValues = $this->getFilterValues($oFilter['fv_id']);
        if (isset($aFilterValues) && is_array($aFilterValues) && !empty($aFilterValues)) {
            foreach($aFilterValues as $oFilter) {
                $oFilter['f_id'] = $iNewFilterId;
                unset($oFilter['fv_id']);
                $aNewFilterValuesId[] = func_array2insert('cidev_filter_values', $oFilter);
            }
        }
        return($aNewFilterValuesId);
    }

    public function addProductToStoreFront ($iProductId, $iStoreFrontId) {
        $aProductStoreFront = array('productid' => $iProductId, 'sfid' => $iStoreFrontId);
        func_array2insert ('products_sf', $aProductStoreFront);
    }

    public function addMainProductCategory ($iProductId, $iCategoryId) {
        $aProductCategory = array('productid' => $iProductId, 'categoryid' => $iCategoryId, 'main' => 'Y', 'orderby' => 10);
        func_array2insert ('products_categories', $aProductCategory);
    }

    public function setProductForSale($iProductId, $sStatus = 'Y') {
        func_array2update($this->sPrimaryTable, array('forsale' => $sStatus), 'productid = '.$iProductId);
    }

    private function addFilterValueToProduct($iFilterId, $iFilterValue, $iNewProductId){
        $aFilterProduct = array('fp_id' => $iFilterId, 'fv_id' => $iFilterValue, 'productid' => $iNewProductId);
        func_array2insert ('cidev_filter_products', $aFilterProduct);
    }

    private function BackprocessLogs($sLogMessage) {
        $this->message[] = $sLogMessage;
        func_backprocess_log("clone_product", $sLogMessage. "; Productid = $this->aProductToQueue[$this->sPrimaryKeyFiled]");
    }



    protected function cloneProduct($aProduct, $aParamToClone = array()) {



        /*ЕСЛИ [PRODUCT] не существует ИЛИ [PRODUCT].forsale !="Y" ИЛИ trim([PRODUCT].clone_parent_productid) >0 или дистрибьютор от [xcart_clone_products_queue].manufacturerid не имеет родителя, ТО
			залоггировать в BackprocessLogs текст 'trying clone cloned, disabled or non-existing product, or target manufacturer is not a clone. skip...'*/

        if (empty($aProduct) || $aProduct["forsale"] != "Y" || $aProduct["clone_parent_product_id"] > 0 || $this->aProductToQueue["parent_manufacturer_id"] == -1) {
            $this->BackprocessLogs("trying clone cloned, disabled or non-existing product, or target manufacturer is not a clone. skip...");
            return false;
        }


        /* skip если есть записи в xcart_variants*/

        $aVariants = $this->getProductVariants($aProduct['productid']);
        if (!empty($aVariants)) {
            $this->BackprocessLogs("trying clone product with variants. skip...");
            return false;
        }


        /*ИНАЧЕ
		получить все подчиненные дистрибьюторы дистрибьютора продукта [PRODUCT] --> [Distributors] (получить code дистрибьютора , отобрать всех дистрибьюторов у которых parent_manufacturer_id = manufacturerid)
		*/

        $classManufacturer = new classManufacturer();

        $aManufacturer = $classManufacturer->getMainufacturersInfo(array($aProduct["manufacturerid"]));
        $aManufacturer = reset($aManufacturer);

        $aChildManufacturers = $classManufacturer->getChildrenManufacturers($aManufacturer["manufacturerid"]);

        //если [xcart_clone_products_queue].manufacturerid >=0, то убрать из списка дистрибьюторов всех кроме дистрибьютора с данным manufacturerid
        if ($this->aProductToQueue["manufacturerid"] >= 0) {
            $aChildManufacturers = $this->search_array_key_value($aChildManufacturers,"manufacturerid", $this->aProductToQueue["manufacturerid"]);
        }


        /*  ЦИКЛ по [Distributors]
                сформировать clonedSKU предполагаемого клона по очередному дистрибьютору: [Distributors].code-[PRODUCT].mpn
                если clonedSKU существует в БД, то
                    залоггировать в BackprocessLogs текст '[clonedSKU] already exist. Added to update queue...'
                    вставить [clonedSKU].productid в очередь с параметром clone = 'N'
                иначе
                    вызвать блок вставки нового продукта для очередного подчиненного дистрибьютора;
                    посчитать успешное добавление
                конец если
            КОНЕЦ ЦИКЛ по [Distributors]
        */
        if (empty($aChildManufacturers)) {
            $this->message[] = "No Manufacturers found to Clone";
            return false;
        }

        foreach ($aChildManufacturers as $aChildManufacturer) {
            //сформировать clonedSKU предполагаемого клона по очередному дистрибьютору: [Distributors].code-[PRODUCT].mpn
            $sClonedSKU = $this->getClonedSKU($aChildManufacturer["code"], $this->getProductMPN($aProduct["productcode"],$aManufacturer["code"]));
            if (!empty($sClonedSKU)) {
                /*если clonedSKU существует в БД, то
                  залоггировать в BackprocessLogs текст '[clonedSKU] already exist. Added to update queue...'
                  вставить [clonedSKU].productid в очередь с параметром clone = 'N'*/
                $aProductBySKU = $this->getProductBySKU($sClonedSKU);
                if (!empty($sProductBySKU)) {
                    $this->BackprocessLogs("SKU $sClonedSKU already exist. Added to update queue...");
                    $this->queueNewProductForUpdate($aProductBySKU);
                } else {
                    /* иначе
                        вызвать блок вставки нового продукта для очередного подчиненного дистрибьютора;
                    */
                    $aParamToClone = array(
                        "productcode" => $sClonedSKU,
                        "manufacturercode" => $aChildManufacturer["code"],
                        "root_category_id" => $aChildManufacturer["root_categoryid_for_cloned_products"],
                        "d_main_sf" =>  $aChildManufacturer["d_main_sf"],
                        "source_sfid" =>  $aChildManufacturer["d_main_sf"],
                        "productid" =>  $aProduct['productid'],
                        "similar_productids" => "",
                        "forsale" => "N",
                        "pc_classify_status" => "NC",
                        "pc_mc_operator" => "",
                        "pc_acc_operator" => "",
                        "amazon_enabled" => "N",
                        "pc_most_relevant_categories" => "",
                        "pc_delta" => "0",
                        "amazon_fba" => "N",
                        "amazon_fba_avail" => 0,
                        "prevent_search_indexing_this_product_page" => 'N',
                        "controlled_by_feed" => '',
                        "add_date" => time(),
                        "mod_date" => time(),
                    );

                    $this->primaryKeyValue = $this->insertClonedProduct($aProduct, $aParamToClone);

                    if (!$this->primaryKeyValue) {
                        $this->message[] = "Error Clone Product"; return false;
                    }

                    /*POST UPDATE VALUES:
                    (set this values after all tables updated)
                    forsale = 'Y'*/

                    $this->setProductForSale($this->primaryKeyValue);

                    /*посчитать успешное добавление*/

                    $this->IncSuccessAdd();
                }
            }
            else { $this->message[] = "Error calculate ClonedSKU"; return false;}
        }
        return true;
    }

    protected function insertClonedProduct ($aProduct, $aParamToClone) {
        /*блок вставки нового продукта
        реквизиты
        clonedSKU - SKU нового продукта
        targetDistributor - дистрибьютор нового продукта
        получить root_category_id = [targetDistributor].root_categoryid_for_cloned_products
        targetSFID = [targetDistributor].d_main_s*/


        /*ВАЖНО:
            для условия клонирования продукта:
            если root_category_id = 0, то иерархию категорий продукта нужно вставить в корень категорий
            если root_category_id > 0, то проверить есть ли такая категория и принадлежит ли она магазину назначения [xcart_categories].storefrontid (если одно из условий не выполняется выдать ошибку в backprocesslogs 'Cloning of product has issues with root category id...')
            иерархию категорий копируемого продукта копировать в подчинение этой категории
            P.S. при копировании иерархии категорий сначала проверять (по полю [xcart_categories].category) есть ли такая категория в подчинении
            если нет до добавлять, если есть то использовать ее

            Иерархию категорий продукта берем только с главной категории продукта [xcart_products_categories].main = 'Y'*/


        $classCategory = new classCategories();

        $aProductCategories = $this->getMainProductCategoriesInfo($aProduct["productid"]);

        $clonedCategoryId = "";

        foreach($aProductCategories as $aProductCategory) {
            $aProductCategoryPath = $classCategory->getCategoryPathasArray($aProductCategory["categoryid"]);
            $aParamToClone["parentid"] = $aParamToClone["root_category_id"];
            if (!empty($aProductCategoryPath) && is_array($aProductCategoryPath)){
                foreach ($aProductCategoryPath as $iCategoryPathId){
                    $clonedCategoryId = $classCategory->cloneCategory($iCategoryPathId, $aParamToClone);
                    $aParamToClone["parentid"] = $clonedCategoryId;
                }
            }
        }

        $iNewProductCategory = $clonedCategoryId;


        /*данные копируем из следующих таблиц:
            xcart_products

            xcart_images_D
            xcart_images_P
            xcart_images_T
            xcart_product_files
            xcart_products_amz_fields
            xcart_product_taxes
            xcart_variants
            xcart_variant_items
            xcart_clean_urls*/


        $this->primaryKeyValue = $this->DublicatePrimaryTable($aParamToClone);
        $aParamToClone[$this->sPrimaryKeyFiled] = $this->primaryKeyValue;


        $this->DublicateNonPrimaryTable($aParamToClone);

        $this->addMainProductCategory($this->primaryKeyValue, $iNewProductCategory);

        $this->addProductToStoreFront($this->primaryKeyValue, $aParamToClone["d_main_sf"]);

        /*добавляем данные в следующие таблицы:
        xcart_categories
        xcart_products_categories

        xcart_cidev_filters
        xcart_cidev_filter_products
        xcart_cidev_filter_values

        xcart_products_sf*/


        $aProductFilters = $this->getProductFilters($aProduct["productid"]);
        if (isset($aProductFilters) && is_array($aProductFilters) && !empty($aProductFilters)) {
            foreach ($aProductFilters as $oFilter) {
                $iFilterId = $this->cloneFilter($oFilter,$aParamToClone);
                $aNewFilterValues = $this->cloneFilterValues($oFilter,$iFilterId);
                if (isset($aNewFilterValues) && is_array($aNewFilterValues) && !empty($aNewFilterValues)) {
                    foreach($aNewFilterValues as $iFilterValue) {
                        $this->addFilterValueToProduct($iFilterId, $iFilterValue, $this->primaryKeyValue);
                    }
                }
            }
        }


        return $this->primaryKeyValue;
    }

    protected function updateClonedProduct ($aProduct) {
        /*при обновлении перезаписываем полностью (удаляем и вставляем из оригинала)
            xcart_images_D
            xcart_images_P
            xcart_images_T
            xcart_product_files
            xcart_products_amz_fields
            xcart_product_taxes*/

        $this->DublicateNonPrimaryTable(array(), true);

        /*xcart_products
        обновляем только поля:
        `product` VARCHAR(255) NOT NULL DEFAULT '',
        `distribution` VARCHAR(255) NOT NULL DEFAULT '',
        `weight` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
        `list_price` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
        `avail` MEDIUMINT(9) NOT NULL DEFAULT '0',
        `forsale` CHAR(1) NOT NULL DEFAULT 'Y',
        `mod_date` INT(11) NOT NULL DEFAULT '0',
        `shipping_freight` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
        `min_amount` MEDIUMINT(9) NOT NULL DEFAULT '1',
        `dim_x` FLOAT NOT NULL DEFAULT '0',
        `dim_y` FLOAT NOT NULL DEFAULT '0',
        `dim_z` FLOAT NOT NULL DEFAULT '0',
        `free_tax` CHAR(1) NOT NULL DEFAULT 'N',
        `return_time` INT(11) NOT NULL DEFAULT '0',
        `upc` VARCHAR(14) NOT NULL DEFAULT '',
        `cost_to_us` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
        `map_price` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
        `mult_order_quantity` CHAR(1) NOT NULL DEFAULT 'N',
        `new_map_price` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
        `eta_date_mm_dd_yyyy` VARCHAR(32) NULL DEFAULT NULL,
        `lead_time_message` VARCHAR(255) NOT NULL DEFAULT '',
        `r_avail` MEDIUMINT(9) NOT NULL DEFAULT '0',*/

        $aUpdateProduct = array();
        $aUpdateProduct['product'] = $aProduct['product'];
        $aUpdateProduct['distribution'] = $aProduct['distribution'];
        $aUpdateProduct['weight'] = $aProduct['weight'];
        $aUpdateProduct['list_price'] = $aProduct['list_price'];
        $aUpdateProduct['avail'] = $aProduct['avail'];
        $aUpdateProduct['forsale'] = $aProduct['forsale'];
        $aUpdateProduct['mod_date'] = $aProduct['mod_date'];
        $aUpdateProduct['shipping_freight'] = $aProduct['shipping_freight'];
        $aUpdateProduct['min_amount'] = $aProduct['min_amount'];
        $aUpdateProduct['dim_x'] = $aProduct['dim_x'];
        $aUpdateProduct['dim_y'] = $aProduct['dim_y'];
        $aUpdateProduct['dim_z'] = $aProduct['dim_z'];
        $aUpdateProduct['free_tax'] = $aProduct['free_tax'];
        $aUpdateProduct['return_time'] = $aProduct['return_time'];
        $aUpdateProduct['upc'] = $aProduct['upc'];
        $aUpdateProduct['cost_to_us'] = $aProduct['cost_to_us'];
        $aUpdateProduct['map_price'] = $aProduct['map_price'];
        $aUpdateProduct['mult_order_quantity'] = $aProduct['mult_order_quantity'];
        $aUpdateProduct['new_map_price'] = $aProduct['new_map_price'];
        $aUpdateProduct['eta_date_mm_dd_yyyy'] = $aProduct['eta_date_mm_dd_yyyy'];
        $aUpdateProduct['lead_time_message'] = $aProduct['lead_time_message'];
        $aUpdateProduct['r_avail'] = $aProduct['r_avail'];

        func_array2update($this->sPrimaryTable, $aUpdateProduct, $this->sPrimaryKeyFiled." = ".$aProduct[$this->sPrimaryKeyFiled]);

        return true;

    }

    /*protected function getClonedData($aParams)  {
        $aSelectResult = array();

        foreach ($this->arrCloneTableStructure as $sTable) {
            $aSelectResult[$sTable['table']]['result'] = func_query("SELECT * FROM ".$this->sql_tbl[$sTable['table']]." WHERE ".$sTable['key_field']." = ".$aParams[$this->sPrimaryKeyFiled]);
            if (isset($aSelectResult[$sTable['table']]['result']) && is_array($aSelectResult[$sTable['table']]['result']))
            foreach ($aSelectResult[$sTable['table']]['result']  as &$aRows) {
                if ($sTable['primary_key'] != $sTable['key_field']) {
                    unset($aRows[$sTable['primary_key']]);
                }
            }
            $aSelectResult[$sTable['table']]['key_field'] =  $sTable['key_field'];
        }

        return $aSelectResult;
    }

    protected function DublicatePrimaryTable ($aCloneParam){

        $this->aClonedData = $this->getClonedData($aCloneParam);

        $insertRow = reset($this->aClonedData[$this->sPrimaryTable]['result']);
        foreach ($aCloneParam as $key => $value) {
            if (in_array($key, array_keys($insertRow)) && $key != $this->sPrimaryKeyFiled) {
                $insertRow[$key] = $value;
            }
        }
        unset($insertRow[$this->sPrimaryKeyFiled]);
        array_walk_recursive($insertRow, array(__CLASS__,'recursive_escape'));
//        func_print_r($insertRow);
        return func_array2insert($this->sPrimaryTable, $insertRow);
    }

    private function DublicateNonPrimaryTable ($aCloneParam, $deleteBeforeInsert = false){

        unset ($this->aClonedData[$this->sPrimaryTable]);

        if (isset($this->aClonedData) && is_array($this->aClonedData) && count($this->aClonedData)>0) {
            foreach ($this->aClonedData as $sTable => $aRowsToClone) {
                if (isset($aRowsToClone['result']) && is_array($aRowsToClone['result']) && count($aRowsToClone['result']) > 0)
                    foreach ($aRowsToClone['result'] as $aRow) {
                        foreach ($aCloneParam as $keyParam => $valueParam) {
                            if (in_array($keyParam, array_keys($aRow))) {
                                $aRow[$keyParam] = $valueParam;
                            }
                        }

                        $aRow[$aRowsToClone['key_field']] = $aCloneParam[$this->sPrimaryKeyFiled];


                        array_walk_recursive($aRow, array(__CLASS__,'recursive_escape'));
//func_print_r($aRow);
                        if ($deleteBeforeInsert) {
                            $this->deleteFromTableByKeyValue($sTable, $aRowsToClone['key_field'], $aCloneParam[$this->sPrimaryKeyFiled]);
                        }

                        func_array2insert($sTable, $aRow);

                    }
            }
        }

        return true;
    }*/


    protected function queueNewProductForUpdate ($aProduct) {
        func_query ("INSERT INTO $this->sql_tbl['clone_products_queue'] (productid, clone, insert_datetime, manufacturerid)
                     VALUES (".$aProduct['productid'].", 'N',".time().",".$aProduct['productid'].")");
    }

    protected function getProductBySKU($sSKU) {
        $aProduct = func_query_first("SELECT * FROM xcart_products xp WHERE xp.productcode = '$sSKU'");
        if (empty($aProduct)) return false;
        return $aProduct;
    }

    protected function  updateProduct($aProduct) {
        //ЕСЛИ [PRODUCT] не существует ИЛИ trim([PRODUCT].clone_parent_product_sku) != '', ТО
        if (empty($aProduct) || $aProduct["clone_parent_product_id"] > 0) {
            $this->BackprocessLogs('trying update cloned or non-existing product . skip...');
            return false;
        }
        /*
         ИНАЧЕ получить все подчиненные дистрибьюторы дистрибьютора продукта [PRODUCT] --> [Distributors] (получить code дистрибьютора , отобрать всех дистрибьюторов у которых parent_manufacturer_id = manufacturer_id)
	    */
        $classManufacturer = new classManufacturer();

        $aManufacturer = $classManufacturer->getMainufacturersInfo(array($aProduct["manufacturerid"]));
        $aManufacturer = reset($aManufacturer);

        $aChildManufacturers = $classManufacturer->getChildrenManufacturers($aManufacturer["manufacturerid"]);

        /*
         ЦИКЛ по [Distributors]
			сформировать clonedSKU предполагаемого клона по очередному дистрибьютору: [Distributors].code-[PRODUCT].mpn
			если clonedSKU существует в БД, то
				вызвать блок обновления продукта для очередного подчиненного дистрибьютора;
				посчитать успешное обновление
			конец если
		КОНЕЦ ЦИКЛ по [Distributors]
         * */

        foreach ($aChildManufacturers as $aChildManufacturer) {
            //сформировать clonedSKU предполагаемого клона по очередному дистрибьютору: [Distributors].code-[PRODUCT].mpn
            $sClonedSKU = $this->getClonedSKU($aChildManufacturer["code"], $this->getProductMPN($aChildManufacturer["code"], $aProduct["productcode"]));
            if ($sClonedSKU) {
                //если clonedSKU существует в БД, то
                //вызвать блок обновления продукта для очередного подчиненного дистрибьютора;
                if ($this->updateClonedProduct($aProduct)) {
                //посчитать успешное обновление
                    $this->IncSuccessUpdate();
                }
            }
        }

        return true;

    }



}