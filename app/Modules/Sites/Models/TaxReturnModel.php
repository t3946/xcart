<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class TaxReturnModel extends Model
{
    public const TAX_TYPE_INCOME = 'Income tax returns';
    public const TAX_TYPE_SALES = 'Sales tax returns';
    public const TAX_TYPE_VAT = 'VAT returns';

    public static function getFields(): array
    {
        return [
            'id' => AutoField::class,
            'tax_type' => [
                'class' => CharField::class,
                'choices' => [
                    'Income' => self::TAX_TYPE_INCOME,
                    'Sales' => self::TAX_TYPE_SALES,
                    'VAT' => self::TAX_TYPE_VAT,
                ],
                'Tax'
            ],
            'from_date' => [
                'class' => DateField::class,
                'verboseName' => 'From'
            ],
            'to_date' => [
                'class' => DateField::class,
                'verboseName' => 'To'
            ],
            'status' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'choices' => [
                    'Filed' => 'Filed',
                ],
                'verboseName' => 'Tax return status',
            ],
            'corporate' => [
                'field' => 'corporate_id',
                'class' => ForeignField::class,
                'modelClass' => CorporateModel::class,
                'link' => ['corporate_id' => 'id']
            ]
        ];
    }

    public function __toString()
    {
        return (string)$this->getField('tax_type')->getValue();
    }
}