<?php

namespace Modules\Goods\Controllers\Api;

use Modules\Goods\Models\ProductModel;
use Xcart\App\Controller\Controller;

class ApiProductController extends Controller
{
    private const PRIVATE_KEY = 'y5gzWWCcqyVVQByEzG/mRApTaW6l1tvq2ngOb5b3qeA=';
    private const PUBLIC_KEY = '2r7bQsPMLds=';

    public function getDistributorProductList(): void
    {
        $encrypt = base64_decode($_GET['a']);
        $ad =      base64_decode($_GET['b']);

        $nonce = base64_decode(self::PUBLIC_KEY);
        $key = base64_decode(self::PRIVATE_KEY);

        $decrypt = sodium_crypto_aead_chacha20poly1305_decrypt($encrypt, $ad, $nonce, $key);

        $decrypt = explode("&", $decrypt);

        $qs = ProductModel::objects()->getQuerySet();
        $filter = [];

        foreach ($decrypt as $parameter){
            $parameter = explode("=", $parameter);
            $filter[$parameter[0]] = $parameter[1];
        }

        /** @var ProductModel $product_models */
        $product_models = $qs->filter($filter)->all();

        $mass_of_all_mpn = [];

        foreach ($product_models as $product_model){
            $mass_of_all_mpn[] = $product_model->getMPN();
        }

        $this->jsonResponse($mass_of_all_mpn);
    }

}