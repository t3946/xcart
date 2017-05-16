<?php
namespace Modules\Product\Models;

use Mindy\QueryBuilder\Expression;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTreeModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;

/**
 * @property string categoryid_path
 * @property mixed categoryid
 */
class CategoryModel extends AutoMetaTreeModel
{
    public static function tableName()
    {
        return 'xcart_categories';
    }

    public static function getFields()
    {
        return array_merge_recursive(
            parent::getFields(),
             [
                 'products' => [
                     'class' => ManyToManyField::className(),
                     'modelClass' => ProductModel::className(),
                     'through' => ProductCategoriesModel::className()
                 ],

                'categoryid' => [
                    'class' => AutoField::className(),
                    'primary' => true,
                    'null' => false,
                ],
                'parent' => [
                    'field' => 'parentid'
                ],

                'storefrontid' => [
                    'class' => IntField::className(),
                    'primary' => false,
                    'null' => false,
                ],
                'description' => [
                    'class' => CharField::className(),
                    'null' => false,
                    'default' => ''
                ],
                'google_product_category' => [
                    'class' => CharField::className(),
                    'null' => false,
                    'default' => ''
                ],
            ]
        );
    }

    public function getBreadcrumbs()
    {
        $bread = new Breadcrumbs();

        if ($parents = self::objects($this)->ancestors()->order(['lft'])->all())
        {
            foreach ($parents as $model) {
                $bread->add($model->category, $model->getAbsoluteUrl());
            }
        }

        $bread->add($this->category, $this->getAbsoluteUrl());

        return $bread;
    }

    public function getAbsoluteUrl()
    {
        if (!$this->getIsNewRecord())
        {
            return Xcart::app()->router->url('category:view:old', ['id' => $this->categoryid, 'slug' => 'TEMP']);
        }

        return false;
    }

    public function getThisObjects()
    {
        return static::objects($this);
    }

    public function getSubcategories($withProductCount = true, $level = 1)
    {
        $qs = static::objects()
                    ->descendants(false, $level)
                    ->filter(['avail' => 'Y']);

        if ($withProductCount) {
            $ta = $qs->getTableAlias();

            $qs->with(['products']);
            $qs->group(['categoryid']);
            $qs->select([
                'pcount' => ProductModel::objects()
//                                        ->setQuerySet($qs->getQuerySet())
                                        ->with(['categories'])
                                        ->filter([
                                            'categories__lft__gt' => new Expression("{$ta}.lft"),
                                            'categories__rgt__lt' => new Expression("{$ta}.rgt"),
                                            'categories__root' => new Expression("{$ta}.root"),
                                            new Expression("{$ta}.root > 1"),
                                         ])
                                        ->select(['count(*)'])
                                        ->getQueryBuilder(),

                '*',
            ]);
            func_dump($qs->allSql());
            
            die();

        }

        return $qs->all();
    }
}