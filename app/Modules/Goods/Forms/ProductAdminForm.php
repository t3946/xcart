<?php

namespace Modules\Goods\Forms;


use Mindy\QueryBuilder\Q\QOr;
use Modules\Brand\Models\BrandModel;
use Modules\Editor\Fields\EditorField;
use Modules\Goods\Admin\ProductAdmin;
use Modules\Goods\Admin\ProductOptionsAdmin;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\Fields\TimeStampField;
use Xcart\App\Form\Fields\UnixDateField;
use Xcart\App\Form\ModelForm;

class ProductAdminForm extends ModelForm
{

    public $exclude = [
        'categories',
        'product_categories',
        'prices',
        'sites',
        'quick_prices',
        'clean_url',
        'order_details',
        'surf_path',
        'sf_moves',
        'filter_values',
        'images',
        'videos',
    ];

    public function getFieldsets()
    {
        return [
            'Product details' => [
                'productcode',
                'upc',
                'product',
                'fulldescr',
                'descr',
                'lead_time_message',
                'supplier_internal_id',
            ],
            'Operator and product availability' => [
                'forsale',
                'lock_forsale',
                'eta_date_mm_dd_yyyy',
            ],
            'Categorization' => [
                'distributor',
                'brand',
                'category',
                'main_category_id',
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
                'new_map_price',
                'map_price'
            ],
            'Inventory' => [
                'r_avail',
                'low_avail_limit',
                'min_amount',
                'mult_order_quantity'
            ],
            'Product options' => [
                'product_options'
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

        return [
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
                'required' => true,
                'label' => 'Product name'
            ],
            'fulldescr' => [
                'class' => EditorField::class,
                'label' => 'Detailed description',
                'required' => true,
                'readonly' => $this->getInstance()->distributor->feed_fields->filter(['field_name' => 'fulldescr', new QOr(['locked' => 'Y', 'admin_lock' => 'Y'])])->count() > 0
            ],
            'seo_fulldescr' => [
                'class' => EditorField::class,
                'label' => 'SEO Detailed description',
            ],
            'descr' => [
                'class' => EditorField::class,
                'label' => 'Short description',
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
                'listTemplate' => 'admin/list/_list.tpl'
                /*'defaultOrder' => [
                    'class'
                ]*/
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
            ],
            'distributor' => [
                'class' => Select2Field::class,
                'choices' => $distributor ? [$distributor->manufacturerid => (string)$distributor] : [],
                'ajaxUrl' => (new ProductAdmin)->getSuggestionUrl('distributor'),
            ],
            'brand' => [
                'class' => Select2Field::class,
                'choices' => $brand ? [$brand->brandid => (string)$brand] : [],
                'ajaxUrl' => (new ProductAdmin)->getSuggestionUrl('brand'),
            ],
            'category' => [
                'class' => Select2Field::class,
                'value' => $category ? $category->categoryid : null,
                'choices' => $category ? [$category->categoryid => (string)implode('/', array_map(function ($a) {
                    return $a['name'];
                }, $category->getBreadcrumbs()->get()))] : [],
                'ajaxUrl' => (new ProductAdmin)->getSuggestionUrl('category'),
                'label' => 'Main category'
            ],
            'main_category_id' => [
                'class' => CharField::class,
                'label' => 'Main category ID',
                'value' => $category ? $category->categoryid : null,
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


}