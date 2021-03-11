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
                'verboseName' => 'Tax name'
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
                'verboseName' => 'Tax is applied to'
            ],
            'address_type' => [
                'class' => CharField::class,
                'choices' => [
                    'S' => 'Shipping address',
                    'B' => 'Billing address',
                ],
                'verboseName' => 'Tax is activated based on'
            ],
            'is_vat' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'Tax type'
            ],
            'price_includes_tax' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'Tax is included into the product price'
            ],
            'position' => [
                'class' => IntField::class,
                'default' => 0
            ],
            'rates' => [
                'class' => HasManyField::class,
                'modelClass' => TaxRatesModel::class,
                'link' => ['taxid' => 'taxid'],
                'verboseName' => 'Tax rates'
            ],
            'active' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'Activate tax'
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