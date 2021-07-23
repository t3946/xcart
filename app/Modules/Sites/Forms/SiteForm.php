<?php

namespace Modules\Sites\Forms;

use Modules\Core\Models\CountryModel;
use Modules\Sites\Models\CurrencyModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class SiteForm extends ModelForm
{
    public array $exclude = [
        'images',
        'config',
        'list_config',
        'prefix',
        'choices',
        'orderby',
        'static_page',
        'marketplaces',
        'short_name',
    ];

    public function getFields()
    {
        return [
            'corporates' => [
                'class' => Select2Field::class,
                'label' => 'Corporations',
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'taxes' => [
                'class' => Select2Field::class,
                'label' => 'Taxes',
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'payment_methods' => [
                'class' => Select2Field::class,
                'label' => 'Payment methods',
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'shop_closed' => [
                'class' => CheckboxField::class,
                'label' => 'Check this to close your shop temporarily',
            ],
            'shop_closed_method' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'search_all_website_show' => [
                'class' => CheckboxField::class,
                'label' => "Search on s3stores.com must contain this SF's products",
            ],
            'Enable_CDN' => [
                'class' => CheckboxField::class,
                'label' => "Enable CDN",
            ],
            'Enable_surf_stats' => [
                'class' => CheckboxField::class,
                'label' => 'Enable surf stats',
            ],
            'country_code' => [
                'class' => Select2Field::class,
                'label' => 'Preferred served country',
                'choices' => function () {
                    foreach (CountryModel::objects() as $model) {
                        $result[$model->code] = "{$model->code}: {$model->name}";
                    }
                    return $result ?? [];
                },
                'inline_editor' => true,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'currency' => [
                'class' => Select2Field::class,
                'label' => 'Storefront currency',
                'choices' => (static function () {
                    foreach (CurrencyModel::objects() as $model) {
                        $res[$model->pk] = $model->currency_code;
                    }
                    return $res ?? [];
                })(),
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'flat_shipping_enabled' => [
                'class' => CheckboxField::class,
                'label' => 'Flat Shipping',
            ],
            'show_full_state_country' => [
                'class' => CheckboxField::class,
                'label' => 'Show full State & Country Name',
            ],
            'lang' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'file_edit_image_favicon' => [
                'class' => ImageField::class,
                'label' => 'Storefront favicon',
            ],
            'status' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'logo' => [
                'class' => ImageField::class,
            ]
        ];
    }

    public function getModel()
    {
        return new SiteModel();
    }

    public function getName()
    {
        return 'Edit Site';
    }
}