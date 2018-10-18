<?php

namespace Modules\Amazon\Helpers;


class AmazonOfferHelper
{
    public const OUR_MERCHANT_ID = 'A2SWKX6V1OVQ89';

    public static function getLowestPrice(array $lowestPrices): array
    {
        $result = $prices = [];


        if (!isset($lowestPrices[0]) && isset($lowestPrices['LandedPrice'])) {
            $prices[] = $lowestPrices;
        } else {
            $prices = $lowestPrices;
        }

        foreach ($prices as $lowestPrice) {
            if ($lowestPrice['@attributes']['condition'] === 'new') {
                $channel = null;
                if (!$result['LandedPrice'] || (float)$lowestPrice['LandedPrice']['Amount'] < (float)$result['LandedPrice']) {

                    if ($lowestPrice['@attributes']['fulfillmentChannel']) {
                        $channel = $lowestPrice['@attributes']['fulfillmentChannel'] === 'Amazon' ? 'FBA' : 'MFN';
                    }

                    $result = [
                        'LandedPrice' => (float)$lowestPrice['LandedPrice']['Amount'],
                        'ListingPrice' => (float)$lowestPrice['ListingPrice']['Amount'],
                        'Shipping' => (float)$lowestPrice['Shipping']['Amount'],
                        'fulfillmentChannel' => $channel,
                    ];
                }
            }
        }
        return $result;
    }

    public static function getSalesRank(array $salesRank): ?int
    {
        $result = null;
        foreach ($salesRank as $rank) {
            if (!is_numeric($rank['ProductCategoryId'])) {
                $result = $result ? min($result, (int)$rank['Rank']) : (int)$rank['Rank'];
            }
        }
        return $result;
    }
}