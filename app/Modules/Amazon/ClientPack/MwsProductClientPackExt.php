<?php

namespace Modules\Amazon\ClientPack;


use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\ClientPack\MwsProductClientPack;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestLogCollection;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestManager;

class MwsProductClientPackExt extends MwsProductClientPack
{
    const METHOD_GET_COMPETITIVE_PRICING_FOR_SKU   = 'getCompetitivePricingForSKU';
    const METHOD_GET_MY_PRICE_FOR_SKU   = 'getMyPriceForSKU';

    private $throttleManager;

    public function callGetCompetitivePricingForSKU($skuList)
    {
        $options = [
            self::PARAM_SELLER_ID => $this->sellerId,
            self::PARAM_MARKETPLACE_ID => $this->marketplaceId,
            self::PARAM_SELLER_SKU_LIST => array('SellerSKU' => $skuList),
        ];
        $weight = is_array($skuList) ? count($skuList) : 1;
        return CaponicaClientPack::throttledCall($this, self::METHOD_GET_COMPETITIVE_PRICING_FOR_SKU, $options, $weight);
    }

    public function callGetMyPriceForSKU($skuList, $itemCondition = self::ITEM_CONDITION_TEXT_NEW) {
        $options = [
            self::PARAM_SELLER_ID       => $this->sellerId,
            self::PARAM_MARKETPLACE_ID  => $this->marketplaceId,
            self::PARAM_SELLER_SKU_LIST => array('SellerSKU' => $skuList),
            self::PARAM_ITEM_CONDITION  => $itemCondition,
        ];
        $weight = is_array($skuList) ? count($skuList) : 1;
        return CaponicaClientPack::throttledCall($this, self::METHOD_GET_MY_PRICE_FOR_SKU, $options, $weight);
    }

    public function initThrottleManager() {
        $this->throttleManager = new ThrottledRequestManager(
            [
                self::METHOD_GET_COMPETITIVE_PRICING_FOR_ASIN   => [20, 10, ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
                self::METHOD_GET_LOWEST_OFFER_LISTINGS_FOR_ASIN => [20, 10, ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
                self::METHOD_GET_MATCHING_PRODUCTS              => [20, 2,  ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
                self::METHOD_GET_MATCHING_PRODUCTS_FOR_ID       => [20, 5,  ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
                self::METHOD_LIST_MATCHING_PRODUCTS             => [20, 0.2],
                self::METHOD_GET_COMPETITIVE_PRICING_FOR_SKU    => [20, 10, ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
                self::METHOD_GET_MY_PRICE_FOR_SKU               => [20, 10, ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
            ]
        );
    }

    public function getThrottleManager() {
        return $this->throttleManager;
    }

}