<?php

namespace Modules\Brand\Models;

use Doctrine\DBAL\Types\Types;
use Modules\Brand\BrandModule;
use Modules\Core\Helpers\Cache;
use Modules\Core\Helpers\CoreHelper;
use Modules\Goods\Models\ImageDModel;
use Modules\Marketplace\Models\ExternalMarketplaceDisabledBrandModel;
use Modules\Marketplace\Models\ExternalMarketplaceDisabledDxModel;
use Modules\Marketplace\Models\ExternalMarketplaceDisabledModel;
use Modules\Goods\Models\ProductModel;
use Modules\Marketplace\Models\ExternalMarketPlaceModel;
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
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\SlugifyTrait;

/**
 * @property mixed brandid
 * @property ?string brand
 * @property string avail
 * @property ProductModel[]|Manager products_active
 */
class BrandModel extends Model
{
    use SlugifyTrait;

    public static function tableName()
    {
        return 'xcart_brands';
    }

    public static function getFields()
    {
        $alias = ExternalMarketplaceDisabledModel::objects()->getTableAlias();
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
                'verboseName' => 'Description'
            ],
            'brand' => [
                'class' => CharField::class,
                'null' => false,
                'verboseName' => 'Brand',
            ],
            'meta_descr' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'SEO meta description'
            ],
            'avail' => [
                'class' => CharField::class,
                'null' => false,
                'default' => 'Y',
                'choices' => [
                    'Y' => 'Yes',
                    'N' => 'No',
                ],
                'verboseName' => 'Status',
            ],
            'prevent_search_indexing_of_all_brand_products' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
                'verboseName' => 'Prevent search indexing of all brand products'
            ],
            'prevent_search_indexing_brand_page' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
                'verboseName' => 'Prevent search indexing brand page'
            ],
            'disclaimer_text' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Brand disclaimer'
            ],
            'title' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => "Title (title)"
            ],
            'url' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'URL (include http://)'
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
                'verboseName' => 'Order by'
            ],
            'product_brand_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Product brand name'
            ],
            'customer_service_name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Customer service name'
            ],
            'leadtime_from' => [
                'class' => IntField::class,
                'null' => true,
                'default' => 0,
            ],
            'leadtime_to' => [
                'class' => IntField::class,
                'null' => true,
                'default' => 0,
            ],
            'link_to_us_url' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Link to us URL (include http://)'
            ],
            'customer_service_email' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Customer service email'
            ],
            'customer_service_phone' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'Customer service phone'
            ],
            'SEO_brand_name_h1' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => 'SEO brand name (H1)'
            ],
            'SEO_h2' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'verboseName' => "SEO (H2)"
            ],
            'brand_storefront' => [
                'class' => HasManyField::class,
                'modelClass' => BrandStorefrontModel::class,
                'link' => ['brandid' => 'brandid']
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
            'products_active' => [
                'class' => HasManyField::class,
                'modelClass' => ProductModel::class,
                'link' => ['brandid' => 'brandid'],
                'extra' => ['forsale' => 'Y']
            ],
            'image' => [
                'class' => ImageField::class,
                'adapterName' => 'www',
                'uploadTo' => '/images/B/%Y-%m-%d/',
                'null' => true,
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
                'sqlType' => Types::STRING,
                'modelClass' => UserModel::class,
                'link' => ['provider' => 'login']
            ],
            'markets_disabled' => [
                'class' => ManyToManyField::class,
                'modelClass' => ExternalMarketPlaceModel::class,
                'through' => ExternalMarketplaceDisabledBrandModel::class,
            ],
        ];
    }

    public function getBreadcrumbs(): Breadcrumbs
    {
        $bread = new Breadcrumbs();

        $bread->add(BrandModule::t('Brands'), 'brand:list');
        $bread->add($this->brand, $this->getAbsoluteUrl());
        return $bread;
    }
    public function getImage()
    {
        return $this->image->getValue() ?? null;
    }

    public function getAbsoluteUrl($full = false)
    {
        $url = '';
        if ($this->brandid) {

            $url = Xcart::app()->router->url(
                'brand:view',
                ['id' => $this->pk, 'slug' => $this->getSlugPart() ?: $this->pk]
            );

            if ($full) {
                $site = Xcart::app()->getModule('Sites')->getSite();
                $url = '//' . $site->domain . $url;
            }
        }

        return $url;
    }

    public function getAdminUrl()
    {
        if ($this->isNewRecord) {
            return Xcart::app()->router->url('brand:create_brand');
        }

        return Xcart::app()->router->url('brand:update_brand', ['id' => $this->brandid]);
    }

    public function getUrl()
    {
        /** TODO rewrite on new router */
        return "/brand/{$this->brandid}";
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
            'brand_storefront__sfid' => Xcart::app()->getModule('Sites')->getSite(),
            'brand_storefront__products_count__gte' => 100,
        ])->cache(Cache::CACHE_DAY);

        return $qs->order(['brand'])->all();
    }
    public function __toString()
    {
        return $this->brand ?? '';
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
            $letter = mb_strtoupper(mb_substr($brand->brand, 0, 1));
            if (!isset($brands[$letter])) {
                $brands[$letter] = [$brand];
            } else {
                $brands[$letter][] = $brand;
            }
        }
        return $brands;
    }

    public function getSlugPart(): string
    {
        return $this->createSlug($this->brand);
    }

}