<?php

namespace Modules\Goods\Controllers;


use Modules\Core\Models\StateModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Xcart\App\Controller\PrototypeAdminController;

class ShippingController extends PrototypeAdminController
{
    public function calculate_shipping($id)
    {
        $rates = $states = [];

        if ($sates = StateModel::objects()
            ->filter(['country_code' => 'US'])
            ->exclude(['base_state_zipcode' => ''])
            ->order(['state'])
            ->all()) {
            /** @var StateModel $state */
            foreach ($sates as $state) {
                $states[$state->stateid] = $state;
                $rates[$state->stateid] = ShippingHelper::getStateShipping($id, 1, $state);
            }

            echo $this->render('shipping/state_shipping.tpl',
                [
                    'rates' => $rates,
                    'states' => $states,
                ]
            );
        }
    }
}