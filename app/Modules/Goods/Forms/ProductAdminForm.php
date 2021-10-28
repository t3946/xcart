<?php

namespace Modules\Goods\Forms;


use DateTime;
use Modules\Goods\Admin\FilesProductAdmin;
use Modules\Goods\Admin\FilterProductAdmin;
use Modules\Goods\Admin\ProductImagesAdmin;
use Modules\Shipping\Models\ZoneModel;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Q\QOr;
use Modules\Editor\Fields\EditorField;
use Modules\Goods\Admin\ProductAdmin;
use Modules\Goods\Admin\ProductOptionsAdmin;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\Fields\UnixDateField;
use Xcart\App\Form\ModelForm;

class ProductAdminForm extends ModelForm
{

    public array $exclude = [
        'categories',
        'product_categories',
        'prices',
        'sites',
        'quick_prices',
        'clean_url',
        'order_details',
        'surf_path',
        'sf_moves',
        'images',
        'videos',
        'last_verify_date',
        'last_modify_user'
    ];

    public function getFieldsets()
    {
        return [
            'Operator and product availability' => [
                'user_modify',
                'forsale',
                'lock_forsale',
                'eta_date_mm_dd_yyyy',
            ],
            'Product details' => [
                'productcode',
                'upc',
                'product',
                'fulldescr',
                'descr',
                'lead_time_message',
                'supplier_internal_id',
            ],
            'Categorization' => [
                'distributor',
                'brand',
                'category',
            ],
            'SEO options' => [
                'prevent_search_indexing_this_product_page',
                'title_tag',
                'seo_product_name',
                'seo_h2',
                'seo_meta_descr',
                'seo_fulldescr',
            ],
            'Pricing' => [
                'list_price',
                'cost_to_us',
                'product_price_multiplier',
                'new_map_price',
                'map_price'
            ],
            'Shipping' => [
                'weight',
                'dim_x',
                'dim_y',
                'dim_z',
                'shipping_weight',
                'shipping_dim_x',
                'shipping_dim_y',
                'shipping_dim_z',
                'shipping_freight',
                'free_ship_zone',
                'free_ship_text',
            ],
            'Inventory' => [
                'r_avail',
                'low_avail_limit',
                'min_amount',
                'mult_order_quantity'
            ],
            'Images' => [
                'detail_images'
            ],
            'Product files' => [
                'files'
            ],
            'Product options' => [
                'product_options'
            ],
            'Product attributes' => [
                'filter_values'
            ],
            'Amazon options' => [
                'amazon_enabled'
            ],

        ];
    }

