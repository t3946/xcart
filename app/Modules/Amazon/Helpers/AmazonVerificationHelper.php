<?php

namespace Modules\Amazon\Helpers;


use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Main\Xcart;

class AmazonVerificationHelper
{
    public const LINK_SEARCH_BY_ASIN = 'https://www.amazon.com/dp/%s/';
    public const LINK_SEARCH_BY_UPC = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';
    public const LINK_SEARCH_BY_NAME = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';

    public static function getSearchLinksJson(ProductModel $model)
    {
        $result = [];

        if ($model->ASIN && $model->ASIN !== ProductModel::NO_ASIN_FOUND) {
            $result['asin'] = [Xcart::app()->router->url('amazon:view') . '?' . sprintf(self::LINK_SEARCH_BY_ASIN, $model->ASIN), "Open product by ASIN: {$model->ASIN}"];
        }

        if ($model->upc) {
            $result['upc'] = [Xcart::app()->router->url('amazon:view') . '?' . sprintf(self::LINK_SEARCH_BY_UPC, $model->upc), "Search product by UPC: {$model->upc}"];
        }

        if ($name = $model->getFrontendName()) {
            $result['name'] = [Xcart::app()->router->url('amazon:view') . '?' . sprintf(self::LINK_SEARCH_BY_NAME, urlencode(html_entity_decode("{$name} {$model->getMPN()}"))), "Search product by Product Name: {$model->getFrontendName()}"];
        }

        return $result;
    }

    public static function rel2abs($rel, $base): string
    {
        $path = $port = $user = $pass = $host = $scheme = null;
        if (empty($rel)) {
            $rel = '.';
        }

        if (parse_url($rel, PHP_URL_SCHEME) || strpos($rel, '//') === 0) {
            return $rel;
        } //Return if already an absolute URL

        if (strpos($rel, '#') === 0 || strpos($rel, '?') === 0) {
            return $base . $rel;
        } //Queries and anchors

        extract(parse_url($base), EXTR_OVERWRITE); //Parse base URL and convert to local variables: $scheme, $host, $path

        $path = isset($path) ? preg_replace('#/[^/]*$#', '', $path) : '/'; //Remove non-directory element from path
        if (strpos($rel, '/') === 0) {
            $path = '';
        } //Destroy path if relative url points to root

        $port = isset($port) && (int)$port !== 80 ? ':' . $port : '';

        $auth = '';

        if (isset($user)) {
            $auth = $user;
            if (isset($pass)) {
                $auth .= ':' . $pass;
            }
            $auth .= '@';
        }
        $rel = ltrim($rel, '/');

        $abs = "$auth$host$port$path/$rel"; //Dirty absolute URL

        for ($n = 1; $n > 0; $abs = preg_replace(array("#(/\.?/)#", "#/(?!\.\.)[^/]+/\.\./#"), '/', $abs, -1, $n)) {

        } //Replace '//' or '/./' or '/foo/../' with '/'
        return $scheme . '://' . $abs; //Absolute URL is ready.
    }

