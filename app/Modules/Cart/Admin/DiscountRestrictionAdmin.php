<?php
namespace Modules\Cart\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Brand\Models\BrandModel;
use Modules\Cart\Forms\CouponKitForm;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Modules\Cart\Forms\RestrictionDatesForm;
use Modules\Cart\Models\CouponKitModel;
use Modules\Cart\Models\CouponRestrictionModel;

class DiscountRestrictionAdmin extends ListViewAdmin
{
    public $ownerField = 'coupon';

    public static function getName()
    {
        return 'Discount Restriction';
    }

    public function getListColumns()
    {
        return ['(string)'];
    }

    public function getSuggestionColumns()
    {
        return [
            'brand' => [
                'class' => BrandModel::className(),
                'columns' => [
                    'brand', 'pk'
                ]
            ]
        ];
    }

    public function getForm()
    {
        $defClass = $this->getInstance()->getFormClass();

        if (!empty($_GET['form'])) {
//            try {
                $form = new $_GET['form'];
//            }
//            catch (\Exception $e) {
//                dd($e->getTraceAsString());

//                $form = new $defClass();
//            }

            return $form;
        }

        return new $defClass();
    }

    public static function getItemName()
    {
        return 'Restriction type';
    }

    public function getModel()
    {
        return new CouponRestrictionModel();
    }
}