<?php
namespace Modules\Payment\Models;

use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\CharField;

class PaymentProcessorModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_ccprocessors';
    }

    public static function getFields()
    {
        return [
            'module_name' => [
                'class' => CharField::className(),
                'primary' => true
            ],
        ];
    }
}