    public function getFields()
    {
        /** @var ProductModel $product */
        $product = $this->getInstance();
        $brand = $product->brand;
        $distributor = $product->distributor;
        $category = $product->getMainCategory();
        $user = $product->last_modify_user;
        $user_modified_login = $user->login ?? $product->provider;
        $modify_time = (new DateTime())->setTimestamp($product->mod_date)->format('d M Y H:s');
        return [
            'weight' => [
                'class' => CharField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extend' => 'weight_lock',
            ],
            'weight_lock' => [
                'class' => CheckboxField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'extendedBeforeText' => 'Locked by Product Manager',
                'extendedInputTemplate' => 'forms/field/checkbox/input.tpl'
            ],
            'dim_x' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 50px']
            ],
            'dim_y' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 50px'],
                'inputTemplate' => 'admin/extended_input.tpl',
                'extend' => 'dim_lock',
            ],
            'dim_lock' => [
                'class' => CheckboxField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'extendedBeforeText' => 'Locked by Product Manager',
                'extendedInputTemplate' => 'forms/field/checkbox/input.tpl'
            ],
            'dim_z' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 50px']
            ],
            'shipping_weight' => [
                'class' => CharField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extend' => 'shipping_weight_lock',
            ],
            'shipping_weight_lock' => [
                'class' => CheckboxField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'extendedBeforeText' => 'Locked by Product Manager',
                'extendedInputTemplate' => 'forms/field/checkbox/input.tpl'
            ],
            'shipping_dim_x' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 50px']
            ],
            'shipping_dim_y' => [
                'class' => CharField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'html' => ['style' => 'width: 50px'],
                'extend' => 'shipping_dim_lock',
            ],
            'shipping_dim_lock' => [
                'class' => CheckboxField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'extendedBeforeText' => 'Locked by Product Manager',
                'extendedInputTemplate' => 'forms/field/checkbox/input.tpl'
            ],
            'shipping_dim_z' => [
                'class' => CharField::class,
                'html' => ['style' => 'width: 50px']
            ],
            'shipping_freight' => [
                'class' => CharField::class
            ],
            'free_ship_zone' => [
                'class' => Select2Field::class,
                'choices' => function (): array {
                    $result = [-1 => 'No free shipping'];
                    foreach (ZoneModel::objects()->all() as $zone) {
                        $result[$zone->zoneid] = $zone->zone_name;
                    }
                    return $result ?? [];
                },
                'html' => [
                    'style' => 'width: 100%'
                ]
            ],
            'free_ship_text' => CharField::class,
            'productcode' => [
                'class' => CharField::class,
                'required' => true,
                'label' => 'SKU'
            ],
            'upc' => [
                'class' => CharField::class,
                'label' => 'UPC/EAN/ISBN'
            ],
            'product' => [
                'class' => CharField::class,
                'label' => 'Product name'
            ],
            'product_price_multiplier' => [
                'class' => CharField::class,
                'label' => 'Price multiplier'
            ],
            'fulldescr' => [
                'class' => EditorField::class,
                'label' => 'Detailed description',
                'required' => true,
                'readonly' => $this->getInstance()->pk && $this->getInstance()->distributor->feed_fields->filter(['field_name' => 'fulldescr', new QOr(['locked' => 'Y', 'admin_lock' => 'Y'])])->count() > 0,
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
            'seo_fulldescr' => [
                'class' => EditorField::class,
                'label' => 'SEO Detailed description',
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
            'descr' => [
                'class' => EditorField::class,
                'label' => 'Short description',
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
            'lead_time_message' => [
                'class' => CharField::class,
                'label' => 'Lead time message',
            ],
            'supplier_internal_id' => [
                'class' => CharField::class,
                'label' => 'Supplier internal id',
            ],
            'product_options' => [
                'class' => ListViewField::class,
                'adminClass' => ProductOptionsAdmin::class,
                'defaultOrder' => ['position']
            ],
            'filter_values' => [
                'class' => ListViewField::class,
                'adminClass' => FilterProductAdmin::class,
            ],
            'amazon_enabled' => [
                'class' => CheckboxField::class,
                'label' => 'Amazon enabled'
            ],
            'forsale' => [
                'class' => DropDownField::class,
                'choices' => [
                    'Y' => 'Available for sale',
                    'N' => 'Disabled',
                ],
                'label' => 'Availability',
            ],
            'lock_forsale' => [
                'class' => DropDownField::class,
                'choices' => [
                    'N' => 'Unlocked',
                    'Y' => 'Locked forever',
                ],
                'label' => "Lock 'Availability' status",
            ],
            'eta_date_mm_dd_yyyy' => [
                'class' => UnixDateField::class,
                'html' => [
                    'class' => "datepicker-here big",
                    'data-language' => "en",
                    'data-clear-button' => "1",
                    'data-date-format' => 'yyyy-mm-dd',
                    'autocomplete' => 'off',
                    'style' => 'width: 250px;'
                ],
                'inputTemplate' => 'admin/extended_input.tpl',
                'extend' => 'eta_date_lock',
            ],
            'eta_date_lock' => [
                'class' => CheckboxField::class,
                'inputTemplate' => 'admin/extended_input.tpl',
                'extendedBeforeText' => 'Lock until ETA date',
                'extendedInputTemplate' => 'forms/field/checkbox/input.tpl'
            ],
            'distributor' => [
                'class' => Select2Field::class,
                'choices' => $distributor ? [$distributor->manufacturerid => (string)$distributor] : [],
                'html' => [
                    'style' => 'width: 100%',
                    'data-url' => (new ProductAdmin())->getSuggestionUrl('distributor'),
                ],
            ],
            'brand' => [
                'class' => Select2Field::class,
                'choices' => $brand ? [$brand->brandid => (string)$brand] : [],
                'html' => [
                    'style' => 'width: 100%',
                    'data-url' => (new ProductAdmin())->getSuggestionUrl('brand'),
                ],
            ],
            'category' => [
                'class' => Select2Field::class,
                'value' => $category->categoryid ?? null,
                'choices' => $category
                    ? [
                        $category->categoryid => implode(
                            '/',
                            array_map(
                                static fn($a) => $a['name'],
                                $category->getBreadcrumbs()->get()
                            )
                        )]
                    : [],
                'html' => [
                    'style' => 'width: 100%',
                    'data-url' => (new ProductAdmin())->getSuggestionUrl('category'),
                ],
                'label' => 'Main category'
            ],
            'prevent_search_indexing_this_product_page' => [
                'class' => CheckboxField::class,
                'label' => 'Prevent search indexing this product page'
            ],
            'title_tag' => [
                'class' => CharField::class,
                'label' => htmlentities('Title (<title>)')
            ],
            'seo_product_name' => [
                'class' => CharField::class,
                'label' => htmlentities('SEO product name (<H1>)')
            ],
            'seo_h2' => [
                'class' => CharField::class,
                'label' => htmlentities('SEO (<H2>)')
            ],
            'seo_meta_descr' => [
                'class' => TextAreaField::class,
                'label' => "SEO meta 'Description'"
            ],
            'list_price' => [
                'class' => CharField::class,
                'label' => 'List price (US$)'
            ],
            'cost_to_us' => [
                'class' => CharField::class,
                'label' => 'Cost to us (US$)'
            ],
            'new_map_price' => [
                'class' => CharField::class,
                'label' => 'MAP price (US$)'
            ],
            'map_price' => [
                'class' => CharField::class,
                'label' => 'Bridge price (US$)'
            ],
            'r_avail' => [
                'class' => CharField::class,
                'label' => 'Quantity in stock (items) (real)'
            ],
            'low_avail_limit' => [
                'class' => CharField::class,
                'label' => 'Low limit in stock'
            ],
            'min_amount' => [
                'class' => CharField::class,
                'label' => 'Minimum order quantity'
            ],
            'mult_order_quantity' => [
                'class' => CheckboxField::class,
                'label' => 'Multiple order quantity'
            ],
            'detail_images' => [
                'class' => ListViewField::class,
                'adminClass' => ProductImagesAdmin::class,
                'defaultOrder' => ['products_images__order_by'],
                'label' => 'Detail images',
            ],
            'files' => [
                'class' => ListViewField::class,
                'adminClass' => FilesProductAdmin::class,
                'defaultOrder' => ['orderby'],
            ],
            'user_modify' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'border: none; width: 300px',
                    'readonly' => true,
                ],
                'label' => 'Added by',
                'value' => "($user_modified_login) on $modify_time",
            ],
        ];
    }

    public function getModel()
    {
        return new ProductModel();
    }

    public function setAttributes(array $data)
    {
        $data['eta_date_mm_dd_yyyy'] = strtotime($data['eta_date_mm_dd_yyyy']);
        return parent::setAttributes($data);

    }

    public function getBottomUrls(): array
    {
        return [[
            'url' => $this->getInstance()->getProductURLOnDistributorWebSite(),
            'anchor' => "Product on distributor's website: {$this->getInstance()->getMpn()}"
        ]];
    }

    public function beforeInstanceSave($instance)
    {
        $instance->last_modify_id = Xcart::app()->user->pk;
    }
}