<?php

namespace Modules\Payment\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class PaymentMethodModel extends Model
{
    use AutoMetaTrait;

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
            'processor' => [
                'field' => 'processor_id',
                'class' => ForeignField::className(),
                'modelClass' => ProcessorModel::className(),
                'link' => ['processor_id' => 'processor_id'],
            ],
            'cc_processor_models' => [
                'class' => HasManyField::className(),
                'modelClass' => PaymentProcessorModel::className(),
                'link' => ['paymentid' => 'paymentid'],
            ]
        ];
    }

}