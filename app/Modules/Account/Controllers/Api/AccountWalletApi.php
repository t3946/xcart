<?php


namespace Modules\Account\Controllers\Api;


use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\CreditCardsModel;
use Xcart\App\Controller\FrontendController;

class AccountWalletApi  extends FrontendController
{
    public function getCards()
    {
        $models = CreditCardsModel::objects()->order(['-is_default'])->all();

        $cards = [];

        foreach ($models as $key => $model)
        {
            $cards[$key] = $model->getAttributes();
            $cards[$key]['address'] = $model->address_model->getAttributes();
        }

        $this->jsonResponse($cards);
    }

    public function changeDefault()
    {
        $cardId = json_decode(file_get_contents('php://input'));
        $cards = CreditCardsModel::objects()->all();

        foreach ($cards as $card)
        {
            if($card->credit_card_id == $cardId){
                $card->is_default = true;
            } else{
                $card->is_default = false;
            }
            $card->save();
        }

        $this->getCards();
    }

    public function addNewCard()
    {
        $cardInfo = json_decode(file_get_contents('php://input'), true);

        $newCard = $cardInfo['card'];

        $address = $cardInfo['address'];

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

        $this->getCards();
    }
}