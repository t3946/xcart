<?php

namespace Modules\Goods\Helpers;

use DateTime;
use DateTimeInterface;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\CurrencyModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Exceptions\UnknownPropertyException;
use Xcart\App\Main\Xcart;
use function is_array;

class ApiProductHelper
{
    /**
     * get array of main product fields (product has many excess data because this method takes only needed info) and return this
     * @param $products
     * @return array
     * @throws UnknownPropertyException
     */
    public static function getProductData($products, array $params = []): array
    {
        if (!is_array($products)) {
            $products->limit(20)->cache(60);
        }
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        /** @var CurrencyModel $currency */
        $currency = $site->getCurrency();
        $data = [];

        /**
         * @var ProductModel $product
         */
        foreach ($products as $product) {
            //get images
            $images = [];

            if ($product->isGroupRoot()) {
                /** @var ProductModel[] $children */
                $children = $product->getFrontendChilds()->limit(4)->all();
                $unique_hash_list = [];

                foreach ($children as $child) {
                    $image = $child->getMainImage();

                    if (in_array($image->hash, $unique_hash_list, true) === true) {
                        continue;
                    }

                    $unique_hash_list[] = $image->hash;

                    if ($image && $url = $image->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB)) {
                        $images[] = [
                            'url' => $url,
                            'alt' => $child->getFrontendName(),
                        ];
                    }
                }
            } else {
                if ($params['is_slider'] && $product->isGroupChild()) {
                    $product = $product->parent;
                }
                $imageModel = $product->getMainImage();

                if ($imageModel && $url = $imageModel->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB)) {
                    $images[] = [
                        'url' => $url,
                        'alt' => $product->getFrontendName(),
                    ];
                }
            }

            $eta_date = '';

            if ($product->eta_date_mm_dd_yyyy && $product->eta_date_mm_dd_yyyy > time()) {
                $eta_date = $product->getFrontendEtaDate();
            }

            $dx = $product->distributor;
            $brand = $product->brand;

            $data[] = [
                'name' => htmlspecialchars_decode($product->getFrontendName() ?: $product->product, ENT_QUOTES),
                'url' => $product->getAbsoluteUrl(),
                'mpn' => $product->getMpn(),
                'upc' => $product->upc,
                'images' => $images,
                'description' => utf8_encode($product->getCatalogDescription(140)),
                'short_description' => utf8_encode($product->getCatalogDescription(70)),
                'inStock' => !$product->isOutOfStock(),
                'productcode' => $product->productcode,
                'brand' => $product->brand->brand ?? null,
                'brandUrl' => $product->brand ? $product->brand->getAbsoluteUrl() : null,
                'min_amount' => $product->min_amount,
                'lead_time' => [
                    'lead_time_message' => trim($product->lead_time_message),
                    'dx' => [
                        'leadtime' => $dx->dx_leadtime,
                        'leadtime_to' => $dx->dx_leadtime_to,
                    ],
                    'brand' => [
                        'leadtime_from' => $brand->leadtime_from,
                        'leadtime_to' => $brand->leadtime_to,
                    ],
                ],
                'mult_order_quantity' => $product->mult_order_quantity,
                'eta_date' => $eta_date,
                'avail' => $product->r_avail,
                'productid' => $product->productid,
                'isNew' => $product->isNewProduct(),
                'isSale' => $product->isSaleSticker(),
                'isGroupRoot' => $product->isGroupRoot(),
                'childrenNumber' => $product->getFrontendChilds()->count(),

                'price' => [
                    'number' => $product->getFrontendPrice(),
                    'formatted' => $currency->getCurrencyFormat($product->getFrontendPrice()),
                ],

                'listPrice' => [
                    'number' => (float)$product->list_price,
                    'formatted' => $currency->getCurrencyFormat($product->list_price),
                ],
            ];
        }

        return $data;
    }

    public static function getRussiaMonth(int $num_month): string
    {
        $monthes = [
            1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
            5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
            9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
        ];
        return $monthes[$num_month];
    }
}