<?php


namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ListItemsModel;
use Modules\Account\Models\ProductListsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Orm\Base;
use Xcart\App\Main\Xcart;

class AccountListsApi extends FrontendController
{
    public function getLists()
    {
        $user = json_decode(file_get_contents('php://input'));

        $lists =  ProductListsModel::objects()->filter(['user_id' => $user])->all();

        $items = [];

        foreach ($lists as $key => $list)
        {
            $items[$key] = $list->getAttributes();
            $items[$key]['products'] = ListItemsModel::objects()->filter(['product_list_id' =>$list->product_list_id])->order(['order_by'])->all();

            foreach ( $items[$key]['products'] as $product_key => $product)
            {
                $items[$key]['products'][$product_key] = $product->getAttributes();
                $items[$key]['products'][$product_key]['product'] = $product->product->getAttributes();
            }

        }

        $this->jsonResponse($items);
    }

    public function createList()
    {
        $data = json_decode(file_get_contents('php://input'),true);
        $model = new ProductListsModel($data);
        $model->save();

        $this->jsonResponse($model->getAttributes());
    }

    public function reorderProducts()
    {
        $order_id = json_decode(file_get_contents('php://input'),true);

        foreach ($order_id as $key => $value)
        {
          $list_item =  ListItemsModel::objects()->get(['product_id' => $value]);

          $list_item->order_by = $key;

          $list_item->save();
        }

        $this->jsonResponse('success');
    }

    public function deleteList()
    {
        $list_id = json_decode(file_get_contents('php://input'));


        $user = Xcart::app()->auth->getUser(true);

       if(!$user)
       {
           $this->jsonResponse('user not login');
           return;
       }

       if(ProductListsModel::objects()->get([$user->user_id => 'user_id',$list_id => 'product_list_id' ])){

           dd(123);
           ProductListsModel::objects()->delete([$list_id => 'product_list_id' ]);
           $this->jsonResponse('delete successfully');
           return;
       }
        $this->jsonResponse('error');
    }
}