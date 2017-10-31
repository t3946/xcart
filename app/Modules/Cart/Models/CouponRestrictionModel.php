<?php
namespace Modules\Cart\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

class CouponRestrictionModel extends Model
{
    /** @var \Modules\Cart\Discounts\Restrictions\AbstractRestriction */
    private $restrict;

    public static function getFields()
    {
        return [
            'id' => AutoField::className(),
            'coupon' => [
                'class' => ForeignField::className(),
                'modelClass' => CouponKitModel::className(),
                'link' => ['coupon_id' => 'id'],
                'required' => true,
            ],
            'class' => [
                'class' => CharField::className(),
                'required' => false,
            ],
            'data' => [
                'class' => JsonField::className(),
                'required' => true,
                'default' => [],
            ]
        ];
    }

    public function getRestrict()
    {
        if (!$this->restrict) {
            if (!$this->getIsNewRecord()) {
                $this->restrict = new $this->class();
            }
        }

        $this->restrict->setData($this->data);

        return $this->restrict;
    }

    public function __toString()
    {
        if ($restrict = $this->getRestrict()) {
            return $restrict->getName() ." [{$restrict->toString()}]";
        }

        return parent::__toString();
    }
}