<?php

namespace Modules\Cart\Models;

use Modules\Cart\Admin\CouponKitAdmin;
use Modules\Cart\Discounts\Restrictions\DefaultRestriction;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
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
 * @property (bool) active
 * @property (bool) deleted
 * @property (string) uid
 * @property (string) code
 * @property (string) name
 * @property (int) type [1 => percentage, 2 => summ]
 * @property (float) discount
 * @property (float) max_discount
 * @property (int) uses_per_user
 * @property (string) description
 * @property \DateTime created_at
 * @property \DateTime updated_at
 * @property CouponRestrictionModel[]|\Xcart\App\Orm\Manager|null restrictions
 *
 * @package Modules\Cart\Models
 */
class CouponKitModel extends Model
{
    public static function getDefaultRestrictions()
    {
        return [
            new DefaultRestriction(),
        ];
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::className(),
            'active' => [
                'class' => BooleanField::className(),
            ],
            'deleted' => [
                'class' => BooleanField::className(),
                'default' => false,
            ],
            'uid' => [
                'class' => CharField::className(),
                'required' => true,
                'default' => uniqid(),
            ],
            'code' => [
                'class' => CharField::className(),
                'required' => true,
                'unique' => true,
            ],
            'name' => [
                'class' => CharField::className(),
                'required' => true,
            ],

            'type' => [
                'class' => IntField::className(),
                'required' => true,
                'default' => 1,
                'choices' => [
                    1 => 'Discount percentage.',
                    2 => 'Discount summ.'
                ],
            ],

            'discount' => [
                'class' => DecimalField::className(),
                'required' => true,
            ],

            'max_discount' => [
                'class' => DecimalField::className(),
                'required' => true,
                'verboseName' => 'Max summ discount'
            ],

            'uses_per_user' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 1,
                'verboseName' => 'Max uses per user'
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
            ],

            'description' => [
                'class' => TextField::className(),
                'null' => true,
            ],
        ];
    }

    public function __toString()
    {
        return $this->name . " [{$this->code}]";
    }

    public function delete()
    {
        $this->deleted = true;

        return parent::save();
    }

    public function afterDelete($owner)
    {
        $owner->restrictions->delete();
    }

    public function getAbsoluteUrl()
    {
        return Xcart::app()->router->url('coupon:view', ['code' => $this->code]);
    }

    public function isPercentageCalc()
    {
        return $this->type == 1;
    }

    public function getAdmin()
    {
        return (new CouponKitAdmin())->setModel($this);
    }
}