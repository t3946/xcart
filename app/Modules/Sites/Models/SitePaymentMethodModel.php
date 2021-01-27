<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class SitePaymentMethodModel extends Model
{
    public static function tableName(): string
    {
        return 'sites_payment_methods';
    }

    public static function getFields(): array
    {
        return [
            'site' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => [
                    'site_id' => 'storefrontid'
                ],
            ],
            'payment_method' => [
                'field' => 'payment_method_id',
                'class' => ForeignField::class,
                'modelClass' => PaymentMethodModel::class,
                'link' => [
                    'payment_method_id' => 'payment_method_id'
                ]
            ]
        ];
    }
}