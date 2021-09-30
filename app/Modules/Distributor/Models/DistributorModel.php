<?php

namespace Modules\Distributor\Models;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Distributor\Helpers\DistributorHelper;
use Modules\Forms\Models\TemplateModel;
use Modules\Goods\Models\ImageMModel;
use Modules\Goods\Models\ProductModel;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Marketplace\Models\ExternalMarketplaceDisabledDxModel;
use Modules\Marketplace\Models\ExternalMarketplaceDisabledModel;
use Modules\Marketplace\Models\ExternalMarketPlaceModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Modules\Sites\Models\CurrencyModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Helpers\PhoneHelper;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FloatField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Manufacturer;

/**
 * @property int manufacturerid
 * @property float price_coef_x
 * @property float price_coef_y
 * @property float price_coef_z
 * @property float d_minimum_order_amount_in_us
 * @property string d_minimum_order_amount
 * @property string code
 * @property string submit_to_operator
 * @property mixed currency
 * @property string d_contact_name_for_templates
 * @property TemplateModel request_avail_template
 * @property string d_send_to_email_14
 * @property DistributorTabModel[] tabs
 * @property ShippingRateModel[]|Manager shipping_rates
 * @property TemplateModel order_entry_template
 * @property TemplateModel order_submit_template
 * @property bool allow_dispatch_off_working_hours
 * @property DistributorContactsModel[]|Manager contacts_model
 * @property bool avail
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
        $alias = ExternalMarketplaceDisabledModel::objects()->getTableAlias();

        return [
            'manufacturerid' => [
                'class' => AutoField::className()
            ],
            'manufacturer' => [
                'class' => CharField::class
            ],
            'descr' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'url' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'code' => [
                'class' => CharField::class,
                'null' => false
            ],
            'manufact_text_displayed' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'shipping_last_update_date' => [
                'class' => DateField::class,
                'null' => true,
                'default' => null
            ],
            'mess_body' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'cart_manufact_text_displayed' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'd_instructions_to_order_entry_operator' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'request_avail_template' => [
                'field' => 'request_avail_template_id',
                'class' => ForeignField::class,
                'modelClass' => TemplateModel::class,
                'link' => ['request_avail_template_id' => 'id'],
                'null' => false,
                'default' => TemplateModel::REQUEST_AVAILABILITY_TEMPLATE_ID,
            ],
            'order_entry_template' => [
                'field' => 'order_entry_template_id',
                'class' => ForeignField::class,
                'modelClass' => TemplateModel::class,
                'link' => ['order_entry_template_id' => 'id'],
                'null' => false,
                'default' => TemplateModel::ORDER_ENTRY_TEMPLATE_ID,
            ],
            'order_submit_template' => [
                'field' => 'order_submit_template_id',
                'class' => ForeignField::class,
                'modelClass' => TemplateModel::class,
                'link' => ['order_submit_template_id' => 'id'],
                'null' => false,
                'default' => TemplateModel::DISPATCH_ORDER_TEMPLATE_ID,
            ],
            'order_entry_special_instructions' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'order_submit_special_instructions' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'd_email_subject_14' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'd_message_body_14' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'd_frontend_return_policy' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'd_distributor_return_policy' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'product_feeds_comments' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'd_dispatch_instructions' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'dx_paypal_account_email' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true,
                'verboseName' => 'Distributor PayPal account email'
            ],
            'dcad_swift' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'avail' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => true,
                'verboseName' => 'Activate distributor products'
            ],
            'd_availability_must_be_checked' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false
            ],
            'd_sec14_show_header' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => true
            ],
            'allow_dispatch_off_working_hours' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false
            ],
            'add_cost_to_us_column_to_dispatch_message' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false
            ],
            'd_sec14_show_items_stock' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => true
            ],
            'd_sec14_show_shipto' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => true
            ],
            'd_sec14_show_items_cost' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => true
            ],
            'd_sec14_show_footer' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => true
            ],
            'update_approximation_shipping_rates' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false
            ],
            'shipping_rates' => [
                'class' => HasManyField::class,
                'modelClass' => ShippingRateModel::class,
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
            'distributor_charges_for_each_order_twice_and_split_invoices' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'd_available_on_distributor_site_checkbox' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'd_sent_by_email_to' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'd_put_on_the_invoices' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'd_invoices_sent_by_email_to' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'd_invoices_sent_by_fax_to' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'd_invoices_mailed_to_our_checkbox' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'allow_pre_orders' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'calculate_shipping' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'products_always_verify' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'warehouse_pickups_are_allowed' => [
                'class' => BooleanCharField::class,
                'default' => false
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true
            ],
            'days_before_verify' => [
                'class' => IntField::class,
                'default' => 60
            ],
            'd_order_entry_operator_email' => [
                'class' => CharField::class,
                'default' => 'order.entry@s3stores.com',
                'verboseName' => 'Order entry operator email'
            ],
            'd_we_pay_to_distributor_by' => [
                'class' => CharField::class,
                'default' => 'credit_card',
                'choices' => [
                    'credit_card' => 'credit / debit card',
                    'paypal_balance' => 'PayPal balance',
                    'check' => 'check',
                ],
            ],
            'products_quantity_behavior' => [
                'class' => CharField::class,
                'default' => 'N',
                'choices' => [
                    'N' => 'do NOT display quantity',
                    'R' => 'display real quantity',
                    'D' => 'display quantity of',
                ],
            ],
            'd_bulk_or_individual_order_payments' => [
                'class' => CharField::class,
                'choices' => [
                    'distributor_charges_for_each_order_separately' => "distributor charges for each order separately",
                    'distributor_may_charge_for_several_orders_at_once' => 'distributor may charge for several orders at once',
                    'distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping' => 'distributor charges for each order twice: one charge for products and one charge for shipping',
                ],
                'default' => 'distributor_charges_for_each_order_separately'
            ],
            'd_questionable_1' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>narcotics, steroids,</b> certain controlled substances or other products that present a risk to consumer safety',
            ],
            'd_questionable_2' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>drug paraphernalia</b>',
            ],
            'd_questionable_3' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>cigarettes</b>',
            ],
            'd_questionable_4' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'the promotion of <b>hate, violence, racial or other forms of intolerance that is discriminatory</b> or the financial exploitation of a crime',
            ],
            'd_questionable_5' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'items that are considered <b>obscene</b>',
            ],
            'd_questionable_6' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'items that <b>infringe or violate any copyright, trademark,</b> right of publicity or privacy or any other proprietary right under the laws of any jurisdiction',
            ],
            'd_questionable_7' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>certain sexually oriented materials</b> or services',
            ],
            'd_questionable_8' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>ammunition, firearms, or certain firearm parts or accessories</b>',
            ],
            'd_questionable_9' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>certain weapons or knives</b> regulated under applicable law',
            ],
            'd_questionable_10' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>jewels, precious metals and stones</b>',
            ],
            'd_questionable_11' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>alcoholic beverages</b>',
            ],
            'd_questionable_12' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>non-cigarette tobacco products, e-cigarettes</b>',
            ],
            'd_questionable_13' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => '<b>prescription drugs/devices</b>',
            ],
            'max_extra_margin' => [
                'class' => FloatField::class,
                'default' => 0,
                'null' => false,
            ],
            'dx_leadtime' => [
                'class' => IntField::class,
                'default' => 0,
                'null' => false,
            ],
            'dx_leadtime_to' => [
                'class' => IntField::class,
                'default' => 0,
                'null' => false,
            ],
            'dx_eta_date' => [
                'class' => DateField::class,
                'null' => true,
                'default' => null
            ],
            'disabled_reason' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],
            'd_search_keyphrase_for_reconciliation' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
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
                'sqlType' => Types::STRING,
                'modelClass' => CountryModel::class,
                'link' => ['m_country' => 'code'],
                'default' => 'US'
            ],
            'state_model' => [
                'field' => 'm_state',
                'class' => ForeignField::class,
                'sqlType' => Types::STRING,
                'modelClass' => StateModel::class,
                'link' => [
                    'm_state' => 'code',
                    'm_country' => 'country_code'
                ],
                'default' => 'NY',
            ],
            'provider_model' => [
                'field' => 'provider',
                'class' => ForeignField::class,
                'sqlType' => Types::STRING,
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
            'feeds' => [
                'class' => HasManyField::class,
                'modelClass' => SupplierFeedModel::class,
                'link' => ['manufacturerid' => 'manufacturerid'],
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
            'markets_disabled' => [
                'class' => HasManyField::class,
                'modelClass' => ExternalMarketplaceDisabledModel::class,
                'link' => ['manufacturerid' => 'resource_id'],
                'extra' => ['resource_type' => 'D']
            ],
            'disabled_marketplaces' => [
                'class' => ManyToManyField::class,
                'modelClass' => ExternalMarketPlaceModel::class,
                'through' => ExternalMarketplaceDisabledDxModel::class,
                'extra' => ["{$alias}.resource_type" => 'D']
            ],
            'images' => [
                'class' => HasManyField::class,
                'modelClass' => ImageMModel::class,
                'link' => ['manufacturerid' => 'id'],
            ],
            'tabs' => [
                'class' => HasManyField::class,
                'modelClass' => DistributorTabModel::class,
                'link' => ['manufacturerid' => 'distributor_id'],
            ],
            'carriers' => [
                'class' => ManyToManyField::class,
                'modelClass' => TrackingLinksCarrierModel::class,
                'through' => DistributorCarrierModel::class,
            ],
            'sites' => [
                'class' => ManyToManyField::class,
                'modelClass' => SiteModel::class,
                'through' => DistributorSiteModel::class,
            ],
            'logo' => [
                'class' => ImageField::class,
                'adapterName' => 'www',
                'uploadTo' => 'images/M/',
                'null' => true,
            ],
            'taxes' => [
                'class' => HasManyField::class,
                'modelClass' => DistributorTaxModel::class,
                'link' => ['manufacturerid' => 'distributor_id']
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

    public function getShippingOnlyOneCountry()
    {
        if (($countries = $this->getShippingCountries()) && count($countries) === 1) {
            return reset($countries);
        }
        return null;
    }

    public function getShippingCountries()
    {
        $result = $r = [];
        foreach ($this->shipping_rates as $rate) {
            array_push($r,  ...$rate->zone_element_country->all());
        }
        if ($r) {
            foreach($r as $zone_element) {
                $result[] = $zone_element->field;
            }
        }
        return array_unique($result);
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
        return $this->getMinimalAmount() <= $subtotal;
    }

    public function getMinimalAmount(): float
    {
        if ($this->d_minimum_order_amount === 'applies_to_all_orders'
            && $this->d_for_orders_below_min_order_amount === 'are_rejected'
            && $this->d_minimum_order_amount_in_us) {
            return $this->d_minimum_order_amount_in_us;
        }

        return 0;
    }

    public function __toString()
    {
        return (string)$this->manufacturer;
    }

    public function getDefaultContact()
    {
        return $this->contacts_model->exclude(['phone' => ''])->order(['position'])->limit(1)->get();
    }

    public function getProductQuestionsContact()
    {
        return $this->contacts_model
            ->filter(['utility__utility_id' => DistributorUtilityModel::REQUEST_PRODUCT_QUESTIONS_UTILITY])->limit(1)->get();
    }

    public function getPhone(): string
    {
        if ($contact = $this->getDefaultContact()) {
            return $contact->phone ?? '';
        }
        return '';
    }

    public function getPhoneExt(): string
    {
        if ($contact = $this->getDefaultContact()) {
            return $contact->ext ?? '';
        }
        return '';
    }

    public function getPhoneNormalized(): string
    {
        return PhoneHelper::getPhoneNormalized($this->getPhone(), $this->m_country);
    }

    public function getAdminUrl($section = 1): string
    {
        if ($section !== null) {
            return Xcart::app()->router->url('admin:section', ['mid' => $this->manufacturerid, 'section' => $section]);
        }
        return '';
    }

    public function isUserPriveded($login)
    {
        return $login === $this->provider;
    }

    public function getAdminOrdersUrl(int $month): string
    {
        $url = Xcart::app()->router->url('dashboard:search');
        $time_for_request = urlencode(date('m/d/Y', time() - $month * 30 * 24 * 60 * 60) . ' - ' . date('m/d/Y'));
        return "{$url}?search[order][distributor][]={$this->manufacturerid}&search[order][date]={$time_for_request}";
    }

    public function getProhibitedProducts(): array
    {
        foreach (range(1, 9) as $idx) {
            $prop = "d_questionable_{$idx}";
            if ($this->$prop) {
                $res[] = $this->getField($prop)->getVerboseName();
            }
        }
        return $res ?? [];
    }

    public function getApprovalProducts(): array
    {
        foreach (range(10, 13) as $idx) {
            $prop = "d_questionable_{$idx}";
            if ($this->$prop) {
                $res[] = $this->getField($prop)->getVerboseName();
            }
        }
        return $res ?? [];
    }

    public function getContactNameForTemplates(): string
    {
        /** @var DistributorContactsModel $contact */
        $contact = $this->contacts_model
            ->filter(['utility__utility_id' => DistributorUtilityModel::ORDER_MESSAGE_UTILITY])
            ->order(['position'])
            ->limit(1)
            ->get();
        if ($contact && $contact->contact_name && $names_arrays = explode(' ', $contact->contact_name)) {
            $result = ucfirst(strtolower($names_arrays[0]));
        }
        return $result ?? 'Supplier';
    }

    public function afterSave($owner, $isNew)
    {
        if ($isNew) {
            DistributorTabModel::objects()->getOrCreate([
                'distributor_id' => $owner->pk,
                'position' => 10,
                'name' => 'Shipping',
                'content' => '{{distributor_shipped_from}}'
            ]);
            DistributorTabModel::objects()->getOrCreate([
                'distributor_id' => $owner->pk,
                'position' => 20,
                'name' => 'Our guarantee',
                'content' => "This product is brand new and includes the manufacturer's warranty, so you can buy with confidence."
            ]);
            ShippingRateModel::objects()->getOrCreate([
                'shippingid' => 1,
                'zoneid' => 11,
                'manufacturerid' => $owner->pk,
                'type' => 'R',
                'cost_marcup' => 1
            ]);
        }
    }
}