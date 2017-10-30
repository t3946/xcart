<?php
namespace Modules\Cart\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

class CouponRestrictionModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::className(),
            'coupon' => [
                'class' => ForeignField::className(),
                'modelClass' => CouponKitModel::className(),
                'link' => ['coupon_id' => 'id'],
            ],
            'data' => [
                'class' => JsonField::className(),
                'required' => true,
            ]
        ];
    }
}