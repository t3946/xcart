<?php
namespace Modules\Cart\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Cart\Forms\CouponKitForm;
use Modules\Cart\Models\CouponKitModel;
use Xcart\App\Exceptions\HttpException;
use Xcart\App\Main\Xcart;

class CouponKitAdmin extends Admin
{
    public function getExcludedColumns()
    {
        return ['orders'];
    }

    public static function getName()
    {
        return 'Coupon KIT';
    }

    public function getForm()
    {
        return new CouponKitForm();
    }

    public function getModel()
    {
        return new CouponKitModel();
    }

    public function getAvailableListColumns()
    {
        return array_replace_recursive(parent::getAvailableListColumns(), [
            'active' => [
                'title' => 'Active',
                'template' => 'admin/list/columns/boolean.tpl',
                'order' => 'active'
            ],
        ]);
    }


    public function getQuerySet()
    {
        $qs = parent::getQuerySet();

        $qs->filter(['deleted' => false]);

        return $qs;
    }

    public function getModelOr404($pk)
    {
        $object = $this->getModel()->objectsAll()->filter(['pk' => $pk])->limit(1)->get();

        if (!$object) {
            throw new HttpException(404);
        }

        return $object;
    }

    public function getListItemActions()
    {
        $return = [];

        if (Xcart::app()->user->getIsSuperuser()) {
            $return[] = 'update';
        }
        $return[] = 'info';

        return $return;
    }
}