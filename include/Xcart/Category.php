<?php
namespace Xcart;

class Category extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['categoryid'];
        $this->sPrimaryTable = 'categories';
        parent::__construct($aParams);
    }

    public function getPath()
    {
        return $this->getField('categoryid_path');
    }

    public function getCategoryId()
    {
        return $this->getField('categoryid');
    }

    /**
     * @param string $delimiter
     */
    public function getPathExploded($delimiter = '/')
    {
        global $config;
        $sResult = null;
        $aP = [];
        $max_depth = $config["Appearance"]["category_max_depth"];
        $aPath = explode($delimiter, $this->getPath());
        $depth = count($aPath);
        if (!empty($aPath)) {
            foreach ($aPath as $iCategoryId) {
                if (empty($max_depth) || ($depth <= $max_depth)) {
                    $aP[] =  Category::model(['categoryid' => $iCategoryId])->getField('category');
                } else {
                    $aP[] = '...';
                }
                $depth--;
            }
        }
        if (!empty($aP)) {
            $sResult = implode($delimiter, $aP);
        }
        return $sResult;
    }
}