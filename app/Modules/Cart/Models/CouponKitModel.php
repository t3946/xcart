<?php

namespace Modules\Cart\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

/**
 * Class CouponKitModel
 *
 * For constructing custom discount
 *
 * @package Modules\Cart\Models
 */
class CouponKitModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::className(),
            'code' => [
                'class' => CharField::className(),
                'required' => true,
            ],
            'name' => [
                'class' => CharField::className(),
                'required' => true,
            ],

            'type' => [
                'class' => IntField::className(),
                'required' => true,
                'choices' => [
                    1 => 'Discount summ.',
                    2 => 'Discount percentage.'
                ],
            ],

            'discount' => [
                'class' => DecimalField::className(),
                'required' => true,
            ],

            'max_discount' => [
                'class' => DecimalField::className(),
                'required' => true,
            ],

            'description' => [
                'class' => TextField::className(),
                'null' => true,
            ],

            'created_at' => [
                'class' => DateTimeField::className(),
                'autoNowAdd' => true,
            ],
            'updated_at' => [
                'class' => DateTimeField::className(),
                'autoNow' => true,
            ],

            'restrictions' => [
                'class' => HasManyField::className(),
                'modelClass' => CouponRestrictionModel::className(),
                'link' => ['id' => 'coupon_id'],
            ]
        ];
    }

    public function __toString()
    {
        return $this->name . " [{$this->code}]";
    }

    public function afterDelete($owner)
    {
        $owner->restrictions->delete();
    }
}