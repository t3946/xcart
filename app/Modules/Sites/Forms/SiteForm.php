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
                    'class' => 'select2-field',
                ],
            ],
            'taxes' => [
                'class' => Select2Field::class,
                'label' => 'Taxes',
                'multiple' => true,
                'html' => [
                    'class' => 'select2-field',
                ],
            ],
            'payment_methods' => [
                'class' => Select2Field::class,
                'label' => 'Payment methods',
                'multiple' => true,
                'html' => [
                    'class' => 'select2-field',
                ],
            ],
            'company_name' => [
                'class' => CharField::class,
                'label' => 'Company name',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'shop_closed' => [
                'class' => CheckboxField::class,
                'label' => 'Check this to close your shop temporarily',
            ],
            'shop_closed_method' => [
                'class' => Select2Field::class,
                'html' => [
                    'class' => 'select2-field'
                ],
            ],
            'company_website' => [
                'class' => CharField::class,
                'label' => 'Company website',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'cidev_top_header_code' => [
                'class' => CharField::class,
                'label' => 'Toll free customer service phone',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'local_phone' => [
                'class' => CharField::class,
                'label' => 'Local phone',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'fax_number' => [
                'class' => CharField::class,
                'label' => 'Fax number',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'cidev_header_code' => [
                'class' => CharField::class,
                'label' => 'Search string text',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'customer_service_working_time' => [
                'class' => CharField::class,
                'label' => 'Working time',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'opt_order_prefix' => [
                'class' => CharField::class,
                'label' => 'Order prefix',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'newsletter_email' => [
                'class' => CharField::class,
                'label' => 'Reply-To newsletter email address',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'start_year' => [
                'class' => CharField::class,
                'label' => 'Year when the store started its operation',
            ],
            'search_all_website_show' => [
                'class' => CheckboxField::class,
                'label' => "Search on s3stores.com must contain this SF's products",
            ],
            'Enable_CDN' => [
                'class' => CheckboxField::class,
                'label' => "Enable CDN",
            ],
            'CDN_domain' => [
                'class' => CharField::class,
                'label' => 'CDN domain',
                'html' => [
                    'class' => 'common-input'
                ]
            ],
            'Google_Trusted_Store_ID' => [
                'class' => CharField::class,
                'label' => 'Google Trusted Store ID',
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
                'html' => [
                    'class' => 'select2-field'
                ],
                'inline_editor' => true,
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
                    'class' => 'select2-field'
                ],
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
                'label' => 'Preferred language',
                'html' => [
                    'class' => 'select2-field'
                ],
            ],
            'file_edit_image_favicon' => [
                'class' => ImageField::class,
                'label' => 'Storefront favicon',
            ],
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