<?php


namespace Modules\Account\Controllers\Api;


use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\DeliveryTypesModel;
use Xcart\App\Controller\FrontendController;

class AccountAddressesApi extends FrontendController
{
    public function getAddresses()
    {
        $addresses = AddressesModel::objects()->order(['-is_default'])->all();

        $resultMass = [];

        foreach ($addresses as $key => $address )
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
        $addressId = json_decode(file_get_contents('php://input'));
        $addresses = AddressesModel::objects()->all();

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
        $address = json_decode(file_get_contents('php://input'), true);

        if($address['is_default']){
            foreach (AddressesModel::objects()->all() as $key => $addressModel)
            {
                $addressModel->is_default = false;

                $addressModel->save();
            }
        }
        AddressesModel::objects()->create($address);
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