    public static function getAllHeaders(): array
    {
        $result = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public static function replaceHTMLUrl($content, $base_url, $url): string
    {
        $add_script = null;

        $result = preg_replace_callback('/(?<=href=(\\"|\'))[^\\"\']+(?=(\\"|\'))/', function ($matches) use ($base_url, $url) {
            return $base_url . self::rel2abs($matches[0], $url);
        }, $content);

        $result = preg_replace_callback('/(<form[^<]*action=["|\'])([^"|\']+)(["|\'][^>]*>)(.*?)(<\/form>)/is', function ($matches) use ($base_url, $url) {
            $inputhidden = '<input type="hidden" name="miniProxyFormAction" value="' . self::rel2abs($matches[2], $url) . '" />';
            $return_value = $matches[1] . rtrim($base_url, '?u=') . $matches[3] . $matches[4] . $inputhidden . $matches[5];
            return $return_value;
        }, $result);

        $sRegPattern = "/(\/gp\/product\/|\/gp\/offer-listing\/|\/dp\/)(\w+)/";

        preg_match($sRegPattern, $url, $aMatchesASIN);

        if (!empty($aMatchesASIN[2])) {
            $sASIN = $aMatchesASIN[2];
            $add_script = "<script> window.onload = function() {parent.iframeLoaded('$sASIN')};</script>";
        }

        return $result . $add_script ?? '';
    }

    public static function proxifyCSS($css, $base_url, $URL)
    {
        return preg_replace_callback(
            '/url\((.*?)\)/i',
            function ($matches) use ($URL, $base_url) {
                $url = $matches[1];
                //Remove any surrounding single or double quotes from the URL so it can be passed to rel2abs - the quotes are optional in CSS
                //Assume that if there is a leading quote then there should be a trailing quote, so just use trim() to remove them
                if (strpos($url, "'") === 0) {
                    $url = trim($url, "'");
                }
                if (strpos($url, '"') === 0) {
                    $url = trim($url, '"');
                }
                if (stripos($url, 'data:') === 0) {
                    return 'url(' . $url . ')';
                } //The URL isn't an HTTP URL but is actual binary data. Don't proxify it.
                return 'url(' . $base_url . self::rel2abs($url, $URL) . ')';
            },
            $css);
    }

    public static function getAmazonVerifyOrders()
    {
        return OrderModel::objects()
            ->filter(
                [
                    new QOr(
                        [
                            'vn_status__isnt' => OrderModel::ORDER_VERIFICATION_STATUS_PRODUCT_VERIFIED,
                            new QAnd([
                                'detail_models__product_model__amazon_verified__isnt' => 'Y',
                                'detail_models__product_model__forsale' => 'Y',
                                'detail_models__product_model__verification_statusid__isnt' => 3,
                            ]),
                        ]),
                    'order_type' => OrderModel::ORDER_TYPE_XCART,
                    'cb_status__in' => [
                        OrderStatusModel::ORDER_STATUS_AUTHORIZED,
                        OrderStatusModel::ORDER_STATUS_QUEUED,
                    ]
                ]
            )->order(['-orderid']);
    }

    public static function getNextAmazonProduct()
    {

    }

    public static function checkConclusions($params): bool
    {
        if ($params['product_image'] === 'different') {
            return false;
        }
        if ($params['product_names'] === 'different') {
            return false;
        }
        if ($params['product_description'] === 'different') {
            return false;
        }
        if ($params['qty_on_amazon'] !== $params['qty_on_our_website']) {
            return false;
        }

        return true;
    }

    public static function getConclusionsLog($params): string
    {
        $params = array_map(function($a){return str_replace('_', ' ', $a);}, $params);
        $log = "Product images show: {$params['product_image']}\n";
        $log .= "Product names: {$params['product_names']}\n";
        $log .= "Product descriptions: {$params['product_description']}\n";
        $log .= "Pack quantity listed on Amazon: {$params['qty_on_amazon']}\n";
        $log .= "Pack quantity listed on our website: {$params['qty_on_our_website']}\n";

        return $log;
    }

    public static function addAmazonListing(ProductModel $model):? string
    {
        if ($model->ASIN === ProductModel::NO_ASIN_FOUND) return null;

        $sFeed = "TemplateType=Offer\tVersion=2014.0703" . str_repeat("\t", 254) . PHP_EOL;
        $aHeader = ['sku',
            'price',
            'quantity',
            'product-id',
            'product-id-type',
            'condition-type',
            'condition-note',
            'ASIN-hint',
            'title',
            'product-tax-code',
            'operation-type',
            'sale-price',
            'sale-start-date',
            'sale-end-date',
            'leadtime-to-ship',
            'launch-date',
            'is-giftwrap-available',
            'is-gift-message-available',
            'fulfillment-center-id',
            'main-offer-image',
            'offer-image1',
            'offer-image2',
            'offer-image3',
            'offer-image4',
            'offer-image5'];
        $sFeed .= implode("\t", $aHeader) . str_repeat("\t", 231) . "\n";
        $sFeed .= implode("\t", $aHeader) . str_repeat("\t", 231) . "\n";

        $row = [
            $model->productcode,
            number_format($model->getMinimumAmazonPrice(), 2, '.', ''),
            $model->getAmazonQuantity(),
            $model->ASIN,
            'ASIN',
            'New',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $model->distributor->amazon_leadtime_to_ship,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''];

        $sFeed .= implode("\t", $row) . str_repeat("\t", 231) . "\n";

        $feedHandle = @fopen('php://temp', 'rw+');
        if ($feedHandle) {
            fwrite($feedHandle, $sFeed);
            rewind($feedHandle);
            $amzPool = new AmazonPoolStore();
            /** @var \MarketplaceWebService_Model_SubmitFeedResult $result */
            $result = $amzPool->getFeedAndReportClientPack()
                ->callSubmitFeed(MwsFeedAndReportClientPack::FEED_TYPE_PAI_FLAT_LISTINGS, $feedHandle)
                ->getSubmitFeedResult();
            @fclose($feedHandle);

            /** @var \MarketplaceWebService_Model_FeedSubmissionInfo $s_info */
            $s_info = $result->getFeedSubmissionInfo();
            return $s_info->getFeedSubmissionId();
        }

        return null;

    }

}