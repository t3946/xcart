<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classHTMLShot extends classData
{
    const PATH_TO_HTMLS_SHOT_IMAGES = "images/HTML/%d/";
    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "product_htmlshot";
        $this->sPrimaryKeyFiled = "id";

        parent::__construct($iId);
    }

    public function createHTMLShot(classProduct $oProduct)
    {
        $aImagesD = $oProduct->getImages('D');
        $aImagesP = $oProduct->getImages('P');
        $aImages = array_merge($aImagesD,$aImagesP);
        foreach ($aImages as $oImage)
        {
            if ($oImage->saveImageTo(sprintf(self::PATH_TO_HTMLS_SHOT_IMAGES,$oProduct->getProductId()))){
                $oImage->setField('image_path','./'.sprintf(self::PATH_TO_HTMLS_SHOT_IMAGES,$oProduct->getProductId()).$oImage->getFileName());
            }

        }
    }
}