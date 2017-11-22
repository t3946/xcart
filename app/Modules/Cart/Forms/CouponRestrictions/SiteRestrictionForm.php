<?php
namespace Modules\Cart\Forms\CouponRestrictions;


use Modules\Cart\Discounts\Restrictions\SiteRestriction;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;

class SiteRestrictionForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return SiteRestriction::className();
    }

    public function getFields()
    {
        return array_merge(parent::getFields(), [
            'not_in' => [
                'class' => CheckboxField::className(),
                'value' => $this->getDataValue('not_in', false),
                'label' => 'Not for selected'
            ],
            'site' => [
                'class' => DropDownField::className(),
                'value' => $this->getDataValue('site'),
                'choices' => function(){
                    $result = [];

                    foreach (SiteModel::objects()->all() as $site) {
                        $result[$site->pk] = (string)$site;
                    }

                    return $result;
                },
            ],
        ]);
    }
}