<?php


namespace Modules\Account\Controllers\Api;


use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\CreditCardsModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderGroupRefundModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;

class AccountWalletApi  extends Controller
{
    public function getCards()
    {
        $user = json_decode(file_get_contents('php://input'));
        $this->jsonResponse($this->getCardsFromBase($user));
    }

    public function getCardsFromBase(int $id)
    {
        $user = UserModel::objects()->get(['user_id' => $id]);

        $models = $user->cards->order(['-is_default'])->all();

        $cards = [];

        foreach ($models as $key => $model)
        {
            $cards[$key] = $model->getAttributes();
            $cards[$key]['address'] = $model->address_model->getAttributes();
        }

        return $cards;

    }

    public function changeDefault()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $cardId = $data['cardId'];
        $userId = $data['user'];

        $user = UserModel::objects()->get(['user_id' => $userId]);
        $cards = $user->cards->order(['-is_default'])->all();

        foreach ($cards as $card)
        {
            if($card->credit_card_id == $cardId){
                $card->is_default = true;
            } else{
                $card->is_default = false;
            }
            $card->save();
        }

        $this->jsonResponse( $this->getCardsFromBase($userId));
    }

    public function addNewCard()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $userId = $data['user'];

        $newCard = $data['card'];

        $newCard['user_id'] = $userId;

        $address = $data['address'];

        if ($address['address_id']) {
            $newCard['address_id'] = $address['address_id'];

        }
        else
        {
            $address['address_type'] = 'billing';
            $address['user_id'] = $userId;
            $model = new AddressesModel($address);
            $model->save();

            $newCard['address_id'] = $model->address_id;
        }
        if($newCard['is_default']){
            $cards = CreditCardsModel::objects()->all();

            foreach ($cards as $card)
            {
                $card->is_default = false;
                $card->save();
            }
        }
        CreditCardsModel::objects()->create($newCard);

        $this->jsonResponse( $this->getCardsFromBase($userId));
    }

    public function editCard()
    {
        $cardInfo = json_decode(file_get_contents('php://input'), true);

        $userId = $cardInfo['user'];

        $editCard = $cardInfo['card'];

        $address = $cardInfo['address'];

        if ($address['address_id']) {
            $editCard['address_id'] = $address['address_id'];
        }
        else if($address)
        {
            $address['address_type'] = 'billing';
            $address['user_id'] = $cardInfo['userId'];
            $model = new AddressesModel($address);
            $model->save();

            $editCard['address_id'] = $model->address_id;
        }

        $addressModel = CreditCardsModel::objects()->get(['credit_card_id' => $editCard['credit_card_id']]);


        $addressModel->setAttributes($editCard);

        $addressModel->save();
        $this->jsonResponse( $this->getCardsFromBase($userId));
    }

    public function removeCard()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $userId = $data['user'];

        $cardId = $data['card'];

        CreditCardsModel::objects()->delete(['credit_card_id' => $cardId]);

        $this->jsonResponse( $this->getCardsFromBase($userId));
    }

    public function getTransactions()
    {
        $user_id = json_decode(file_get_contents('php://input'));

        $user = UserModel::objects()->get(['user_id' => $user_id]);
        $transactions = [];

        foreach ($user->transactions->all() as $key => $item)
        {
            $transactions[$key]['transaction_id'] = $item->transaction_id;
            $transactions[$key]['orderInfo'] = $item->order->getAttributes();
            $transactions[$key]['cardInfo'] = $item->credit_card->getAttributes();

            if (OrderGroupModel::objects()->filter(['orderid' => $item->order->orderid])->count()) {
                $transactions[$key]['orderInfo']['orderGroups'] = OrderGroupModel::objects()->filter(['orderid' => $item->order->orderid])->asArray()->all();
                $transactions[$key]['type'] = 'shipping';
            } else {
                $transactions[$key]['orderInfo']['orderGroups'] = OrderGroupRefundModel::objects()->filter(['orderid' => $item->order->orderid])->asArray()->all();
                $transactions[$key]['type'] = 'refund';
            }

            foreach ( $transactions[$key]['orderInfo']['orderGroups'] as $group_key => $group_item)
            {
                if(  $transactions[$key]['type'] === 'refund')
                {
                    foreach ( OrderGroupRefundModel::objects()->filter(['orderid' => $item->order->orderid])->all() as $refund_key => $refund_item)
                    {
                        foreach ( $refund_item->products as $refund_products_key => $refund_product){
                            $transactions[$key]['orderInfo']['orderGroups'][$group_key]['orderGroupsItems'][$refund_key] = array_merge($refund_product->product->getAttributes(),$refund_product->order_detail->asArray()->all());
                        }
                    }
                }
                else{
                    $transactions[$key]['orderInfo']['orderGroups'][$group_key]['orderGroupsItems'] = OrderDetailModel::objects()->
                    filter(['order_group_id' =>  $transactions[$key]['orderInfo']['orderGroups'][$group_key]['order_group_id']])->
                    asArray()->all();
                }

            }
        }

      $this->jsonResponse($transactions);
    }
}