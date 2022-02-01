<?php


namespace Modules\Account\Controllers\Api;

use Exception;
use Modules\Account\Models\ListIdeaModel;
use Modules\Account\Models\ListItemsModel;
use Modules\Account\Models\ProductListsModel;
use Modules\Account\Models\UserListModel;
use Modules\Core\Helpers\CoreHelper;
use Modules\User\Models\UserAccount\UserModel;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class AccountListsApi extends Controller
{
    public static function getLists(UserModel $user): array
    {
        $lists = $user->lists->all();
        /** @var ProductListsModel $list_product_model */
        foreach ($lists as $list_product_model) {
            /** @var UserListModel $list_user_model */
            $list_user_model = $list_product_model->user_list_roles->get(['user_id' => $user->pk]);

            $ar_list[] = array_merge($list_product_model->getFrontendData(), [
                'listType' => $list_user_model->list_type,
                'role' => $list_user_model->role,
                'source' => $list_user_model->source,
            ]);
        }

        return $ar_list ?? [];
    }

    public function getListByCache(string $cache)
    {
        /** @var ProductListsModel $list_product_model */
        if ($list_product_model = ProductListsModel::objects()->get(['cache_url' => $cache])) {
            $data = $list_product_model->getFrontendData();
        }
        $this->jsonResponse($data ?? null);
    }

    /**
     * @throws Exception
     */
    public function createList()
    {
        if (!$user = $this->checkRightsUser()) {
            return;
        }

        $form = json_decode(file_get_contents('php://input'), true);
        $model = new ProductListsModel($form);
        $model->save();
        $model->cache_url = md5($model->product_list_id + $model->public);
        $model->save();

        $user_list = new UserListModel(['user_id' => $user->user_id, 'product_list_id' => $model->product_list_id]);
        $user_list->save();
        $ar_data = array_merge($model->getFrontendData(), [
            'listType' => $user_list->list_type,
            'role' => $user_list->role,
        ]);

        $this->jsonResponse($ar_data);
    }

    public function reorderProducts()
    {
        if (!$this->checkRightsUser()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);

        foreach ($data['productIds'] as $key => $list_items_id) {
            /** @var ListItemsModel $list_item */
            $list_item = ListItemsModel::objects()->get(['list_items_id' => $list_items_id]);
            $list_item->order_by = $key;
            $list_item->save();
        }

        $this->jsonResponse([]);
    }

    public function deleteList(int $list_id)
    {
        /** @var UserModel $user */
        if (!$user = $this->checkRightsUser()) {
            return;
        }
        /** @var UserListModel $list */
        if ($list = UserListModel::objects()->get(['product_list_id' => $list_id, 'user_id' => $user->user_id])) {
            ProductListsModel::objects()->delete(['product_list_id' => $list_id]);
            $this->jsonResponse(['Delete successfully']);
        }
        $this->jsonResponse(['Deleting error']);
    }

    public function transferProduct()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        /** @var UserModel $user */
        if (!$this->checkRightsUser()) {
            return;
        }
        /** @var ListItemsModel $listItem */
        $listItem = ListItemsModel::objects()->get(['product_list_id' => $form['fromListId'], 'product_id' => $form['productId']]);
        $listItem->product_list_id = $form['toListId'];
        $listItem->save();

        $this->jsonResponse(['success']);
    }

    public function getUrlEncrypt()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        /** @var UserModel $user */
        if (!$user = $this->checkRightsUser()) {
            return;
        }

        $encrypt_params = CoreHelper::cipherText($user->user_id . '/' . $form['privateType'] . '/' . $form['hash']);
        foreach ($encrypt_params as $key => $param) {
            $encrypt_params[$key] = urlencode($param);
        }
        $this->jsonResponse($encrypt_params);
    }

    public function acceptInvitation()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        /** @var UserModel $user */
        if (!$user = $this->checkRightsUser()) {
            return;
        }

        /** @var UserListModel $sharedModel */
        $sharedModel = UserListModel::objects()->get(['product_list_id' => $form['listId']]);
        $sharedModel->list_type = "shared";
        $sharedModel->save();

        UserListModel::objects()->create([
            'user_id' => $user->pk,
            'product_list_id' => $form['listId'],
            'role' => $form['role'],
            'source' => UserListModel::SOURCE_CREATE_SIMPLE
        ]);

        $this->jsonResponse([]);
    }

    public function editUsersInList()
    {
        [$list_id, $user_id, $type] = array_values(json_decode(file_get_contents('php://input'), true));

        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            $this->jsonResponse('user not login');
            return;
        }

        $edit_user_list = UserListModel::objects()->get(['user_id' => $user_id, 'product_list_id' => $list_id]);

        $request_user_role = UserListModel::objects()->get(['user_id' => $user->user_id, 'product_list_id' => $list_id]);


        if ($request_user_role->role === 'edit' || $request_user_role->role === 'owner') {
            if ($type === 'delete') {
                UserListModel::objects()->delete(['user_id' => $user_id, 'product_list_id' => $list_id]);
                $this->jsonResponse(['success delete']);
                return;
            }
            $edit_user_list->role = $type;
            $edit_user_list->save();
            $this->jsonResponse(['success']);
        }
    }

    /**
     * @throws Exception
     */
    public function addProductOnList()
    {
        if (!$this->checkRightsUser()) {
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data['productId']) {
            $list_product_model = new ListItemsModel([
                'product_id' => $data['productId'],
                'product_list_id' => $data['listId'],
                'product_type' => 'product'
            ]);
            $list_product_model->save();
            $this->jsonResponse([]);
            return;
        }
        $idea_model = new ListIdeaModel(['name' => $data['name']]);
        $idea_model->save();
        $list_product_model = new ListItemsModel([
            'product_id' => $idea_model->product_id,
            'product_list_id' => $data['listId'],
            'product_type' => 'idea'
        ]);
        $list_product_model->save();

        $this->jsonResponse($list_product_model->getFrontendData());
    }

    public function editIdeaName()
    {
        [$idea_id, $new_name] = array_values(json_decode(file_get_contents('php://input'), true));

        $idea_model = ListIdeaModel::objects()->get(['product_id' => $idea_id]);

        $idea_model->name = $new_name;

        $idea_model->save();

        $this->jsonResponse(['success edit']);
    }

    /**
     * @throws Exception
     */
    public function editComment()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        if (!$this->checkRightsUser()) {
            return;
        }
        /** @var ListItemsModel $list_item */
        if (!$list_item = ListItemsModel::objects()->get(['product_id' => $form['productId'], 'product_list_id' => $form['productListId']])) {
            $this->jsonResponse(['Not found list item'], 404);
            return;
        }
        $list_item->setAttributes($form['data']);
        $list_item->save();
        $this->jsonResponse(['success']);
    }

    /**
     * @throws Exception
     */
    public function manageList()
    {
        $form = json_decode(file_get_contents('php://input'), true);
        /** @var UserModel $user */
        if (!$this->checkRightsUser()) {
            return;
        }
        /** @var ProductListsModel $product_list_model */
        if (!$product_list_model = ProductListsModel::objects()->get(['product_list_id' => $form['productListId']])) {
            $this->jsonResponse(['Not found product list'], 404);
            return;
        }

        $product_list_model->setAttributes($form['data']);
        $product_list_model->save();
        $this->jsonResponse([]);
    }

    public function deleteProduct()
    {
        if (!$this->checkRightsUser()) {
            return;
        }
        $form = json_decode(file_get_contents('php://input'), true);
        $attr_form = ['list_items_id' => $form['list_items_id']];
        /** @var ListItemsModel $list_item */
        if ($list_item = ListItemsModel::objects()->get($attr_form)) {
            if ($list_item->product_type === ListItemsModel::TYPE_IDEA) {
                ListIdeaModel::objects()->delete(['product_id' => $list_item->product_id]);
            }
            ListItemsModel::objects()->delete($attr_form);
            $this->jsonResponse(['Success']);
            return;
        }
        $this->jsonResponse(['Error delete']);
    }

    public function undoDeleteProduct()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            if (!$this->checkRightsUser()) {
                return;
            }
            $base_item_attr = [
                'product_list_id' => $data['productListId'],
                'comment' => $data['comment'],
                'priority' => $data['priority'],
                'needs' => $data['needs'],
                'has' => $data['has'],
                'order_by' => $data['orderBy']
            ];
            switch ($data['productType']) {
                case ListItemsModel::TYPE_IDEA:
                    $idea = new ListIdeaModel();
                    $idea->name = $data['product']['name'];
                    $idea->save();
                    $base_item_attr = array_merge($base_item_attr, [
                        'product_id' => $idea->pk,
                        'product_type' => ListItemsModel::TYPE_IDEA
                    ]);
                    break;
                case ListItemsModel::TYPE_PRODUCT:
                    $base_item_attr = array_merge($base_item_attr, [
                        'product_id' => $data['productId'],
                        'product_type' => ListItemsModel::TYPE_PRODUCT
                    ]);
                    break;
            }
            $item_model = new ListItemsModel();
            $item_model->setAttributes($base_item_attr);
            $item_model->save();
            $this->jsonResponse([]);
        } catch (Throwable $exception) {
            // TODO: Добавить обработку ошибок http статусов на фронте
            $this->jsonResponse([], 400);
        }
    }

    public function actionGetLists(): void
    {
        $user = Xcart::app()->auth->getUser(true);
        $lists = $this->getLists($user);
        $this->jsonResponse($lists);
    }

    public function listInvite(string $tag, string $code)
    {
        if (!$user = $this->checkRightsUser()) {
            return;
        }
        [$user_id, $type, $listHash] = explode('/', CoreHelper::decryptText($code, $tag));
        /** @var ProductListsModel $invite_list */
        if (!$invite_list = ProductListsModel::objects()->get(['cache_url' => $listHash])) {
            $this->jsonResponse(['Not found list invite', 404]);
            return;
        }
        /** @var UserListModel $invite */
        if ($invite = UserListModel::objects()->get(['user_id' => $user->pk, 'product_list_id' => $invite_list->product_list_id])) {
            $this->jsonResponse(['cache' => $invite->list_model->cache_url], 208);
            return;
        }
        /** @var UserModel $invited_user */
        $invited_user = UserModel::objects()->get(['pk' => $user_id]);
        $this->jsonResponse([
            'inviteUser' => $invited_user->name,
            'type' => $type,
            'listData' => [
                'productListId' => $invite_list->product_list_id,
                'name' => $invite_list->name,
                'cacheUrl' => $invite_list->cache_url,
            ]
        ]);
    }

    private function checkRightsUser()
    {
        /** @var UserModel $user */
        if (!$user = Xcart::app()->auth->getUser(true)) {
            $this->jsonResponse(['message' => 'Not found user'], 401);
            return false;
        }
        return $user;
    }
}


