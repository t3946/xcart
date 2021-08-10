<?php


namespace Modules\Account\Controllers\Api;


use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\CreditCardsModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;

class AccountWalletApi  extends FrontendController
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
            $address['user_id'] = $cardInfo['userId'];
            $model = new AddressesModel($address);
            $model->save();

            $newCard['address_id'] = $model->addresses_id;
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

            $editCard['address_id'] = $model->addresses_id;
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
}