<?php

namespace Modules\Goods\Controllers\Api;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Controllers\AbstractCatalogController;
use Modules\Goods\Helpers\ApiProductHelper;
use Modules\Goods\Helpers\ProductFilterHelper;
use Modules\Goods\Helpers\ProductVerificationHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\VerificationStatusModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Xcart\App\Main\Xcart;

class ApiProductController extends AbstractCatalogController
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

        $i = 0;

        while ($pds = ProductModel::objects()->filter(
            [
                'manufacturerid' => (int)$mnf_id,
                'forsale' => 'Y',
                new QOr(['productid__isnt' => new Expression('group_root'), 'group_root__isnull' => true])
            ])->paginate(++$i, 10000)->all()) {
            foreach ($pds as $product_model) {
                /** @var ProductModel $product_model */
                $mass_of_all_mpn[] = $product_model->getMPN();
            }
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
                && ShippingHelper::isUSAContiguous($state_model)) {
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

    public function verify(): void
    {
        /** @var ProductModel $product */
        /** @var VerificationStatusModel $status */

        $result = ['result' => false];
        $post = $this->getRequest()->post;

        $product_id = $post->get('product_id');
        $status_id = $post->get('status_id');
        $note_text = $post->get('note_text') ?? '';

        $product = ProductModel::objects()->get(['productid' => $product_id]);
        $status = VerificationStatusModel::objects()->get(['statusid' => $status_id]);

        if ($product && $status) {
            ProductVerificationHelper::changeVerificationStatus($product, $status, $note_text);
            $result = ['result' => true];
        }
        $this->jsonResponse($result);
    }

    public function getQS($data = null)
    {
        return $data->childs->getQuerySet();
    }

    /**
     * get paginated child products by group product id
     * @param int $id product id
     * @param string $slug
     * @throws \Exception
     */
    public function actionProductGroup(int $id, string $slug): void
    {
        //actionViewOld
        $model = ProductModel::objects()->get(['productid' => $id]);

        //view_internal
        $this->model = $model;

        /** @var \Xcart\App\Orm\QuerySet $pqs */
        $pqs = $this->getQS($model);
        $fh = new ProductFilterHelper($pqs, $this->getRequest()->get->get('filter', []), $this->filters);


        if ($this->getRequest()->getIsAjax()) {
            $pqs = $fh->getFiltrateQS();
            $pqs = $this->getSortedQS($pqs);
        }

        $pager = $this->getPager($pqs);

        $this->setCanonical($model);

        if ($this->getRequest()->getIsAjax()) {
            $pagerView = $pager->createView();
            $this->jsonResponse(
                [
                    'href' => $pagerView->hasNextPage() ? $pagerView->getUrl($pager->getPage() + 1) : false,
                    'items' => ApiProductHelper::getProductData($pager->paginate()),
                    'pager' => [
                        'pageSize' => $pager->getPageSize(),
                        'currentPage' => $pager->getPage(),
                        'paginateCount' => count($pager->paginate()),
                        'total' => $pager->getTotal(),
                    ],
                ]
            );
        }
    }

}