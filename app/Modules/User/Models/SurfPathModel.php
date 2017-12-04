<?php

namespace Modules\User\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class SurfPathModel extends Model
{
    use AutoMetaTrait;

    const GOAL_TYPE_ADD_TO_CART      = 'A';
    const GOAL_TYPE_CHECKOUT         = 'K';
    const GOAL_TYPE_SEARCH           = 'S';
    const GOAL_TYPE_ORDER            = 'O';
    const GOAL_TYPE_ORDER_MESSAGE    = 'M';
    const GOAL_TYPE_REFERER          = 'R';
    const GOAL_TYPE_PRODUCT          = 'P';
    const GOAL_TYPE_BRAND            = 'B';
    const GOAL_TYPE_CATEGORY         = 'C';
    const GOAL_TYPE_STATIC_PAGE      = 'T';
    const GOAL_TYPE_HOME_PAGE        = 'H';
    const GOAL_TYPE_TECHNICAL_SEARCH = 'L';

    public $goals_arr
        = [
            self::GOAL_TYPE_ADD_TO_CART => "goal_addtocart",
            self::GOAL_TYPE_CHECKOUT    => "goal_checkout",
            self::GOAL_TYPE_SEARCH      => "goal_search",
            self::GOAL_TYPE_ORDER       => "goal_order",
        ];

    public static function tableName()
    {
        return 'xcart_cidev_surf_path';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'additional_data' => [
                'class' => TextField::className(),
                'default' => '',
                'null' => false
            ],
        ];
    }


}