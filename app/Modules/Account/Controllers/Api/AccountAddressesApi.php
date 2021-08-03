<?php


namespace Modules\Account\Controllers\Api;


use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\DeliveryTypesModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class AccountAddressesApi extends FrontendController
{
    public function getAddresses()
    {
        $user_id = json_decode(file_get_contents('php://input'));

        $user = UserModel::objects()->get(['user_id' => $user_id]);

        $resultMass = [];

        foreach ($user->addresses->order(['-is_default'])->all() as $key => $address )
        {
            $country = $address->country_model;
            $state = $address->state_model;
            $resultMass[$key] = $address->getAttributes();
            $resultMass[$key]['delivery_type'] = $address->delivery_type->name;
            $resultMass[$key]['country'] = ['value' => $country->code, 'viewValue' => $country->name];
            $resultMass[$key]['state'] = ['value' => $state->stateid, 'viewValue' => $state->state];
            $resultMass[$key]['is_default'] = (bool) $address->is_default;
        }

       $this->jsonResponse(['addresses' => $resultMass]);
    }

    public function changeDefaultAddress()
    {
        $data = json_decode(file_get_contents('php://input'));

        $addressId = $data['addressId'];

        $userId = $data['user'];

        $user = UserModel::objects()->get(['user_id' => $userId]);
        $addresses = $user->addresses->all();

        foreach ($addresses as $key => $address)
        {
            if($address->addresses_id == $addressId){

                $address->is_default = true;
            } else{
                $address->is_default = false;
            }
            $address->save();
        }

        $this->getAddresses();
    }

    public function removeAddress()
    {
        $addressId = json_decode(file_get_contents('php://input'));

        AddressesModel::objects()->delete(['addresses_id' => $addressId]);

        $this->getAddresses();
    }

    public function addAddress()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $address = $data['address'];
        $userId = $data['user'];
        $user = UserModel::objects()->get(['user_id' => $userId]);
        $addresses = $user->addresses->all();

        if($address['is_default'])
        {
            $add_arr = array_map(fn($a) => $a->addresses_id, $addresses);
            AddressesModel::objects()->filter(['addresses_id__in' => $add_arr])->update(['is_default' => false]);
        }
        $address['user_id'] = $userId;
        $model = new AddressesModel($address);
        $model->save();
        $this->getAddresses();
    }

    public function editAddress()
    {
        $address = json_decode(file_get_contents('php://input'), true);

        if($address['is_default']){
            foreach (AddressesModel::objects()->all() as $key => $addressModel)
            {
                $addressModel->is_default = false;

                $addressModel->save();
            }
        }

        $addressModel = AddressesModel::objects()->get(['addresses_id' => $address['addresses_id']]);

        $addressModel->setAttributes($address);
        $addressModel->save();

        $this->getAddresses();
    }


}