<?php
namespace Modules\Cart\Forms\CouponRestrictions;


use Modules\Brand\Models\BrandModel;
use Modules\Cart\Admin\DiscountRestrictionAdmin;
use Modules\Cart\Discounts\Restrictions\BrandRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Xcart\App\Form\Fields\Select2Field;

class BrandRestrictionForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return BrandRestriction::className();
    }

    public function getFields()
    {
        $bid = $this->getDataValue('brand');
        $choices = [];

        if ($bid) {
            $choices[$bid] = (string)BrandModel::objects()->get(['pk' => $bid]);
        }

        return array_merge(parent::getFields(), [
            'brand' => [
                'class' => Select2Field::className(),
                'value' => $bid,
                'choices' => $choices,
                'ajaxUrl' => (new DiscountRestrictionAdmin)->getSuggestionUrl('brand'),
                'Brand'
            ],
        ]);
    }
}