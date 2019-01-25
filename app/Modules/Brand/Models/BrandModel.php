<?php

namespace Modules\Brand\Models;

use Doctrine\DBAL\Types\Type;
use Modules\Brand\BrandModule;
use Modules\Core\Helpers\Cache;
use Modules\Menu\Models\CleanUrlModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\UserModel;
use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * @property mixed brandid
 */
class BrandModel extends Model
{

    public static function tableName()
    {
        return 'xcart_brands';
    }

    public static function getFields()
    {
        return [
            'brandid' => [
                'class' => AutoField::class,
                'primary' => true,
                'null' => false,
            ],
            'descr' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => BrandModule::t('Description')
            ],
            'brand' => [
                'class' => CharField::class,
                'null' => false,
                'verboseName' => BrandModule::t('Brand'),
            ],
            'meta_descr' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => BrandModule::t('SEO meta description')
            ],
            'avail' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => 'Y',
                'verboseName' => BrandModule::t('Availability')
            ],
            'prevent_search_indexing_of_all_brand_products' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => 'N',
                'verboseName' => BrandModule::t('Prevent search indexing of all brand products')
            ],
            'prevent_search_indexing_brand_page' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => 'N',
                'verboseName' => BrandModule::t('Prevent search indexing brand page')
            ],
            'disclaimer_text' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => BrandModule::t('Brand disclaimer')
            ],
            'title' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => BrandModule::t("Title (<title>)")
            ],
            'url' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
                'verboseName' => BrandModule::t('Order by')
            ],
            'product_brand_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],
            'customer_service_name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'link_to_us_url' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'customer_service_email' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'customer_service_phone' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'SEO_brand_name_h1' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => BrandModule::t('SEO brand name (<H1>)')
            ],
            'SEO_h2' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => BrandModule::t("SEO (<H2>)")
            ],
            'brand_storefront' => [
                'class' => HasManyField::class,
                'modelClass' => BrandStorefrontModel::class,
                'link' => ['brandid' => 'brandid']
            ],
            'storefront' => [
                'class' => ManyToManyField::class,
                'modelClass' => SiteModel::class,
                'through' => BrandStorefrontModel::class,
            ],
            'child_brands' => [
                'class' => HasManyField::class,
                'modelClass' => BrandModel::class,
                'link' => ['brandid' => 'parent_brand_id']
            ],
            'products' => [
                'class' => HasManyField::class,
                'modelClass' => ProductModel::class,
                'link' => ['brandid' => 'brandid']
            ],
            'parent' => [
                'field' => 'parent_brand_id',
                'class' => ForeignField::class,
                'modelClass' => BrandModel::class,
                'link' => ['parent_brand_id' => 'brandid']
            ],
            'user' => [
                'field' => 'provider',
                'class' => ForeignField::class,
                'sqlType' => Type::STRING,
                'modelClass' => UserModel::class,
                'link' => ['provider' => 'login']
            ],
            'clean_url' => [
                'class' => HasToOneField::class,
                'modelClass' => CleanUrlModel::class,
                'link' => ['brandid' => 'resource_id'],
                'extra' => ['resource_type' => 'M'],
            ],

        ];
    }

    public function getImage()
    {
        return ImageBModel::objects()->limit(1)->get(['id' => $this->brandid]);
    }

    public function getBreadcrumbs(): Breadcrumbs
    {
        $bread = new Breadcrumbs();

        $bread->add('Brands', 'brand:list');
        $bread->add($this->brand, $this->getAbsoluteUrl());
        return $bread;
    }

    public function getAbsoluteUrl($full = false)
    {
        if ($this->brandid) {

            $url = Xcart::app()->router->url('brand:view', ['id' => $this->pk, 'slug' => $this->clean_url->getSlugPart()]);

            if ($full) {
                $site = Xcart::app()->getModule('Sites')->getSite();

                $url = '//' . $site->domain . $url;
            }
            return $url;
        }

        return false;
    }

    public function getAdminUrl()
    {
        if ($this->isNewRecord) {
            return Xcart::app()->router->url('brand:create_brand');
        } else {
            return Xcart::app()->router->url('brand:update_brand', ['id' => $this->brandid]);
        }
    }

    public function getUrl()
    {
        /** TODO rewrite on new router */
        return "/brand/{$this->brandid}";
    }

    public function __toString()
    {
        $code = '';
        if ($st = $this->storefront->limit(1)->get()) {
            $code .=  $st->code .":";
        }

        $code .= $this->pk;

        return "[{$code}] {$this->brand}";
    }

    public function getProductFrontendName()
    {
        return $this->product_brand_name ?: $this->brand;
    }

    /**
     * Return all active brands
     * @param bool $includeSelf
     * @param int $level
     * @return mixed
     * @throws \Xcart\App\Exceptions\UnknownMethodException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public static function getAllActive()
    {
        $qs = static::objects()->filter([
            'parent_brand_id__isnull' => true,
            'avail' => 'Y',
            'storefront__through__sfid' => Xcart::app()->getModule('Sites')->getSite(),
            'storefront__through__products_count__gt' => 0,
        ])->cache(Cache::CACHE_DAY);

        return $qs->order(['brand'])->all();
    }

    /**
     * Recieve all brands and split alphabetically
     * @return array
     * @throws \Xcart\App\Exceptions\UnknownMethodException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function getAllAlphabetically()
    {
        $allBrands = static::getAllActive();
        return static::breakUpAlphabetically($allBrands);
    }

    /**
     * Split brands alphabetically
     * @param $allBrands
     * @return array
     */
    private static function breakUpAlphabetically($allBrands)
    {
        $brands = [];
        foreach ($allBrands as $brand) {
            $letter = strtoupper(substr($brand->brand, 0, 1));
            if (!isset($brands[$letter])) {
                $brands[$letter] = [$brand];
            } else {
                $brands[$letter][] = $brand;
            }
        }
        return $brands;
    }


}