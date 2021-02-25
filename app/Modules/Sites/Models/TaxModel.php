<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * @property string apply_to
 */
class TaxModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_taxes';
    }

    public static function getFields(): array
    {
        return [
            'taxid' => AutoField::class,
            'tax_name' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Tax service name'
            ],
            'regnumber' => [
                'class' => CharField::class,
                'null' => true,
                'default' => true,
                'verboseName' => 'Tax registration number'
            ],
            'apply_to' => [
                'class' => CharField::class,
                'choices' => [
                    'PS' => 'Product subtotal',
                    'SH' => 'Product subtotal + Shipping',
                ],
                'verboseName' => 'Apply tax to'
            ],
            'address_type' => [
                'class' => CharField::class,
                'choices' => [
                    'S' => 'Shipping address',
                    'B' => 'Billing address',
                ],
                'verboseName' => 'Rates depend on'
            ],
            'is_vat' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'VAT'
            ],
            'price_includes_tax' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'Included into the product price'
            ],

            'position' => [
                'class' => IntField::class,
                'default' => 0
            ],
            'active' => [
                'class' => BooleanField::class,
                'default' => false,
                'vrboseName' => 'Status'
            ],
            'rates' => [
                'class' => HasManyField::class,
                'modelClass' => TaxRatesModel::class,
                'link' => ['taxid' => 'taxid'],
            ],
            'sites' => [
                'class' => ManyToManyField::class,
                'modelClass' => SiteModel::class,
                'through' => SiteTaxModel::class,
            ],
        ];
    }

    public function __toString()
    {
        return (string)($this->tax_name ?: 'Tax');
    }
}