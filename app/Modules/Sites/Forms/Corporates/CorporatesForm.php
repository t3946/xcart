<?php


namespace Modules\Sites\Forms\Corporates;


use Mindy\QueryBuilder\Q\QOr;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Sites\Models\CorporateModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class CorporatesForm extends ModelForm
{
    public function getFieldsets()
    {
        return [[
            'name',
            'country',
            'state',
            'registration_number',
            'incorporation_date',
            'storefronts',
        ]];
    }

    public static array $sections = [
        [
            'corporation' => [
                'title' => 'Corporation',
                'form' => CorporatesForm::class
            ],
            'shareholders' => [
                'title' => 'Shareholders',
                'form' => ShareholdersForm::class
            ],
            'addresses' => [
                'title' => 'Addresses',
                'form' => AddressesForm::class
            ],
            'incorporation_service_company' => [
                'title' => 'Incorporation service company',
                'form' => IncorporationForm::class
            ],
            'bank_accounts' => [
                'title' => 'Bank accounts',
                'form' => BankAccountsForm::class
            ],
            'merchant_accounts' => [
                'title' => 'Merchant accounts',
                'form' => MerchantAccountsForm::class
            ],
            'tax_authorities' => [
                'title' => 'Tax authorities',
                'form' => TaxAuthoritiesForm::class
            ],
            'tax_returns_outstanding' => [
                'title' => 'Tax returns outstanding',
                'form' => TaxReturnsOutstandingForm::class
            ],
            'accounting_service_company' => [
                'title' => 'Accounting service company',
                'form' => AccountingServiceFrom::class
            ],
        ]
    ];

    public function getModel()
    {
        return new CorporateModel;
    }

    public function getFields()
    {
        $entity = $this->getInstance();
        return [
            'country' => [
                'class' => DropDownField::class,
                'label' => 'Country',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () {
                    foreach (CountryModel::objects()->order(['name']) as $country) {
                        $result[$country->code] = (string)$country;
                    }
                    return $result ?? [];
                },
            ],
            'state' => [
                'class' => DropDownField::class,
                'label' => 'State/Province',
                'html' => ['style' => 'width:200px;'],
                'choices' => static function () use ($entity) {
                    foreach (StateModel::objects()->filter(['country_code__in' => [$entity->country ?? 'US']]) as $state) {
                        $result[$state->code] = "{$state->country_code}: {$state}";
                    }
                    return $result ?? [];
                },
            ],
            'registration_number' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;'],
            ],
            'incorporation_date' => [
                'class' => DateField::class,
                'html' => ['style' => 'width:100px;'],
            ],
            'storefronts' => [
                'class' => Select2Field::class,
                'choices' => static function () use($entity) {
                    $mass = [];
                    $filter = ['corporates__id__isnull' => true];
                    if ($entity && $entity->id) {
                        $filter['corporates__id'] = $entity->id;
                    }
                    /** @var SiteModel $model */
                    foreach (SiteModel::objects()->filter([new QOr($filter)])->order(['code']) as $model) {
                        if ($model->isWork()) {
                            $mass[$model->storefrontid] = (string)$model;
                        }
                    }
                    return $mass;
                },
                'html' => ['style' => 'width:300px;'],
            ]
        ];
    }

    public function getName()
    {
        return 'Corporation';
    }

}