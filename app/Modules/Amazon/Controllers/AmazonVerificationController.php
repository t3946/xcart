<?php

namespace Modules\Amazon\Controllers;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Modules\Amazon\Helpers\AmazonVerificationHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderLogModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

class AmazonVerificationController extends Controller
{

    public function index():void
    {
        /** @var OrderModel $order */

        if ($orders = AmazonVerificationHelper::getAmazonVerifyOrders()->limit(100)->all()) {
            foreach ($orders as $order) {
                if ($products = $order->getProducts()) {
                    foreach ($products as $product) {
                        if ($product->amazon_verified !== 'Y') {
                            $this->redirect('amazon:verification', ['id' => $product->productid, 'oid' => $order->orderid], 302, ['split_screen' => 1]);
                        }
                    }
                }
            }
        }
    }

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

        $cookieFile = Paths::get('runtime').'/tmp/'. Xcart::app()->user->login.'_cookie_jar.txt';
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

            /** @var ProductModel $model */
            if ($params['product_id'] && $model = ProductModel::objects()->get(['productid' => $params['product_id']])) {

                $log = '';

                $params['asin'] = trim($params['asin']);

                $old_asin = $model->ASIN;

                if ($params['verify_status_id'] === 'not_found' || !AmazonVerificationHelper::checkConclusions($params['conclusion_buttons'])) {
                    $model->ASIN = ProductModel::NO_ASIN_FOUND;

                    $log = "<b>Amazon verification</b>\n<b>{$model->productcode}</b> --> <b>{$model->ASIN}</b>\n";
                }
                elseif ($params['asin']) {
                    $model->ASIN = $params['asin'];
                    $log = "<b>Amazon verification</b>\n<b>{$model->productcode}</b> --> <b>{$model->ASIN}</b>\n";
                }

                $log .= AmazonVerificationHelper::getConclusionsLog($params['conclusion_buttons']);

                if ($params['note_text']) {
                    $log .= "<b>Note</b>\n";
                    $log .= "{$params['note_text']}\n";
                }

                $model->amazon_verified = 'Y';

                $model->save();

                /** @var OrderModel $order */
                if ($params['batch_id'] && $order = OrderModel::objects()->get(['orderid' => $params['batch_id']])) {

                    if ($old_asin !== $model->ASIN && $FeedSubmissionId = AmazonVerificationHelper::addAmazonListing($model)) {
                          $log .= <<<HTML
 Inventory file has been successfully created <a href="https://sellercentral.amazon.com/listing/status?reference_id={$FeedSubmissionId}#{$FeedSubmissionId}" target="_blank">Feed: {$FeedSubmissionId}</a>
HTML;
                    }

                    (new OrderLogModel([
                        'orderid' => $order->orderid,
                        'type' => OrderLogModel::LOG_TYPE_XCART,
                        'login' => Xcart::app()->user->login,
                        'log' => nl2br($log),
                    ]))->save();


                    echo json_encode(['status' => 'ok']);
                }
            }
        }
    }
}