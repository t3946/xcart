<?php

namespace Modules\Amazon\Controllers;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Modules\Amazon\Helpers\AmazonVerificationHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class AmazonVerificationController extends Controller
{

    public function verification($id, $oid): void
    {
        /** @var ProductModel $model */

        if ($model = ProductModel::objects()->get(['productid' => $id])) {

            echo $this->render('amazon/verification/product.tpl',
                [
                    'model' => $model,
                    'links' => AmazonVerificationHelper::getSearchLinksJson($model),
                    'order' => OrderModel::objects()->get(['orderid' => $oid])
                ]
            );
        }
    }

    public function view(): void
    {

        $cookieFile = 'cookie_jar.txt';
        $cookieJar = new FileCookieJar($cookieFile, TRUE);
        $client = new Client(['cookies' => $cookieJar]);

        $url = Xcart::app()->request->getQueryString();

        if (Xcart::app()->request->request->has('miniProxyFormAction')) {
            $url = Xcart::app()->request->request->get('miniProxyFormAction');
        }

        $params = null;
        //$params = Xcart::app()->request->request->all();

        switch (Xcart::app()->request->getMethod()) {
            case 'GET' :
                break;
            case 'POST' :
                break;
        }

        unset($params['miniProxyFormAction']);

        $all_headers = array_diff_key(AmazonVerificationHelper::getAllHeaders(), array_flip(['Host', 'Content-Length', 'Accept-Encoding']));
        $all_headers['User-Agent'] = Xcart::app()->request->getUserAgent() ?? 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36';

        $options = ['headers' => $all_headers, 'http_errors' => false];
        if ($params) {
            $options['form_params'] = $params;
        }

        $res = $client->request(Xcart::app()->request->getMethod(), $url, $options);

        $res = $res->withoutHeader('X-Frame-Options: SAMEORIG');
        $res = $res->withoutHeader('X-Frame-Options: Deny');

        header('Content-Type: ' . $res->getHeaderLine('Content-Type'));

        if (stripos($res->getHeaderLine('Content-Type'), 'text/html') !== false) {
            echo AmazonVerificationHelper::replaceHTMLUrl($res->getBody(), Xcart::app()->router->absoluteUrl('amazon:view') . '?', $url);
            return;
        }
        if (stripos($res->getHeaderLine('Content-Type'), 'text/css') !== false) {

            echo AmazonVerificationHelper::proxifyCSS($res->getBody(), Xcart::app()->router->absoluteUrl('amazon:view') . '?', $url);
            return;
        }

        header('Content-Length: ' . strlen($res->getBody()));
        echo $res->getBody();

    }

    public function submit()
    {

        if (Xcart::app()->request->post->has('verify_status_id')) {
            $params = Xcart::app()->request->post->all();
            if ($params['asin'] && $params['product_id'] && $model = ProductModel::objects()->get(['product_id' => $params['product_id']])) {

                $model->asin = trim($params['asin']);

                if ($params['batch_id'] && $order = OrderModel::objects()->get(['orderid' => $params['batch_id']])) {
                    (new OrderLogModel)->setAttributes([
                        'orderid' => $order->orderid,
                        'type' => OrderLogModel::LOG_TYPE_XCART,
                        'login' => Xcart::app()->user->login,
                        'log' => nl2br("<b>Amazon verification</b>\nSKU {$model->productcode} --> ASIN {$model->asin}\n"),
                    ])->save();
                }
            }
        }
    }
}