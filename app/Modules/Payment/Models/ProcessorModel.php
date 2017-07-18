<?php

namespace Modules\Payment\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ProcessorModel extends Model
{
    public static function tableName()
    {
        return 'xcart_payment_processor';
    }

    public static function getFields()
    {
        return [
            'processor_id' => [
                'class' => AutoField::className(),
            ],
            'processor_name' => [
                'class' => CharField::className(),
                'default' => '',
                'null' => false
            ],
            'transaction_link' => [
                'class' => CharField::className(),
                'default' => '',
                'null' => false
            ],
            'cc_processor' => [
                'field' => 'processor_name',
                'class' => ForeignField::className(),
                'modelClass' => PaymentProcessorModel::className(),
                'link' => ['processor_name' => 'module_name'],
            ]

        ];
    }
}