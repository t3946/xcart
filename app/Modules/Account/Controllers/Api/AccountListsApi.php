<?php


namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ListIdeaModel;
use Modules\Account\Models\ListItemsModel;
use Modules\Account\Models\ProductListsModel;
use Modules\Account\Models\UserListModel;
use Modules\Core\Helpers\CoreHelper;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Orm\Base;
use Xcart\App\Main\Xcart;

class AccountListsApi extends FrontendController
{
    public function getLists()
    {
        $user = Xcart::app()->auth->getUser(true);

        $lists =  $user->lists->all();

        $items = [];


        foreach ($lists as $key => $list)
        {
            $items[$key] = $list->getAttributes();
            $items[$key]['products'] = $list->list_items->order(['order_by'])->all();


            foreach ( $items[$key]['products'] as $product_key => $product)
            {
                $items[$key]['products'][$product_key] = $product->getAttributes();
                if($product->product_type === 'product')
                {
                    $items[$key]['products'][$product_key]['product'] = ProductModel::objects()->get(['productid' => $product->product_id])->getAttributes();
                    $items[$key]['products'][$product_key]['image'] =  (string) ProductModel::objects()->get(['productid' => $product->product_id])->getMainImage();
                }
                else
                {
                    $items[$key]['products'][$product_key]['product'] = ListIdeaModel::objects()->get(['product_id' => $product->product_id])->getAttributes();
                }
            }
            $items[$key]['list_info'] = UserListModel::objects()->get(['product_list_id' =>$list->product_list_id, 'user_id' => $user->user_id])->getAttributes();
            $items[$key]['users'] = $list->user_list_roles->all();

            foreach ( $items[$key]['users']  as $user_key => $user_model)
            {
                $items[$key]['users'][$user_key] = $user_model->getAttributes();
                $items[$key]['users'][$user_key]['user'] = $user_model->user_model->getAttributes();
            }
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

        UserListModel::objects()->create(['user_id' => $user->user_id, 'product_list_id' => $model->product_list_id, ]);
        $response = $model->getAttributes();

        $response['list_info'] = UserListModel::objects()->get(['product_list_id' =>$model['product_list_id'], 'user_id' => $user->user_id])->getAttributes();

        $response['users'] = $model->user_list_roles->all();

        foreach ( $response['users']  as $user_key => $user)
        {
            $response['users'][$user_key] = $user->getAttributes();
            $response['users'][$user_key]['user'] = $user->user_model->getAttributes();
        }

        $this->jsonResponse($response);
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

        if($user->getIsGuest())
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
        [$list_id, $role] = array_values(json_decode(file_get_contents('php://input'),true));

        $user = Xcart::app()->auth->getUser(true);

        UserListModel::objects()->create(['user_id' => $user->user_id, 'product_list_id' => $list_id, 'role' => $role]);

        $this->jsonResponse('success');
    }

    public function editUsersInList()
    {
        [$list_id,$user_id,  $type ] = array_values(json_decode(file_get_contents('php://input'),true));

        $user = Xcart::app()->auth->getUser(true);

        if($user->getIsGuest()){
            $this->jsonResponse('user not login');
            return;
        }

        $edit_user_list = UserListModel::objects()->get(['user_id' => $user_id, 'product_list_id' => $list_id]);

        $request_user_role = UserListModel::objects()->get(['user_id' => $user->user_id, 'product_list_id' => $list_id]);


        if($request_user_role->role === 'edit' || $request_user_role->role === 'owner'){
          if($type === 'delete'){
              UserListModel::objects()->delete(['user_id' => $user_id, 'product_list_id' => $list_id]);
              $this->jsonResponse(['success delete']);
              return;
          }
          $edit_user_list->role = $type;
          $edit_user_list->save();
          $this->jsonResponse(['success']);
        }
    }

    public function addProductOnList()
    {
        $response_data = json_decode(file_get_contents('php://input'),true);

        if($response_data['productId']){
            ListItemsModel::objects()->create(['product_id' => $response_data['productId'],
                'product_list_id' => $response_data['listId'], 'product_type' => 'product']);
            $this->jsonResponse(['product added successfully']);
            return;
        }
        $idea_model = new ListIdeaModel(['name' => $response_data['name']]);
        $idea_model->save();
        $list_product_model = new ListItemsModel(['product_id' => $idea_model->product_id,
            'product_list_id' => $response_data['listId'], 'product_type' => 'idea']);
        $list_product_model->save();

        $response_data = $list_product_model->getAttributes();
        $response_data['product'] = ListIdeaModel::objects()->get(['product_id' => $list_product_model->product_id])->getAttributes();

        $this->jsonResponse($response_data);
    }

    public function editIdeaName()
    {
        [$idea_id, $new_name ] = array_values(json_decode(file_get_contents('php://input'),true));

        $idea_model = ListIdeaModel::objects()->get(['product_id' => $idea_id]);

        $idea_model->name = $new_name;

        $idea_model->save();

        $this->jsonResponse(['success edit']);
    }

    public function editComment()
    {
        [$product_id, $list_id, $data] = array_values(json_decode(file_get_contents('php://input'),true));

        $product_model = ListItemsModel::objects()->get(['product_id' => $product_id, 'product_list_id' => $list_id]);

        $product_model->setAttributes($data);
        $product_model->save();

        $this->jsonResponse(['success']);
    }

    public function manageList()
    {
        [$list_id, $data] = array_values(json_decode(file_get_contents('php://input'),true));

        $product_list_model = ProductListsModel::objects()->get(['product_list_id' => $list_id]);

        $product_list_model->setAttributes($data);
        $product_list_model->save();

        $this->jsonResponse(['success']);
    }

    public function deleteProduct()
    {
        [$list_id, $product_id] = array_values(json_decode(file_get_contents('php://input'),true));

        $product = ListItemsModel::objects()->get(['product_list_id' => $list_id, 'product_id' => $product_id]);

        if($product->product_type === 'idea')
        {
            ListIdeaModel::objects()->delete(['product_id' => $product_id]);
        }

        ListItemsModel::objects()->delete(['product_list_id' => $list_id, 'product_id' => $product_id]);

        $this->jsonResponse(['success']);
    }

    public function undoDeleteProduct()
    {
        [$product] = array_values(json_decode(file_get_contents('php://input'),true));

        if($product['product_type'] === 'idea')
        {
            ListIdeaModel::objects()->create($product['product']);
        }
        ListItemsModel::objects()->create($product);

        $this->jsonResponse(['success']);
    }
}


