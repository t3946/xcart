<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class FraudCheckModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_fraud_check';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'question_template_body' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ]
        ];
    }
}