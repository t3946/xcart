<?php
namespace Xcart;

class ProductImage extends Data
{
    private $sImageType = null;
    private $bUseCDN = false;
    private $sCDNURL = null;

    public function __construct($type, $iId = null)
    {
        $this->sPrimaryTable = "images_" . $type;
        $this->aPrimaryKeys = ["imageid"];

        parent::__construct($iId);
        $this->sImageType = $type;
    }

    public function getFileName()
    {
        $aPath = pathinfo($this->getField("image_path"));
        return $aPath['basename'];
    }

    public function getImagePath()
    {
        global $xcart_dir;
        $sPath = null;
        $aPath = pathinfo($this->getField("image_path"));
        if ($this->bUseCDN)
            $sPath = 'http://' . $this->sCDNURL . ltrim($aPath['dirname'], '.') . '/';
        else
            $sPath = $xcart_dir . ltrim($aPath['dirname'], '.') . '/';
        return $sPath;
    }

    public function getURL()
    {
        return ltrim($this->getField("image_path"), '.');
    }

    public function saveImageTo($sPath)
    {
        $image_folder_path = $this->getImagePath();

        if ((file_exists($image_folder_path . $this->getFileName()) || $this->bUseCDN) && !is_dir($image_folder_path . $this->getFileName())) {

            if (!file_exists($sPath)) {
                mkdir($sPath, 0755, true);
            }
            if ($this->bUseCDN) {
                $ImgData = file_get_contents_curl($image_folder_path . $this->getFileName());
                if (!empty($ImgData)) {
                    file_put_contents($sPath . $this->getFileName(), $ImgData);
                    return $this;
                } else {
                    return false;
                }
            } else {
                if (copy($image_folder_path . $this->getFileName(), $sPath . $this->getFileName())) {
                    return $this;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    public function useCDN($flag, $sCDNUrl = null)
    {
        $this->bUseCDN = $flag;
        if ($flag)
            $this->sCDNURL = $sCDNUrl;
        return $this;
    }
}