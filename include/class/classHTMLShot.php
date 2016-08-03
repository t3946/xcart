<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classHTMLShot extends classData
{
    const PATH_TO_HTMLS_SHOT_IMAGES = "/images/HTML/%d_%d/";
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'product_htmlshot';
        parent::__construct($aParams);
    }

    public function createHTMLShot(classProduct $oProduct, $orderid)
    {
        global $xcart_dir;
        $aImagesD = $oProduct->getImages('D');
        $aImagesP = $oProduct->getImages('P');
        $aImages = array_merge($aImagesD,$aImagesP);
        $oStoreFront = $oProduct->getStoreFront()->getStoreFrontByProductId($oProduct->getProductId());

        foreach ($aImages as $oImage)
        {
            if ($oStoreFront->isCDNEnable()){
                $oImage->useCDN(true,$oStoreFront->getCDNURL());
            } else $oImage->useCDN(false);


            if ($oImage->saveImageTo($xcart_dir.sprintf(self::PATH_TO_HTMLS_SHOT_IMAGES,$orderid,$oProduct->getProductId()))){
                $oImage->setField('image_path',sprintf(self::PATH_TO_HTMLS_SHOT_IMAGES,$orderid,$oProduct->getProductId()).$oImage->getFileName());
            }

        }
        $oProduct->getPricing();
        $oStoreFront->setCDNDisable();

        $this->setField('htmlshot',addslashes(serialize($oProduct)));
        $this->setField('product_id',$oProduct->getProductId());
        $this->setField('order_id',$orderid);
        $this->_insert();
    }

    public function getId()
    {
        return $this->getField('id');
    }

    public function getOrderId()
    {
        return $this->getField('order_id');
    }

    public function getProductId()
    {
        return $this->getField('product_id');
    }

    public function getHTMLShot()
    {
        return unserialize($this->getField('htmlshot'));
    }

    public function deleteHTMLShot()
    {
        db_query("DELETE FROM " . self::$sql_tbl[$this->sPrimaryTable] . " WHERE id = ".$this->getField('id'));
    }
}