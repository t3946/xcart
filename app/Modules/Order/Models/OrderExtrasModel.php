<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/22/2017
 * Time: 5:18 PM
 */

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class OrderExtrasModel extends Model
{
    public static function tableName()
    {
        return 'xcart_order_extras';
    }

    public static function getFields()
    {
        return [

            'orderid' => [
                'class' => IntField::className(),
                'primary' => true,
                'null' => false,
                'default' => 0
            ],

            'khash' => [
                'class' => CharField::className(),
                'primary' => true,
                'null' => false,
                'default' => ''
            ],

            'value' => [
                'class' => TextField::className(),
                'null' => true,
            ]

        ];
    }
}