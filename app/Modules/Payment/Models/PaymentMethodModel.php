<?php

namespace Modules\Payment\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;

class PaymentMethodModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_payment_methods';
    }

    public static function getFields()
    {
        return [
            'paymentid' => [
                'class' => AutoField::className()
            ],
            'processor_models' => [
                'class' => HasManyField::className(),
                'modelClass' => PaymentProcessorModel::className(),
                'link' => ['paymentid' => 'paymentid'],
            ]
        ];
    }

}