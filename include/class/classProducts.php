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
    private $aClonedData;
    public function __construct()
    {
        parent::__construct();
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

        //func_print_r($this->aProductToQueue);

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

        $this->deleteFromQueue();

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

    protected function deleteFromQueue() {
        //func_query("DELETE FROM $this->sql_tbl[$this->sQueueTable] WHERE $this->sPrimaryKeyFiled = $this->aProductToQueue[productid]");
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



    private function BackprocessLogs($sLogMessage) {
        $this->message[] = $sLogMessage;
        func_backprocess_log("clone_product", $sLogMessage. "; Productid = $this->aProductToQueue[$this->sPrimaryKeyFiled]");
    }

    protected function cloneProduct($aProduct) {



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
                        "productid" =>  $aProduct['productid'],

                    );
                    $this->insertClonedProduct($aProduct, $aParamToClone);

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



        foreach($aProductCategories as $aProductCategory) {
            $aProductCategoryPath = $classCategory->getCategoryPath($aProductCategory["categoryid"]);
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


        $iNewProductId = $this->DublicatePrimaryTable($aParamToClone);
        $aParamToClone["productid"] = $iNewProductId;

        $this->DublicateNonPrimaryTable($aParamToClone);

        /*добавляем данные в следующие таблицы:
        xcart_categories
        xcart_products_categories

        xcart_cidev_filters
        xcart_cidev_filter_products
        xcart_cidev_filter_values

        xcart_products_sf*/

        $this->addMainProductCategory($iNewProductId, $iNewProductCategory);
        $this->addMainProductCategory($iNewProductId, $iNewProductCategory);





        return true;
    }

    protected function getClonedData($aParams)  {
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

    private function DublicateNonPrimaryTable ($aCloneParam){

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
                        func_array2insert($sTable, $aRow);

                    }
            }
        }

        return true;
    }


    protected function queueNewProductForUpdate ($aProduct) {
        func_query ("INSERT INTO $this->sql_tbl['clone_products_queue'] (productid, clone, insert_datetime, manufacturerid)
                     VALUES (".$aProduct['productid'].", 'N',".time().",".$aProduct['productid'].")");
    }

    protected function getProductBySKU($sSKU) {
        $aProduct = func_query_first("SELECT * FROM xcart_products xp WHERE xp.productcode = '$sSKU'");
        if (empty($aProduct)) return false;
        return $aProduct;
    }

    protected function  updateProduct($aProduct, $aManufacturer) {
        //ЕСЛИ [PRODUCT] не существует ИЛИ trim([PRODUCT].clone_parent_product_sku) != '', ТО
        if (empty($aProduct) || $aProduct.clone_parent_product_id > 0) {
            $this->BackprocessLogs('trying update cloned or non-existing product . skip...');
            return false;
        }
        /*
         ИНАЧЕ получить все подчиненные дистрибьюторы дистрибьютора продукта [PRODUCT] --> [Distributors] (получить code дистрибьютора , отобрать всех дистрибьюторов у которых parent_manufacturer_id = manufacturer_id)
	    */

        $aChildManufacturers = $classManufacurer->getChildParentManufacturers($aManufacturer["manufacturerid"]);

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
                $this->updateClonedProduct($aProduct);
                //посчитать успешное обновление
                $this->IncSuccessUpdate();
            }
        }

    }



}