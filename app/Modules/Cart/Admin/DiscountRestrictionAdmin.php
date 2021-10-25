<?php
namespace Modules\Cart\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Brand\Models\BrandModel;
use Modules\Cart\Forms\CouponKitForm;
use Modules\Cart\Forms\DiscountRestrictionForm;
use Modules\Cart\Models\CouponKitModel;
use Modules\Cart\Models\CouponRestrictionModel;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Form\ModelForm;

class DiscountRestrictionAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'coupon';

    public static function getName(): string
    {
        return 'Discount Restriction';
    }

    public function getListColumns() : array
    {
        return ['(string)'];
    }

    public function getSuggestionColumns()
    {
        return [
            'brand' => [
                'class' => BrandModel::class,
                'columns' => [
                    'brand', 'pk'
                ],
                'filter' => [
                    'avail' => 'Y', 'parent__isnull' => true,
                ]
            ],
            'category' => [
                'class' => CategoryModel::class,
                'columns' => [
                    'category', 'pk'
                ],
                'filter' => [
                    'avail' => 'Y'
                ]
            ],
        ];
    }

    public function getForm() : ModelForm
    {
        $defClass = $this->getInstance()->getFormClass();

        if (!empty($_GET['form'])) {
            $form = new $_GET['form'];

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