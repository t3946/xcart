<?php

namespace Modules\Distributor\Models;

use DateTime;
use Doctrine\DBAL\Types\Type;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Distributor\Helpers\DistributorHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\Sites\Models\CurrencyModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Manufacturer;

/**
 * @property float price_coef_z
 * @property float d_minimum_order_amount_in_us
 * @property string d_minimum_order_amount
 * @property string code
 * @property string submit_to_operator
 * @property mixed currency
 */
class DistributorModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public const AMAZON_MANUFACTURER_CODE = 'AMZ';

    public static function getDataModelClass(): string
    {
        return Manufacturer::class;
    }

    public static function tableName()
    {
        return 'xcart_manufacturers';
    }

//    public static function getPrimaryKeyName($asArray = false)
//    {
//        return ['manufacturerid'];
//    }

    public static function getFields()
    {
        return [
            'manufacturerid' => [
                'class' => AutoField::className()
            ],
            'manufacturer' => [
                'class' => CharField::class
            ],
            'shipping_rates' => [
                'class' => HasManyField::className(),
                'modelClass' => ShippingRateModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'contacts_model' => [
                'class' => HasManyField::class,
                'modelClass' => DistributorContactsModel::class,
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'order_groups' => [
                'class' => HasManyField::class,
                'modelClass' => OrderGroupModel::class,
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'feed_fields' => [
                'class' => HasManyField::class,
                'modelClass' => DistributorFeedFieldModel::class,
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'reduce_extra_margin' => [
                'class' => BooleanCharField::class,
            ],
            'max_extra_margin' => [
                'class' => FloatField::class,
            ],
            'dx_leadtime' => [
                'class' => IntField::class,
            ],
            'dx_leadtime_to' => [
                'class' => IntField::class,
            ],
            'parents' => [
                'class' => HasManyField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['parent_manufacturer_id' => 'manufacturerid']
            ],
            'childs' => [
                'class' => HasManyField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturerid' => 'parent_manufacturer_id']
            ],
            'currency' => [
                'field' => 'd_currency',
                'class' => ForeignField::class,
                'modelClass' => CurrencyModel::class,
                'link' => ['d_currency' => 'currency_id'],
                'default' => 1
            ],
            'site' => [
                'field' => 'd_main_sf',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['d_main_sf' => 'storefrontid']
            ],
            'country_model' => [
                'field' => 'm_country',
                'class' => ForeignField::class,
                'sqlType' => Type::STRING,
                'modelClass' => CountryModel::class,
                'link' => ['m_country' => 'code']
            ],
            'state_model' => [
                'field' => 'm_state',
                'class' => ForeignField::class,
                'sqlType' => Type::STRING,
                'modelClass' => StateModel::class,
                'link' => [
                    'm_state' => 'code',
                    'm_country' => 'country_code'
                ],
            ],
            'provider_model' => [
                'field' => 'provider',
                'class' => ForeignField::class,
                'sqlType' => Type::STRING,
                'modelClass' => UserModel::class,
                'link' => ['provider' => 'login'],
            ],
            'products' => [
                'class' => HasManyField::class,
                'modelClass' => ProductModel::class,
                'link' => ['manufacturerid' => 'manufacturerid']
            ],
            'products_active' => [
                'class' => HasManyField::class,
                'modelClass' => ProductModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'extra' => ['forsale' => 'Y']
            ],
            'feed_I_D' => [
                'class' => HasManyField::class,
                'modelClass' => SupplierFeedModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'extra' => ['feed_type' => 'I', 'enabled__isnt' => 'Y'],
            ],
            'feed_I_E' => [
                'class' => HasManyField::class,
                'modelClass' => SupplierFeedModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'extra' => ['feed_type' => 'I', 'enabled' => 'Y'],
            ],
            'feed_P_D' => [
                'class' => HasManyField::class,
                'modelClass' => SupplierFeedModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'extra' => ['feed_type' => 'P', 'enabled__isnt' => 'Y'],
            ],
            'feed_P_E' => [
                'class' => HasManyField::class,
                'modelClass' => SupplierFeedModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
                'extra' => ['feed_type' => 'P', 'enabled' => 'Y'],
            ],

        ];
    }


    /**
     * @param ProductModel $modelProduct
     * @return float
     */
    public function calculatePrice($modelProduct)
    {
        $price = 0;
        if ($this->price_coef_z) {
            $price = max(round(($modelProduct->cost_to_us * $this->price_coef_x + $this->price_coef_y) / $this->price_coef_z, 2), $modelProduct->map_price);
        }
        return $price;
    }

    public function hasDefaultShippingZone(): bool
    {
        return ShippingRateModel::objects()
                ->filter([
                    'manufacturerid' => $this->manufacturerid,
                    'zoneid' => 0
                ])->count() > 0;
    }

    public function hasCanadaShippingZone(): bool
    {
        return ShippingRateModel::objects()
                ->filter([
                    'manufacturerid' => $this->manufacturerid,
                    'zoneid' => 12
                ])->count() > 0;
    }

    public function getDistributorTime(): DateTime
    {
        return (new DateTime())->setTimestamp(time() - $this->d_server_min_distributor_time * 60 * 60);
    }

    public function isGoodTimeToSendEmail(): bool
    {
        return WorkingTimeHelper::workingDayTime($this->getDistributorTime());
    }

    public function checkMinimalAmount($subtotal = 0): bool
    {
        return $this->getMinimalAmount() < $subtotal;
    }

    public function getMinimalAmount(): float
    {
        if ($this->d_minimum_order_amount === 'applies_to_all_orders' && $this->d_for_orders_below_min_order_amount === 'are_rejected' && $this->d_minimum_order_amount_in_us) {
            return (float)$this->d_minimum_order_amount_in_us;
        }

        return 0;
    }

    public function __toString()
    {
        return (string)$this->manufacturer;
    }

    public function getDefaultContact()
    {
        return $this->contacts_model->order(['distributor_field_code'])->limit(1)->get();
    }

    public function getPhone(): string
    {
        if ($contact = $this->getDefaultContact()) {
            return $contact->phone ?? '';
        }
        return '';
    }

    public function getPhoneNormalized(): string
    {
        $phone = $this->getPhone();

        if (strlen($phone_normalized = DistributorHelper::phoneNormalize($phone)) === 10){
            return $this->getPhonePrefix() . $phone_normalized;
        }
        return $phone;
    }

    public function getPhonePrefix(): string
    {
        switch($this->m_country) {
            case 'US':
            case 'CA':
                $prefix = '+1';
                break;
        }
        return $prefix ?? '';
    }

    public function getAdminUrl(): string
    {
        return "/admin/manufacturers.php?manufacturerid={$this->manufacturerid}";
    }

    public function isUserPriveded($login)
    {
        return $login === $this->provider;
    }
}