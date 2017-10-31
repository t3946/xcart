<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 31.10.17
 * Time: 17:57
 */

namespace Modules\Cart\Forms;


use Modules\Cart\Discounts\Restrictions\DateRestriction;
use Xcart\App\Form\Fields\DateField;

class RestrictionDatesForm extends DiscountRestrictionForm
{
    public static function getRestrictClass()
    {
        return DateRestriction::className();
    }

    public function getFields()
    {
        $data = $this->getInstance()->data;

        return array_merge(parent::getFields(), [
            'start' => [
                'class' => DateField::className(),
                'required' => true,
                'value' => empty($data['start']) ? '': $data['start'],
            ],
            'end' => [
                'class' => DateField::className(),
                'required' => true,
                'value' => empty($data['end']) ? '': $data['end'],
            ],
        ]);
    }
}