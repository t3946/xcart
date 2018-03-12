<?php
namespace Modules\Goods\Models;

use Mindy\QueryBuilder\Expression;
use Modules\Goods\Helpers\CategoryCalculateHelper;
use Modules\Menu\Models\CleanUrlModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\TreeModel;
use Xcart\App\Traits\DataModelTrait;
use Xcart\App\Traits\SlugifyTrait;
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
    use DataModelTrait, AutoMetaTrait, SlugifyTrait;

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
        return array_replace_recursive(
            parent::getFields(),
             [
                 'parent' => [
                     'field' => 'parentid'
                 ],

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

                'parentid' => [
                    'class' => IntField::className(),
                    'default' => 0,
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

        if ($parents = $this->getObjects()->ancestors(true)->order(['lft'])->all())
        {
            /** @var self $model */
            foreach ($parents as $model) {
                $bread->add($model->category, $model->getAbsoluteUrl());
            }
        }

        return $bread;
    }

    public function getAbsoluteUrl($full = false)
    {
        if ($full) {
            if ($this->categoryid && $this->url)
            {
                return $this->url->urlFromCode('catalog:view', $full, $this->site);
            }
        }
        else {
            return Xcart::app()->router->url('catalog:view', ['id' => $this->pk, 'slug' => $this->createSlug($this->category)]);
        }


        return false;
    }

    public function getFrontendName()
    {
        return $this->SEO_category_name ?: $this->category;
    }

    public function getSubcategories($withProductCount = true, $level = 1, $tree = false, $cache = true)
    {
        $qs = $this->objects()
                    ->descendants(false, $level)
                    ->filter([ 'avail' => 'Y' ]);

        if ($withProductCount) {
            $qs->filter(['active_product_count__gt' => 0,]);
        }

        if ($tree) {
            $qs->asTree();
        }

        return $qs->all();
    }

    public function afterDelete($owner)
    {
        parent::afterDelete($owner);

        ProductCategoriesModel::objects()->delete(['categoryid' => $this->categoryid]);
    }

    public function afterSave($owner, $isNew)
    {
        parent::afterSave($owner, $isNew);

        //@TODO: For old code, delete after global refactoring

        if (empty($this->categoryid_path) || !$this->parentid) {
            $this->categoryid_path = $this->pk;
        }

        /** @var self $parent */
        if ($parent = $this->parent) {
            $this->categoryid_path = $parent->categoryid_path . '/' . $this->pk;
        }

        if ($this->categoryid_path != $owner->attributes->getOldAttribute('categoryid_path')) {
            static::objects()->filter(['pk' => $this->pk])->update(['categoryid_path' => $this->categoryid_path]);
        }

        if (!$isNew) {
            /** @var static $owner */
            $old_parent = $owner->attributes->getOldAttribute('parentid');

            if ($old_parent != $this->parentid) {
                $this->objects()
                    ->descendants()
                    ->getQuerySet()
                    ->update([
                        'categoryid_path' => new Expression("CONCAT('{$this->categoryid_path}', SUBSTRING_INDEX(categoryid_path, {$this->pk}, -1))")
                    ]);
            }

            if ($old_parent) {
                CategoryCalculateHelper::recalcParents($old_parent, true);
            }

            if ($this->parentid) {
                CategoryCalculateHelper::recalcParents($this->parentid, true);
            }
        }
    }
}