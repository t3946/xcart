<?php


namespace Modules\Sites\Forms\Corporates;


use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\UrlField;

class TaxAuthoritiesForm extends CorporatesForm
{
    public array $exclude = ['storefronts', 'taxes'];

    public function getFieldsets()
    {
        return [
            'Federal tax authority' => [
                'federal_tax_id_name',
                'federal_tax_id',
                'federal_tax_year',
                'federal_tax_url',
                'federal_tax_login',
                'federal_tax_password',
            ],
            'State/Province tax authority' => [
                'state_tax_id_name',
                'state_tax_id',
                'state_tax_year',
                'state_tax_url',
                'state_tax_login',
                'state_tax_password'
            ],
        ];
    }

    public function getFields()
    {
        return [
            'federal_tax_year' => [
                'class' => DateField::class,
            ],
            'state_tax_year' => [
                'class' => DateField::class,
            ],
            'federal_tax_url' => [
                'class' => UrlField::class,
                'extend' => 'Login URL'
            ],
            'state_tax_url' => [
                'class' => UrlField::class,
                'extend' => 'Login URL'
            ],
        ];
    }

    public function getName()
    {
        return 'Tax authorities';
    }
}