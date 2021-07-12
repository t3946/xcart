<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Fields\BooleanField;

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
            'opt_shop_closed' => [
                'class' => CheckboxField::class,
                'label' => 'Check this to close your shop temporarily',
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
            'Preferred_served_country' => [
                'class' => CharField::class,
                'label' => 'Preferred served country',
            ],
            'flat_shipping_enabled' => [
                'class' => CheckboxField::class,
                'label' => 'Flat Shipping',
            ],
            'lang_id' => [
                'class' => DropDownField::class,
                'label' => 'Preferred language',
            ],
/*            'file_edit_image_favicon' => [
                'class' => ImageField::class,
            ]*/
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