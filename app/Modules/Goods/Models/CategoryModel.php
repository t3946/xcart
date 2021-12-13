<?php

namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\TreeForeignField;
use Xcart\App\QueryBuilder\Expression;
use Modules\Goods\Helpers\CategoryCalculateHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\TreeModel;
use Xcart\App\Storage\FileNameHasher\MD5FileContentHasher;
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
 * @property null|SiteModel site
 * @property Manager|ProductModel[] products
 * @property int global_product_count
 * @property int product_count
 */
class CategoryModel extends TreeModel
{
    use AutoMetaTrait;
    use DataModelTrait;
    use SlugifyTrait;

    public static function getDataModelClass(): string
    {
        return Category::class;
    }

    public static function tableName()
    {
        return 'xcart_categories';
    }

    public static function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'avail' => [
                    'class' => CharField::class,
                    'verboseName' => 'Availability',
                    'default' => 'Y',
                    'choices' => [
                        'Y' => 'Enabled',
                        'N' => 'Disabled'
                    ]
                ],
                'products' => [
                    'class' => ManyToManyField::class,
                    'modelClass' => ProductModel::class,
                    'through' => ProductCategoriesModel::class,
                ],

                'site' => [
                    'field' => 'storefrontid',
                    'class' => ForeignField::class,
                    'modelClass' => SiteModel::class,
                    'link' => ['storefrontid' => 'storefrontid'],
                    'null' => false,
                ],

                'categoryid' => [
                    'class' => AutoField::class,
                    'primary' => true,
                    'null' => false,
                ],

                'description' => [
                    'class' => CharField::class,
                    'null' => false,
                    'default' => '',
                ],
                'google_product_category' => [
                    'class' => CharField::class,
                    'verboseName' => 'Google product category',
                    'null' => false,
                    'default' => '',
                ],
                'SEO_h2' => [
                    'verboseName' => 'SEO Description Category',
                    'class' => CharField::class,
                    'null' => false,
                    'default' => '',
                ],
                'icon_path' => [
                    'verboseName' => 'Icon',
                    'class' => ImageField::class,
                    'adapterName' => 's3',
                    'uploadTo' => "categories/icons/%Y%m",
                    'nameHasher' => MD5FileContentHasher::class,
                    'null' => true,
                    'default' => null
                ],
                'picture_path' => [
                    'verboseName' => 'Main picture',
                    'class' => ImageField::class,
                    'adapterName' => 's3',
                    'uploadTo' => "categories/picture/%Y%m",
                    'nameHasher' => MD5FileContentHasher::class,
                    'null' => true,
                    'default' => null
                ],
                'is_bold' => [
                    'verboseName' => 'Bold',
                    'class' => CharField::class,
                    'choices' => [
                        'Y' => 'Yes',
                        'N' => 'No'
                    ]
                ],
                'supplemental_category' => [
                    'verboseName' => 'Supplemental category',
                    'class' => BooleanCharField::class,
                    'default' => false
                ],
                'pc_ready_to_classify' => [
                    'verboseName' => 'Ready to classify',
                    'class' => BooleanCharField::class,
                    'default' => false
                ],
                'prevent_index_products' => [
                    'verboseName' => 'Prevent index products',
                    'class' => BooleanCharField::class,
                    'default' => false
                ],
                'prevent_index_category_page' => [
                    'verboseName' => 'Prevent index category page',
                    'class' => BooleanCharField::class,
                    'default' => false
                ],
                'title_tag' => [
                    'class' => CharField::class,
                    'verboseName' => htmlentities('Title (<title>)')
                ],
                'SEO_category_name' => [
                    'class' => CharField::class,
                    'verboseName' => htmlentities('SEO category name (<H1>)')
                ],
                'meta_keywords' => [
                    'class' => CharField::class,
                    'verboseName' => 'META keywords',
                    'default' => '',
                ],
                'meta_descr' => [
                    'class' => CharField::class,
                    'verboseName' => 'META description',
                    'default' => '',
                ],
                'pc_category_weight' => [
                    'class' => DecimalField::class,
                    'verboseName' => 'Category classify weight',
                    'default' => 0,
                ],
                'pc_z' => [
                    'class' => DecimalField::class,
                    'verboseName' => 'Category Z parameter',
                    'default' => 0
                ],
                'parent' => [
                    'class' => TreeForeignField::class,
                    'null' => true,
                    'modelClass' => self::class,
                    'field' => 'parentid',
                ],
            ]
        );
    }

    public function __toString()
    {
        $code = '';
        if ($st = $this->site) {
            $code .= $st->code . ":";
        }

        $code .= $this->pk;

        return "[$code] $this->category";
    }

    public function getBreadcrumbs()
    {
        $bread = new Breadcrumbs();

        if ($parents = $this->getObjects()->ancestors(true)->order(['lft'])->all()) {
            /** @var self $model */
            foreach ($parents as $model) {
                $url = $model->getAbsoluteUrl(true);
                $bread->add($model->category, $url ? 'https:' . $url : '');
            }
        }

        return $bread;
    }

    public function getAbsoluteUrl($full = false): string
    {
        $url = Xcart::app()->router->url(
            'catalog:view',
            [
                'id' => $this->pk,
                'slug' => $this->getSlugPart() ?: $this->pk
            ]
        );

        if ($full) {
            $site = $this->site ?: Xcart::app()->getModule('Sites')->getSite();
            $url = '//' . $site->domain . $url;
        }
        return $url;
    }

    public function getFrontendName()
    {
        return $this->SEO_category_name ?: $this->category;
    }

    /**
     * Return all active children
     * @param bool $includeSelf
     * @param int $level
     * @return $this
     */
    public function getActiveChildren($includeSelf = false, $level = 1)
    {
        return $this->getObjects()->descendants($includeSelf, $level)->filter([
            'avail' => 'Y',
            'active_product_count__gt' => 0,
        ])->cache(3600);
    }

    public function getSubcategories($withProductCount = true, $level = 1, $tree = false, $cache = true)
    {
        $qs = $this->objects()
            ->descendants(false, $level)
            ->filter(['avail' => 'Y']);

        if ($withProductCount) {
            $qs->filter(['active_product_count__gt' => 0,]);
        }

        if ($tree) {
            $qs->asTree();
        }

        return $qs->cache(3600)->all();
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

    public function getSlugPart(): string
    {
        return $this->createSlug($this->category);
    }
}