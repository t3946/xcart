<?php
namespace Xcart;

use Modules\Goods\Models\CategoryModel;

class Category extends Data
{
    /**
     * @var Category[] $aParentCategories
     */
    private $aParentCategories = null;
    /**
     * @var CleanUrl $oCleanUrl
     */
    private $oCleanUrl = null;

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

    public function getParentsCategories()
    {
        if (is_null($this->aParentCategories)) {
//            $aPaths = explode('/', $this->getField('categoryid_path'));

            /** @var CategoryModel $model */
//            $model = CategoryModel::objects()->get(['pk' => $this->getCategoryId()]);
            $model = new CategoryModel($this->aPrimaryTableValue);
            $aPaths = $model->getObjects()->ancestors()->valuesList(['categoryid'], true);

            if (!empty($aPaths)) {
//                array_pop($aPaths);

                if (!empty($aPaths)) {
                    $aCats = SQLBuilder::getInstance()
                        ->addSelect('c.*')
//                        ->addSelect('ROUND ((LENGTH(categoryid_path) - LENGTH(REPLACE (categoryid_path, "/", ""))) / LENGTH("/")) level')
                        ->addFromTable('categories', 'c')
                        ->addCondition("categoryid IN (".implode($aPaths).")")
                        ->addOrderBy('level DESC')->query()->getQueryResult();

                    if (!empty($aCats)) {
                        foreach ($aCats as $aCat) {
                            unset ($aCat['level']);
                            $this->aParentCategories[] = Category::model()->fill($aCat);
                        }
                    }
                }
            }
        }
        return $this->aParentCategories;
    }

    public function getFirstActiveParentCategory()
    {
        $oResult = null;
        $aParents = $this->getParentsCategories();
        if (!empty($aParents)) {
            foreach($aParents as $oParent) {
                if ($oParent->getField('avail') == 'Y') {
                    $oResult = $oParent;
                    break;
                }
            }
        }
        if (is_null($oResult)) {
            $oResult = Category::model();
        }
        return $oResult;
    }

    /**
     * @return CleanUrl
     */
    public function getCleanUrl()
    {
        if ($this->getCategoryId()) {
            $this->oCleanUrl = CleanUrl::model(['resource_type' => 'C', 'resource_id' => $this->getCategoryId()]);
        }
        return $this->oCleanUrl;
    }
}