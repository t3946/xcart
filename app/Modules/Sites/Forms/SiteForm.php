<?php

namespace Modules\Sites\Forms;

use Exception;
use Modules\Core\Models\CountryModel;
use Modules\Goods\Models\CategoryModel;
use Modules\Sites\Admin\SiteAddressesAdmin;
use Modules\Sites\Admin\SitesAdmin;
use Modules\Sites\Admin\SitesMenuAdmin;
use Modules\Sites\Admin\SiteSocialsAdmin;
use Modules\Sites\Models\CurrencyModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\ListViewField;
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
        'products',
    ];

    /**
     * @throws Exception
     */
    public function getFields(): array
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
            'country' => [
                'class' => Select2Field::class,
                'label' => 'Preferred served country',
                'choices' => function () {
                    foreach (CountryModel::objects() as $model) {
                        $result[$model->code] = "$model->code: $model->name";
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
            ],
            'logo_mobile' => [
                'class' => ImageField::class,
            ],
            'base_category' => [
                'class' => Select2Field::class,
                'choices' => (function () {
                    $res[] = '';
                    if (!$this->getInstance()->pk) {
                        return $res;
                    }
                    foreach (CategoryModel::objects()->filter([
                        'storefrontid' => $this->getInstance()->pk,
                        'level' => 1
                    ]) as $cat) {
                        $res[$cat->pk] = (string) $cat;
                    }
                    return $res ?? [];
                }),
                'html' => [
                    'style' => 'width: 300px',
                    'data-url' => (new SitesAdmin())->getSuggestionUrl('category')."?site={$this->getInstance()->pk}",
                ],
            ],
            'addresses' => [
                'class' => ListViewField::class,
                'adminClass' => SiteAddressesAdmin::class,
            ],
            'menu_list' => [
                'class' => ListViewField::class,
                'adminClass' => SitesMenuAdmin::class
            ],
            'socials' => [
                'class' => ListViewField::class,
                'adminClass' => SiteSocialsAdmin::class,
                'defaultOrder' => ['order_by']
            ]
        ];
    }

    public function getModel(): SiteModel
    {
        return new SiteModel();
    }

    public function getName(): string
    {
        return 'Edit Site';
    }
}