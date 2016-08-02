<?php
global $xcart_dir;
require_once $xcart_dir . "/include/class/classData.php";

class classProductImage extends classData
{
    private $sImageType = null;

    public function __construct($type, $iId = null)
    {
        $this->sPrimaryTable = "images_" . $type;
        $this->sPrimaryKeyFiled = "imageid";

        parent::__construct($iId);
        $this->sImageType = $type;
    }

    public function getFileName()
    {
        return $this->getField("filename");
    }

    public function saveImageTo($sPath)
    {
        global $xcart_dir;
        $image_folder_path = $xcart_dir . "/images/" . $this->sImageType . "/";
        if (file_exists($image_folder_path . $this->getFileName()) && !is_dir($image_folder_path . $this->getFileName())) {
            if (!file_exists($sPath)) {
                mkdir($sPath, 0755, true);
            }

            if (copy($image_folder_path . $this->getFileName(), $sPath . $this->getFileName())) {
                return $this;
            } else return false;
        }
        return false;
    }
}