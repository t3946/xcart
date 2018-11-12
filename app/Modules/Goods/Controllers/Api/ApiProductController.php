<?php

namespace Modules\Goods\Controllers\Api;

use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class ApiProductController extends Controller
{
    private const PRIVATE_KEY = 'y5gzWWCcqyVVQByEzG/mRApTaW6l1tvq2ngOb5b3qeA=';
    private const PUBLIC_KEY = '2r7bQsPMLds=';

    public function getDistributorProductList(): void
    {
        $encrypt = base64_decode($_GET['a']);
        $ad = base64_decode($_GET['b']);

        $nonce = base64_decode(self::PUBLIC_KEY);
        $key = base64_decode(self::PRIVATE_KEY);

        $decrypt = sodium_crypto_aead_chacha20poly1305_decrypt($encrypt, $ad, $nonce, $key);

        $decrypt = explode("&", $decrypt);

        $qs = ProductModel::objects()->getQuerySet();
        $filter = [];

        foreach ($decrypt as $parameter) {
            $parameter = explode("=", $parameter);
            $filter[$parameter[0]] = $parameter[1];
        }

        /** @var ProductModel $product_models */
        $product_models = $qs->filter($filter)->all();

        $mass_of_all_mpn = [];

        foreach ($product_models as $product_model) {
            $mass_of_all_mpn[] = $product_model->getMPN();
        }

        $this->jsonResponse($mass_of_all_mpn);
    }

    public function getMpn($mnf_id): void
    {
        $mass_of_all_mpn = [];

        foreach (ProductModel::objects()->filter(['manufacturerid' => (int) $mnf_id]) as $product_model) {
            /** @var ProductModel $product_model */
            $mass_of_all_mpn[] = $product_model->getMPN();
        }

        $this->jsonResponse($mass_of_all_mpn);
    }

    public function getProductInfo($id): void
    {
        $result = [];

        /** @var ProductModel $model */
        if ($model = ProductModel::objects()->get(['productid' => (int)$id])) {
            if (($geo_ip = GeoipHelper::getGeoipLocation($ip = Xcart::app()->request->getUserIP()))
                && ($state_model = $geo_ip->state_model)
                && ShippingHelper::isUSAContiguous($state_model))
            {
                if ($free_ship_q = ShippingHelper::getQtyForFreeShipping($model, $state_model, $geo_ip->postalCode)) {

                    if ($free_ship_q > 1) {
                        $free_text = "Buy {$free_ship_q} items for Free Shipping";
                    } else {
                        $free_text = 'Free Shipping for US';
                    }

                    $result['shipping']['free_shipping'] = $this->render('product/messages/_p_label.tpl',
                        [
                            'cls' => 'fill free-shipping',
                            'text' => $free_text
                        ]
                    );
                }
            }
        }


        $this->jsonResponse($result);
    }


}