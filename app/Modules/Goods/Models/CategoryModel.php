<?php
namespace Modules\Goods\Models;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Menu\Models\CleanUrlModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\TreeModel;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Category;

/**
 * Class CategoryModel
 *
 * @package Modules\Goods\Models
 *
 * @property string categoryid_path
 * @property mixed categoryid
 * @property string category Name of category
 * @property null|CleanUrlModel url
 * @property null|\Modules\Sites\Models\SiteModel site
 * @property Manager|ProductModel[] products
 */
class CategoryModel extends TreeModel
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass()
    {
        return Category::className();
    }

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
                     'through' => ProductCategoriesModel::className(),
                 ],

                 'url' => [
                     'field' => 'categoryid',
                     'class' => ForeignField::className(),
                     'modelClass' => CleanUrlModel::className(),
                     'link' => ['categoryid' => 'resource_id'],
                     'extra' => ['resource_type' => 'C'],
                 ],

                'site' => [
                    'field' => 'storefrontid',
                    'class' => ForeignField::className(),
                    'modelClass' => SiteModel::className(),
                    'link' => ['storefrontid' => 'storefrontid'],
                    'null' => false,
                ],

                'categoryid' => [
                    'class' => AutoField::className(),
                    'primary' => true,
                    'null' => false,
                ],
                'parent' => [
                    'field' => 'parentid'
                ],


                'description' => [
                    'class' => CharField::className(),
                    'null' => false,
                    'default' => '',
                ],
                'google_product_category' => [
                    'class' => CharField::className(),
                    'null' => false,
                    'default' => '',
                ],
            ]
        );
    }

    public function __toString()
    {
        $code = '';
        if ($st = $this->site) {
            $code .=  $st->code .":";
        }

        $code .= $this->pk;

        return "[{$code}] {$this->category}";
    }

    public function getBreadcrumbs()
    {
        $bread = new Breadcrumbs();

        if ($parents = self::objects($this)->ancestors()->order(['lft'])->all())
        {
            /** @var self $model */
            foreach ($parents as $model) {
                $bread->add($model->category, $model->getAbsoluteUrl());
            }
        }

        $bread->add($this->category, $this->getAbsoluteUrl());

        return $bread;
    }

    public function getAbsoluteUrl($full = false)
    {
        if ($this->categoryid && $this->url)
        {
            return $this->url->urlFromCode('catalog:view', $full, $this->site);

//            return Xcart::app()->router->url('catalog:view:old', ['id' => $this->categoryid, 'slug' => 'TEMP']);
        }

        return false;
    }

    public function getSubcategories($withProductCount = true, $level = 1, $tree = false, $cache = true)
    {
        $qs = static::objects()
                    ->descendants(false, $level)
                    ->filter(['avail' => 'Y']);

        if ($withProductCount) {
            $ta = $qs->getTableAlias();

            $pcountSql = ProductModel::objects()
                        ->with(['categories'])
                        ->filter([
                            'forsale' => 'Y',
                            'categories__lft__gte' => new Expression("{{category}}.lft"),
                            'categories__rgt__lte' => new Expression("{{category}}.rgt"),
                            'categories__root' => new Expression("{{category}}.root"),
                        ])
                        ->countSql();

            $pcountSql = str_replace($ta, 'cp', $pcountSql);
            $pcountSql = str_replace("{{category}}", $ta, $pcountSql);

            $qs->group(['categoryid']);
            $qs->select([
                'pcount' => $pcountSql,
                '*',
            ]);

            $qs->having(['pcount__gt' => 0]);
        }

        if ($tree) {
            $qs->asTree();
        }

        if ($cache) {
            $qs->cache(300);
        }

        return $qs->all();
    }

    public function afterDelete($owner)
    {
        parent::afterDelete($owner);

        ProductCategoriesModel::objects()->delete(['categoryid' => $this->categoryid]);
    }

    public function beforeSave($owner, $isNew)
    {
        parent::beforeSave($owner, $isNew);

        $this->categoryid_path = $this->pk;

        if ($parent = $this->parent) {
            $this->categoryid_path = $parent->categoryid_path . '/' . $this->pk;
        }
    }

    public function afterSave($owner, $isNew)
    {
        parent::afterSave($owner, $isNew);

        //@TODO: For old code, delete after global refactoring

        /** @var static $owner */
        $old_parent = $owner->attributes->getOldAttribute('parentid');

        if ($old_parent != $owner->parentid) {
            $this->objects()
                ->descendants()
                ->update([
                    'categoryid_path' => new Expression("CONCAT('{$this->categoryid_path}', SUBSTRING_INDEX(categoryid_path, {$owner->pk}, -1))")
                ]);
        }

//        @TODO: SLOOOOOOOW
//        if ($old_parent) {
//            $parent = static::objects()->get(['pk' => $old_parent]);
//            $parent->reCalcSelfAndParents();
//        }
//
//        if (!$isNew) {
//            $this->reCalcProductsCount();
//        }
    }

    public function reCalcProductsCount()
    {
        $ta = ProductModel::objects()->getQuerySet()->getTableAlias();
        $qor = new QOr(['group_root__raw' => " = `{$ta}`.`productid`", 'group_root__isnull' => true]);

        $this->global_product_count = ProductModel::objects()
            ->with(['categories'])
            ->filter([
                'categories__lft__gte' => $this->lft,
                'categories__rgt__lte' => $this->rgt,
                'categories__root' => $this->root,
            ])
            ->count();

        $this->active_product_count = ProductModel::objects()
            ->with(['categories'])
            ->filter([
                'forsale' => 'Y',
                'categories__lft__gte' => $this->lft,
                'categories__rgt__lte' => $this->rgt,
                'categories__root' => $this->root,
                'categories__avail' => 'Y',
                $qor
            ])
            ->count();

        $this->product_count = $this->products->filter(['forsale' => 'Y', $qor])->count();
        $this->subcategory_count = $this->objects()->descendants()->count();

        $this->save(['global_product_count', 'active_product_count', 'product_count', 'subcategory_count']);
    }

    public function reCalcSelfAndParents()
    {
        if ($models = $this->objects()->ancestors(true)->all()) {
            /** @var static $model */
            foreach ($models as $model)
            {
                $model->reCalcProductsCount();
            }
        }
    }
}