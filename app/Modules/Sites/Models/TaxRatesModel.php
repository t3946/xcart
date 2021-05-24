<?php


namespace Modules\Sites\Models;


use Modules\Shipping\Models\ZoneElementModel;
use Modules\Shipping\Models\ZoneModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property TaxModel tax
 * @property string rate_type
 * @property float rate_value
 * @property integer taxid
 * @property integer rateid
 */
class TaxRatesModel extends Model
{
    public static function tableName(): string
    {
        return 'xcart_tax_rates';
    }

    public static function getFields(): array
    {
        return [
            'rateid' => AutoField::class,
            'tax' => [
                'field' => 'taxid',
                'class' => ForeignField::class,
                'modelClass' => TaxModel::class,
                'link' => ['taxid' => 'taxid'],
            ],
            'zone' => [
                'field' => 'zoneid',
                'class' => ForeignField::class,
                'modelClass' => ZoneModel::class,
                'link' => ['zoneid' => 'zoneid'],
            ],

            'rate_value' => [
                'class' => DecimalField::class,
                'requires' => true,
                'verboseName' => 'Tax rate'
            ],
            'rate_type' => [
                'class' => CharField::class,
                'choices' => [
                    '%' => '%',
                    '$' => '$',
                ],
            ],
        ];
    }

    public function __toString()
    {
        return $this->pk ? $this->tax . " ". $this->zone : 'Tax rate';
    }
}