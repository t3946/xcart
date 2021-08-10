<?php
namespace Modules\Goods\Forms;

use Modules\Brand\Models\BrandModel;
use Modules\Core\Models\LanguageModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\ModelForm;

class BrandForm extends ModelForm
{
    public array $exclude = ['user', 'parent'];
    public function getFieldsets()
    {
        return [[
            'brand',
            'url',
            'image',
            'leadtime_from',
            'descr',
            'link_to_us_url',
            'customer_service_name',
            'customer_service_phone',
            'disclaimer_text',
            'avail',
            'prevent_search_indexing_of_all_brand_products',
            'prevent_search_indexing_brand_page',
            'title',
            'product_brand_name',
            'SEO_brand_name_h1',
            'SEO_h2',
            'meta_descr',
            'markets_disabled'
        ]];
    }
    public function getFields()
    {
        return [
            'image' => [
                'class' => ImageField::class,
                'label' => 'Logo'
            ],
            'descr' => [
                'class' => TextAreaField::class,
                'label' => 'Description',
            ],
            'leadtime_from' => [
                'class' => CharField::class,
                'label' => 'Lead time (business days)',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px;'],
                'extends' => 'from',
                'extend' => 'leadtime_to',
            ],
            'leadtime_to' => [
                'class' => CharField::class,
                'label' => '',
                'inputTemplate' => 'admin/distributor/form/input.tpl',
                'html' => ['style' => 'width:50px'],
                'extends' => 'to',
            ],
            'prevent_search_indexing_of_all_brand_products' => [
                'class' => CheckboxField::class,
                'label' => 'Prevent search indexing of all brand products',
            ],
            'prevent_search_indexing_brand_page' => [
                'class' => CheckboxField::class,
                'label' => 'Prevent search indexing brand page',
            ],
            'markets_disabled' => [
                'class' => Select2Field::class,
                'label' => 'Forbidden API interactions',
                'html' => [
                    'style' => 'width:400px;',
                    'data-placeholder' => 'Click to select',
                ],
                'multiple' => true,
            ],
            'avail' => [
                'inline_editor' => true,
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 80px'
                ],
                'label' => 'Active'
            ]
        ];
    }
    public function getModel()
    {
        return new BrandModel();
    }
    public function getName()
    {
        return 'Edit brand';
    }
}