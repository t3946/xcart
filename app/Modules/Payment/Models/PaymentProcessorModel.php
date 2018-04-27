<?php
namespace Modules\Payment\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class PaymentProcessorModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_ccprocessors';
    }

    public static function getFields()
    {
        return [
            'module_name' => [
                'class' => CharField::class,
            ],

        ];
    }
}