<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Admin\IncomeTaxReturnAdmin;
use Modules\Sites\Admin\SalesTaxReturnAdmin;
use Modules\Sites\Admin\TaxReturnAdmin;
use Modules\Sites\Admin\VatTaxReturnAdmin;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\Fields\Select2Field;

class TaxReturnsOutstandingForm extends CorporatesForm
{
    public array $exclude = ['storefronts', 'taxes'];

    public function getFieldsets()
    {
        return [
            'Income tax returns' => [
                'income_tax_period_starts_month',
                'income_period_duration',
                'income_tax_returns'

            ],
            'Sales tax returns' => [
                'sales_tax_period_starts_month',
                'sales_period_duration',
                'sales_tax_returns'
            ],
            'VAT returns' => [
                'vat_tax_period_starts_month',
                'vat_period_duration',
                'vat_tax_returns'
            ],
        ];
    }

    public function getFields()
    {
        return [
            'income_tax_period_starts_month' => [
                'class' => DropDownField::class,
                'choices' => self::getMonths(),
                'extend' => 'income_tax_period_starts_day',
                'label' => 'Income tax period starts'
            ],
            'income_tax_period_starts_day' => [
                'class' => DropDownField::class,
                'choices' => self::getDays()
            ],
            'sales_tax_period_starts_month' => [
                'class' => DropDownField::class,
                'choices' => self::getMonths(),
                'extend' => 'sales_tax_period_starts_day',
                'label' => 'Sales tax period starts'
            ],
            'sales_tax_period_starts_day' => [
                'class' => DropDownField::class,
                'choices' => self::getDays()
            ],
            'vat_tax_period_starts_month' => [
                'class' => DropDownField::class,
                'choices' => self::getMonths(),
                'extend' => 'sales_tax_period_starts_day',
                'label' => 'Sales tax period starts'
            ],
            'vat_tax_period_starts_day' => [
                'class' => DropDownField::class,
                'choices' => self::getDays()
            ],
            'income_tax_returns' => [
                'class' => ListViewField::class,
                'adminClass' => IncomeTaxReturnAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'label' => 'Tax return periods'
            ],
            'sales_tax_returns' => [
                'class' => ListViewField::class,
                'adminClass' => SalesTaxReturnAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'label' => 'Tax return periods'
            ],
            'vat_tax_returns' => [
                'class' => ListViewField::class,
                'adminClass' => VatTaxReturnAdmin::class,
                'listTemplate' => 'admin/list/_list.tpl',
                'label' => 'Tax return periods'
            ],
        ];
    }

    public function getName()
    {
        return 'Tax returns outstanding';
    }

    private static function getMonths(): array
    {
        foreach (range(1, 12, ) as $month) {
            $monthPadding = str_pad($month, 2, "0", STR_PAD_LEFT);
            $result[$month] = date('F', strtotime("2020-$monthPadding-01"));
        }
        return $result ?? [];
    }

    private static function getDays(): array
    {

        foreach (range(1, 31) as $day) {
            $result[$day] = str_pad($day, 2, "0", STR_PAD_LEFT);
        }
        return $result ?? [];

    }
}