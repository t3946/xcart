<?php

namespace Modules\Payment\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;

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
        ];
    }

    public function getPaymentProcessor()
    {
        $this->
    }
}