<?php

namespace Modules\Payment\Models;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class PaymentStorefrontModel extends Model
{
    public static function tableName()
    {
        return 'xcart_payment_methods_storefronts';
    }

    public static function getFields()
    {
        return [
            'site' => [
                'field' => 'storefrontid',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefrontid' => 'storefrontid'],
                'primary' => true,
                'null' => false,
            ],
            'payment_method' => [
                'field' => 'paymentid',
                'class' => ForeignField::class,
                'modelClass' => PaymentMethodModel::class,
                'link' => ['paymentid' => 'paymentid'],
                'primary' => true,
                'null' => false,
            ],
        ];
    }
}