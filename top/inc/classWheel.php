<?php

class WheelCore {
    private $sFileName;
    private $sFileContent;
    public function __construct($sFileName) {
        $this->sFileName = $sFileName;
        $this->sFileContent = "";
    }

    public function readFileSites() {
        if (!empty($this->sFileName)) {
            $hOpenFile = fopen($this->sFileName,"r");
            $this->sFileContent = fread($hOpenFile, filesize($this->sFileName));
            while (!feof($hOpenFile)) {
                $this->sFileContent .= fread($hOpenFile, 8192);
            }
            fclose($hOpenFile);
            return $this->sFileContent;
        }
        return false;
    }

    public function getJSonArray() {
        return $this->convertArrayToJSon($this->getArrayFromText());
    }

    private function getArrayFromText() {

        $aSites = array();
        $arrLines = explode("\n",$this->sFileContent);
        foreach ($arrLines as $sLine) {
         $aSites[] = explode(";",$sLine);
        }
        return $aSites;
    }
    private function convertArrayToJSon($aSites){
        return json_encode($aSites, JSON_PRETTY_PRINT);
    }
}

?>