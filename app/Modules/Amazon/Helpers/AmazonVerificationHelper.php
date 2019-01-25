<?php

namespace Modules\Amazon\Helpers;


use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;

class AmazonVerificationHelper
{
    const LINK_SEARCH_BY_ASIN = 'https://www.amazon.com/dp/%s/';
    const LINK_SEARCH_BY_UPC = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';
    const LINK_SEARCH_BY_NAME = 'https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias=aps&field-keywords=%s';

    public static function getSearchLinksJson(ProductModel $model)
    {
        $result = [];

        if ($model->ASIN) {
            $result['asin'] = [Xcart::app()->router->url('amazon:view') . '?' . (sprintf(self::LINK_SEARCH_BY_ASIN, $model->ASIN)), "Open product by ASIN: {$model->ASIN}"];
        }

        if ($model->upc) {
            $result['upc'] = [Xcart::app()->router->url('amazon:view') . '?' . (sprintf(self::LINK_SEARCH_BY_UPC, $model->upc)), "Search product by UPC: {$model->upc}"];
        }

        if ($name = $model->getFrontendName()) {
            $result['name'] = [Xcart::app()->router->url('amazon:view') . '?' . (sprintf(self::LINK_SEARCH_BY_NAME, urlencode(html_entity_decode($model->getFrontendName())))), "Search product by Product Name: {$model->getFrontendName()}"];
        }

        return $result;
    }

    public static function rel2abs($rel, $base): string
    {
        $path = $port = $user = $pass = $host = $scheme = null;
        if (empty($rel)) {
            $rel = '.';
        }

        if (parse_url($rel, PHP_URL_SCHEME) !== '' || strpos($rel, '//') === 0) {
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

        $port = isset($port) && (int) $port !== 80 ? ':' . $port : '';

        $auth = '';

        if (isset($user)) {
            $auth = $user;
            if (isset($pass)) {
                $auth .= ':' . $pass;
            }
            $auth .= '@';
        }
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

}