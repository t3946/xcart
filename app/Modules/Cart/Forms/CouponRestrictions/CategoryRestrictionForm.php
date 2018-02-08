<?php
namespace Modules\Cart\Forms\CouponRestrictions;


use Modules\Cart\Admin\DiscountRestrictionAdmin;
use Modules\Cart\Discounts\Restrictions\CategoryRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Form\Fields\Select2Field;

class CategoryRestrictionForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return CategoryRestriction::className();
    }

    public function getFields()
    {
        $bid = $this->getDataValue('category');
        $choices = [];

        if ($bid) {
            $choices[$bid] = (string)CategoryModel::objects()->get(['pk' => $bid]);
        }

        return array_merge(parent::getFields(), [
            'category' => [
                'class' => Select2Field::className(),
                'value' => $bid,
                'choices' => $choices,
                'ajaxUrl' => (new DiscountRestrictionAdmin)->getSuggestionUrl('category'),
                'label' => 'Category and subcategories',
            ],
        ]);
    }
}