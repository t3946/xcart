<?php


namespace Modules\Account\Controllers\Api;


use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\DeliveryTypesModel;
use Xcart\App\Controller\FrontendController;

class AccountAddressesApi extends FrontendController
{
    public function getAddresses()
    {
        $addresses = AddressesModel::objects()->all();

        $resultMass = [];

        foreach ($addresses as $key => $address )
        {
            $resultMass[$key] = $address->getAttributes();
            $resultMass[$key]['delivery_type'] = $address->delivery_type->name;
            $resultMass[$key]['is_default'] = (bool) $address->is_default;
        }

       $this->jsonResponse($resultMass);
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


}