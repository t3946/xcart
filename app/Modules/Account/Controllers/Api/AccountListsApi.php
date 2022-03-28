<?php


namespace Modules\Account\Controllers\Api;

use Exception;
use Modules\Account\Models\ListIdeaModel;
use Modules\Account\Models\ListItemsModel;
use Modules\Account\Models\ProductListsModel;
use Modules\Account\Models\ProductListsUserRoles;
use Modules\Core\Helpers\CoreHelper;
use Modules\User\Models\UserAccount\UserModel;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class AccountListsApi extends Controller
{
    /** 
     * check user $user can edit list $list
     */
    private static function canEdit($list, $user): bool {
        if ($user->user_id !== $list->user_id) {
            /* @var $role ProductListsUserRoles */
            $role = ProductListsUserRoles::objects()->get(['product_list_id' => $list->product_list_id, 'user_id' => $user->user_id]);

            if ($role->role !== 'editor') {
                return false;
            }
        }

        return true;
    }

    public function getListByCache(string $cache)
    {
        /** @var ProductListsModel $list_product_model */
        $list = ProductListsModel::objects()->get(['cache_url' => $cache]);

        // list not found
        if (!$list) {
            http_response_code(400);
            return;
        }

        $list_data = $list->getFrontendData();
        $this->jsonResponse($list_data ?? null);
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
        $form['user_id'] = $user->user_id;
        $model = new ProductListsModel($form);
        $model->save();
        $model->cache_url = md5($model->product_list_id + $model->public);
        $model->save();

        $ar_data = $model->getFrontendData();

        $this->jsonResponse($ar_data);
    }

    public function reorderProducts()
    {
        $request = $this->getRequest();

        if (!$request->getIsAjax()) {
            $this->error(404);
        }

        $user = Xcart::app()->auth->getUser(true);
        $data = json_decode(file_get_contents('php://input'), true);
        $list = ProductListsModel::objects()->get(['product_list_id' => $data['productListId']]);

        // check list exists
        if (!$list) {
            http_response_code(400);
            return;
        }

        if (!self::canEdit($list, $user)) {
            http_response_code(400);
            return;
        }

        // check products consistence
        $list_items = ListItemsModel::objects()->all(['product_list_id' => $list->product_list_id]);

        if (count($data['productIds']) !== count($list_items)) {
            http_response_code(400);
            return;
        }

        /* @var $list_item ListItemsModel */
        foreach ($list_items as $_ => $list_item) {
            //unexpected product
            if (in_array($list_item->list_items_id, $data['productIds']) === false) {
                http_response_code(400);
                return;
            }
        }

        foreach ($data['productIds'] as $index => $list_items_id) {
            /** @var ListItemsModel $list_item */
            $list_item = ListItemsModel::objects()->get(['list_items_id' => $list_items_id]);
            $list_item->order_by = $index;
            $list_item->save();
        }

        $this->jsonResponse([]);
    }

    public function deleteList(int $list_id)
    {
        /* @var $list ProductListsModel */
        $list = ProductListsModel::objects()->get(['product_list_id' => $list_id]);
        $user = Xcart::app()->getUser(true);
        /* @var $role ProductListsUserRoles */
        $role = ProductListsUserRoles::objects()->get(['product_list_id' => $list->product_list_id, 'user_id' => $user->user_id]);

        //user is owner
        if ($user->user_id === $list->user_id) {
            $list->delete();
        } elseif ($role) {
            $role->delete();
        }

        $this->jsonResponse(['Delete successfully']);
    }

    public function transferProduct()
    {
        $user = Xcart::app()->auth->getUser(true);
        $form = json_decode(file_get_contents('php://input'), true);
        $list_1 = ProductListsModel::objects()->get(['product_list_id' => $form['fromListId']]);
        $list_2 = ProductListsModel::objects()->get(['product_list_id' => $form['toListId']]);

        // user have not permissions
        if (!self::canEdit($list_1, $user) || !self::canEdit($list_2, $user)) {
            http_response_code(400);
            return;
        }
        

        // move product
        /** @var ListItemsModel $listItem */
        $listItem = ListItemsModel::objects()->get(['product_list_id' => $form['fromListId'], 'list_items_id' => $form['list_items_id']]);
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

        $encrypt_params = CoreHelper::cipherText($user->user_id . '/' . $form['role'] . '/' . $form['hash']);
        
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

        $list = ProductListsModel::objects()->get(['product_list_id' => $form['listId']]);

        if (!$list) {
            http_response_code(400);
            return;
        }

        ProductListsUserRoles::objects()->create([
            'user_id' => $user->pk,
            'product_list_id' => $form['listId'],
            'role' => $form['role'],
        ]);

        $this->jsonResponse([]);
    }

    public function editUsersInList()
    {
        $user = Xcart::app()->auth->getUser(true);
        [$list_id, $user_id, $action] = array_values(json_decode(file_get_contents('php://input'), true));

        $list = ProductListsModel::objects()->get(['product_list_id' => $list_id]);

        if (!$list) {
            Xcart::app()->logger->debug("no list");
            http_response_code(400);
            return;
        }

        if (!self::canEdit($list, $user)) {
            http_response_code(400);
            return;
        }

        if ($action === 'delete') {
            ProductListsUserRoles::objects()->delete(['user_id' => $user_id, 'product_list_id' => $list_id]);
            $this->jsonResponse(['success delete']);
            return;
        }

        $role = ProductListsUserRoles::objects()->get(['user_id' => $user_id, 'product_list_id' => $list_id]);
        $role->role = $action;
        $role->save();

        $this->jsonResponse(['success']);
    }

    /**
     * @throws Exception
     */
    public function addProductOnList()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $list = ProductListsModel::objects()->get(['product_list_id' => $data['listId']]);
        $user = Xcart::app()->auth->getUser(true);

        if (!$list) {
            Xcart::app()->logger->debug("no list");
            http_response_code(400);
            return;
        }

        if (!self::canEdit($list, $user)) {
            Xcart::app()->logger->debug("cant edit");
            http_response_code(400);
            return;
        }

        if ($data['productId']) {
            $list_item = ListItemsModel::objects()->get([
                'product_id' => $data['productId'],
                'product_list_id' => $data['listId'],
            ]);

            // add item if no exists
            if (!$list_item) {
                $list_product_model = new ListItemsModel([
                    'product_id' => $data['productId'],
                    'product_list_id' => $data['listId'],
                    'product_type' => 'product'
                ]);
                $list_product_model->save();
            } else {
                http_response_code(400);
                return;
            }

            $this->jsonResponse([]);
            return;
        }

        $idea_model = new ListIdeaModel(['name' => $data['name']]);
        $idea_model->save();
        $list_product_model = new ListItemsModel([
            'product_id' => $idea_model->list_idea_id,
            'product_list_id' => $data['listId'],
            'product_type' => 'idea'
        ]);
        $list_product_model->save();

        $this->jsonResponse($list_product_model->getFrontendData());
    }

    public function editIdeaName()
    {
        $user = Xcart::app()->auth->getUser(true);
        //todo: нет проверки на право редактирования списка
        $data = json_decode(file_get_contents('php://input'), true);
        /** @var ListIdeaModel $idea_model */
        $idea = ListIdeaModel::objects()->get(['list_idea_id' => $data['productId']]);

        if (!$idea) {
            http_response_code(400);
            return;
        }

        $list_item = ListItemsModel::objects()->get(['product_id' => $idea->list_idea_id]);
        $list = ProductListsModel::objects()->get(['product_list_id' => $list_item['product_list_id']]);

        //have no permissions on edit this idea
        if (!self::canEdit($list, $user)) {
            http_response_code(400);
            return;
        }

        $idea->name = $data['name'];
        $idea->save();
        $this->jsonResponse([]);
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

        $list = ProductListsModel::objects()->get(['product_list_id' => $form['productListId']]);

        if (!$list) {
            http_response_code(400);
            return;
        }

        $list_item = ListItemsModel::objects()->get(
            [
                'list_items_id' => $form['list_items_id'],
                'product_list_id' => $form['productListId']
            ]
        );

        /** @var ListItemsModel $list_item */
        if (!$list_item) {
            http_response_code(400);
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
        $user = Xcart::app()->auth->getUser(true);
        $list = ProductListsModel::objects()->get(['product_list_id' => $form['productListId']]);

        if (!$list) {
            http_response_code(400);
            return;
        }

        
        //have no permissions on edit this idea
        if (!self::canEdit($list, $user)) {
            http_response_code(400);
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
        $form = json_decode(file_get_contents('php://input'), true);
        $attr_form = ['list_items_id' => $form['list_items_id']];
        $user = Xcart::app()->auth->getUser(true);
        $list_item = ListItemsModel::objects()->get($attr_form);


        if (!$list_item) {
            http_response_code(400);
            return;
        }

        $list = ProductListsModel::objects()->get(['product_list_id' => $list_item->product_list_id]);

        //have no permissions on edit this idea
        if (!self::canEdit($list, $user)) {
            http_response_code(400);
            return;
        }

        /** @var ListItemsModel $list_item */
        if ($list_item->product_type === ListItemsModel::TYPE_IDEA) {
            ListIdeaModel::objects()->delete(['list_idea_id' => $list_item->product_id]);
        }

        ListItemsModel::objects()->delete($attr_form);

        $this->jsonResponse(['Success']);
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

    public static function getLists($user_id): array {
        $lists_data = [];

        //select self lists
        $lists = ProductListsModel::objects()->all(["user_id" => $user_id]);

        foreach ($lists as $_ => $list) {
            $lists_data[] = $list->getFrontendData();
        }

        //select foreign lists
        $roles = ProductListsUserRoles::objects()->all(["user_id" => $user_id]);

        foreach ($roles as $_ => $role) {
            $list = ProductListsModel::objects()->get(["product_list_id" => $role->product_list_id]);
            $lists_data[] = $list->getFrontendData();
        }

        return $lists_data;
    }

    public function actionGetLists(): void
    {
        if (!$this->checkRightsUser()) {
            return;
        }

        $user = Xcart::app()->auth->getUser(true);
        $this->jsonResponse(self::getLists($user->user_id));
    }

    public function listInvite(string $tag, string $code)
    {
        if (!$user = $this->checkRightsUser()) {
            return;
        }

        [$user_id, $role, $hash] = explode('/', CoreHelper::decryptText($code, $tag));
        $list = ProductListsModel::objects()->get(['cache_url' => $hash]);

        //list no found
        /** @var ProductListsModel $invite_list */
        if (!$list) {
            $this->jsonResponse(['Not found list invite', 404]);
            return;
        }

        // already invited
        /** @var ProductListsUserRoles $invite */
        $role_model = ProductListsUserRoles::objects()->get(['user_id' => $user->pk, 'product_list_id' => $list->product_list_id]);

        if ($role_model) {
            $this->jsonResponse(['cache' => $invite->list_model->cache_url], 208);
            return;
        }

        /** @var UserModel $invited_user */
        $invited_user = UserModel::objects()->get(['pk' => $user_id]);

        $this->jsonResponse([
            'inviteUser' => $invited_user->public_name ?? $invited_user->name,
            'type' => $role,
            'listData' => [
                'productListId' => $list->product_list_id,
                'name' => $list->name,
                'cacheUrl' => $list->cache_url,
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


