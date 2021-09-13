<?php


namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ListItemsModel;
use Modules\Account\Models\ProductListsModel;
use Modules\Account\Models\UserListModel;
use Modules\Core\Helpers\CoreHelper;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Orm\Base;
use Xcart\App\Main\Xcart;

class AccountListsApi extends FrontendController
{
    public function getLists()
    {
        $user = Xcart::app()->auth->getUser(true);

        $lists =  $user->lists;

        $items = [];

        foreach ($lists as $key => $list)
        {
            $items[$key] = $list->getAttributes();
            $items[$key]['products'] = ListItemsModel::objects()->filter(['product_list_id' =>$list->product_list_id])->order(['order_by'])->all();

            foreach ( $items[$key]['products'] as $product_key => $product)
            {
                $items[$key]['products'][$product_key] = $product->getAttributes();
                $items[$key]['products'][$product_key]['product'] = $product->product->getAttributes();
                $items[$key]['products'][$product_key]['image'] =  (string) $product->product->getMainImage();
            }
            $items[$key]['list_info'] = UserListModel::objects()->get(['product_list_id' =>$list->product_list_id, 'user_id' => $user->user_id])->getAttributes();

        }

        $this->jsonResponse($items);
    }

    public function createList()
    {
        $user = Xcart::app()->auth->getUser(true);

        if(!$user)
        {
            $this->jsonResponse('user not login');
            return;
        }

        $data = json_decode(file_get_contents('php://input'),true);
        $model = new ProductListsModel($data);
        $model->save();
        $model->cache_url = md5($model->product_list_id + $model->public);
        $model->save();

        UserListModel::objects()->create(['user_id' => $user->user_id, 'product_list_id' => $model->product_list_id]);
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
       if(UserListModel::objects()->get(['product_list_id' => $list_id, 'user_id' => $user->user_id])){
           ProductListsModel::objects()->delete(['product_list_id' => $list_id]);
           $this->jsonResponse('delete successfully');
           return;
       }

        $this->jsonResponse('deleting error');
    }

    public function moveProduct()
    {
        [$fromListId ,$toListId, $product] = array_values(json_decode(file_get_contents('php://input'),true));

        $user = Xcart::app()->auth->getUser(true);

        if(!$user)
        {
            $this->jsonResponse('user not login');
            return;
        }

        $listItem = ListItemsModel::objects()->get(['product_list_id' => $fromListId, 'product_id' => $product]);

        $listItem->list = $toListId;

        $listItem->save();

        $this->jsonResponse('success');
    }

    public function getUrlEncrypt()
    {
        [$private_type,$hash] = array_values(json_decode(file_get_contents('php://input'),true));
        $user = Xcart::app()->auth->getUser(true);

        $encrypt_params = CoreHelper::cipherText($user->user_id . '/' . $private_type .'/'. $hash);

        $this->jsonResponse($encrypt_params);
    }

    public function acceptInvitation()
    {
        [$list_id] = array_values(json_decode(file_get_contents('php://input'),true));

        $user = Xcart::app()->auth->getUser(true);

        UserListModel::objects()->create(['user_id' => $user->user_id, 'product_list_id' => $list_id]);

        $this->jsonResponse('success');
    }
}