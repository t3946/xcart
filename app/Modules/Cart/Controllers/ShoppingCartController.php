<?php
//
//
//namespace Modules\Cart\Controllers;
//
//use Modules\Admin\Controllers\BackendController;
//use Modules\Cart\Models\CartModel;
//use Modules\Order\Models\OrderModel;
//use Xcart\App\Pagination\DataSource\QuerySetDataSource;
//use Xcart\App\Pagination\Pagination;
//
//class ShoppingCartController extends BackendController
//{
//    public function actionView() {
//
//        $qs = CartModel::objects()->order(['-id']);
//        $request = $this->getRequest();
//
//        if ($request->get->get('ShoppingCart_form')['id']) {
//            $qs->filter(['id' => $request->get->get('ShoppingCart_form')['id']]);
//        }
//
//        $pager = new Pagination($qs,['pageSize' => 25], new QuerySetDataSource());
//
//        foreach ($pager->paginate() as $model) {
//
//            $order_model = OrderModel::objects()->filter(['cart_number' => 8])->get();
//
//        }
//    }
//}



namespace Modules\Cart\Controllers;

use Modules\Admin\Controllers\BackendController;
use Modules\Cart\Forms\ShoppingCartForm;
use Modules\Cart\Models\CartModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class ShoppingCartController extends BackendController
{
    public function actionView() {

        $qs = CartModel::objects()->order(['-id']);
        $request = $this->getRequest();

        if (!empty($request->get->get('ShoppingCartForm')['id'])){
            $qs->filter(['id' => $request->get->get('ShoppingCartForm')['id'] ]);
        }

        $cart = Xcart::app()->getModule('Cart')->getComponent('cart');
        $s = $cart->getItemsGroupedBy();

//        $cart->getItemsGroupedBy();

//        $cart_models = CartModel::objects()->all();
//
//        foreach ($cart_models as $model){
//            $id = $model->id;
//            $data = $model->data;
//
//            foreach ($data['cart'] as $p) {
//                $pr = $p->_object->getPrice();
//                $p_model = ProductModel::objects()->get(['productid' => $p->_object->productid]);
//                $price = $p_model->getPrice();
//            }
//
//            $breakpoint = 1;
//        }

        $pager = new Pagination($qs,['pageSize' => 25], new QuerySetDataSource());

        $pageTitle = "Call records";

        $form = new ShoppingCartForm();

        Xcart::app()->breadcrumbs->add('Shopping cart');
        echo $this->renderInSmarty("admin/cart/all_carts.tpl", [
            'pager' => $pager,
            'page_title' => $pageTitle,
            'form' => $form,
        ]);

    }

}