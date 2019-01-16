<?php
namespace Xcart;

class StoreFronts extends Data
{
    /**
     * @var Storefront[]
     */
    private $aStoreFronts = [];

    public function __construct($iId = null)
    {
        $this->sPrimaryTable = "storefronts";
        $this->sPrimaryKeyFiled = "storefrontid";

        parent::__construct($iId);
        $this->fetchStoreFronts();
    }

    public function fetchStoreFronts()
    {
        if (empty($this->aStoreFronts)) {
            $oArtist = new StoreFront(['storefrontid'=>0]);
            $this->aStoreFronts[] = $oArtist;
            $aStoreFronts = func_query("SELECT * FROM " . self::$sql_tbl['storefronts'] . " ORDER BY domain" );
            if (!empty($aStoreFronts)) {
                foreach ($aStoreFronts as $aStoreFront) {
                    $oStorefront = new StoreFront();
                    $oStorefront->fill($aStoreFront);
                    $this->aStoreFronts[] = $oStorefront;
                }
            }
        }
        return $this;
    }

    public function getStoreFrontsSelect()
    {
        $aResult = [];
        foreach ($this->aStoreFronts as $oStoreFront){
            if ($name = $oStoreFront->getCompanyName()) {
                $aResult[$oStoreFront->getStoreFrontId()] = $name;
            }
        }
        return $aResult;

    }

    public function getStoreFronts() {
        if (empty($this->aStoreFronts)) {
            $this->fetchStoreFronts();
        }
        return $this->aStoreFronts;
    }
}