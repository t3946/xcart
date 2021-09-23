<?php

namespace Modules\Amazon\Helpers;


use FBAInventoryServiceMWS_Model_InventorySupply;
use FBAInventoryServiceMWS_Model_InventorySupplyList;
use FBAInventoryServiceMWS_Model_ListInventorySupplyResponse;
use FBAInventoryServiceMWS_Model_ListInventorySupplyResult;
use MarketplaceWebServiceProducts_Model_ASINIdentifier;
use MarketplaceWebServiceProducts_Model_CompetitivePriceList;
use MarketplaceWebServiceProducts_Model_CompetitivePriceType;
use MarketplaceWebServiceProducts_Model_CompetitivePricingType;
use MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResponse;
use MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResult;
use MarketplaceWebServiceProducts_Model_GetMyPriceForASINResponse;
use MarketplaceWebServiceProducts_Model_GetMyPriceForASINResult;
use MarketplaceWebServiceProducts_Model_GetMyPriceForSKUResponse;
use MarketplaceWebServiceProducts_Model_GetMyPriceForSKUResult;
use MarketplaceWebServiceProducts_Model_IdentifierType;
use MarketplaceWebServiceProducts_Model_MoneyType;
use MarketplaceWebServiceProducts_Model_OffersList;
use MarketplaceWebServiceProducts_Model_OfferType;
use MarketplaceWebServiceProducts_Model_PriceType;
use MarketplaceWebServiceProducts_Model_Product;
use MarketplaceWebServiceProducts_Model_SalesRankList;
use MarketplaceWebServiceProducts_Model_SalesRankType;
use MarketplaceWebServiceProducts_Model_SellerSKUIdentifier;
use Modules\Amazon\Models\AmazonFbaProductModel;
use Modules\Goods\Models\ProductModel;

class AmazonProductHelper
{
    /**
     * @param MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResponse $cpResult
     * @param ProductModel[] $aProducts
     * @return AmazonFbaProductModel[]
     */
    public static function getCompetitivePricingForSKU(MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResponse $cpResult, $aProducts)
    {
        /** @var AmazonFbaProductModel[] $aResult */
        $aResult = [];
        $iReportDate = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        if ($res = $cpResult->getGetCompetitivePricingForSKUResult()) {
            /** @var MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResult $r */
            foreach ($res as $r) {
                /** @var MarketplaceWebServiceProducts_Model_Product $p */
                if ($p = $r->getProduct()) {
                    /** @var MarketplaceWebServiceProducts_Model_IdentifierType $identifier */
                    $identifier = $p->getIdentifiers();
                    /** @var MarketplaceWebServiceProducts_Model_SellerSKUIdentifier $skuIdentifier */
                    $skuIdentifier = $identifier->getSKUIdentifier();
                    $sSKU = $skuIdentifier->getSellerSKU();
                    $aProductModels = array_filter(
                        $aProducts,
                        function ($e) use ($sSKU) {
                            return $e->productcode == $sSKU;
                        });
                    $oProductModel = reset($aProductModels);
                    if ($oProductModel) {
                        $params = ['productcode' => $sSKU, 'productid' => $oProductModel->productid, 'report_date' => $iReportDate];
                        if ($oAmazonProductModel = AmazonHelper::getAmazonFbaProductModel($params)) {
                            $oAmazonProductModel->report_date = $iReportDate;
                            /** @var MarketplaceWebServiceProducts_Model_SalesRankList $sRanks */
                            $sRanks = $p->getSalesRankings();
                            if ($sRanks && $srl = $sRanks->getSalesRank()) {
                                /** @var MarketplaceWebServiceProducts_Model_SalesRankType $sr */
                                foreach ($srl as $sr) {
                                    $oAmazonProductModel->cpr_SalesRank = max(intval($sr->getRank()), $oAmazonProductModel->cpr_SalesRank);
                                }
                            }
                            /** @var MarketplaceWebServiceProducts_Model_CompetitivePricingType $comPricing */
                            if ($comPricing = $p->getCompetitivePricing()) {
                                /** @var MarketplaceWebServiceProducts_Model_CompetitivePriceList $comPrices */
                                $comPrices = $comPricing->getCompetitivePrices();
                                if ($comPrices && $cpl = $comPrices->getCompetitivePrice()) {
                                    /** @var MarketplaceWebServiceProducts_Model_CompetitivePriceType $cp */
                                    foreach ($cpl as $cp) {
                                        if ($cp->getcondition() == 'New' && $cp->getsubcondition() == 'New') {
                                            /** @var MarketplaceWebServiceProducts_Model_PriceType $price */
                                            if ($price = $cp->getPrice()) {
                                                if ($cp->getbelongsToRequester() == 'true') {
                                                    /** @var MarketplaceWebServiceProducts_Model_MoneyType $lPrice */
                                                    if ($lPrice = $price->getLandedPrice()) {
                                                        $oAmazonProductModel->cpr_belongs_LandedPrice = $lPrice->getAmount();
                                                        $oAmazonProductModel->buybox_in++;
                                                    }
                                                } else {
                                                    if ($lPrice = $price->getLandedPrice()) {
                                                        $oAmazonProductModel->cpr_LandedPrice = $lPrice->getAmount();
                                                        $oAmazonProductModel->cpr_belongs_LandedPrice = 0;
                                                        $oAmazonProductModel->buybox_out++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            /** @var MarketplaceWebServiceProducts_Model_ASINIdentifier $identifierASIN */
                            if ($identifierASIN = $identifier->getMarketplaceASIN()) {
                                $sAsin = $identifierASIN->getASIN();
                                if (!empty($sAsin)) {
                                    $oAmazonProductModel->ASIN = $sAsin;
                                }
                            }
                            $aResult[] = $oAmazonProductModel;
                        }
                    }
                }
            }
        }
        return $aResult;
    }

    public static function getMyPriceForSKU(MarketplaceWebServiceProducts_Model_GetMyPriceForSKUResponse $cpResult, $aProducts)
    {
        /** @var AmazonFbaProductModel[] $aResult */
        $aResult = [];
        $iReportDate = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        if ($res = $cpResult->getGetMyPriceForSKUResult()) {
            /** @var MarketplaceWebServiceProducts_Model_GetMyPriceForSKUResult $r */
            foreach ($res as $r) {
                /** @var MarketplaceWebServiceProducts_Model_Product $p */
                if ($p = $r->getProduct()) {
                    /** @var MarketplaceWebServiceProducts_Model_IdentifierType $identifier */
                    $identifier = $p->getIdentifiers();
                    /** @var MarketplaceWebServiceProducts_Model_SellerSKUIdentifier $skuIdentifier */
                    $skuIdentifier = $identifier->getSKUIdentifier();
                    $sSKU = $skuIdentifier->getSellerSKU();
                    $aProductModels = array_filter(
                        $aProducts,
                        function ($e) use ($sSKU) {
                            return $e->productcode == $sSKU;
                        });
                    $oProductModel = reset($aProductModels);
                    if ($oProductModel) {
                        $params = ['productcode' => $sSKU, 'productid' => $oProductModel->productid, 'report_date' => $iReportDate];
                        if ($oAmazonProductModel = AmazonHelper::getAmazonFbaProductModel($params)) {
                            $oAmazonProductModel->report_date = $iReportDate;

                            /** @var MarketplaceWebServiceProducts_Model_OffersList $aOffers */
                            /** @var MarketplaceWebServiceProducts_Model_OfferType $oOffer */
                            /** @var MarketplaceWebServiceProducts_Model_MoneyType $lPrice */
                            if (($aOffers = $p->getOffers()) && $offerList = $aOffers->getOffer()) {

                                if ($oAmazonProductModel->lis_InStockSupplyQuantity > 0) {
                                    $sChannel = 'AMAZON';
                                } else {
                                    $sChannel = 'MERCHANT';
                                }

                                foreach ($offerList as $oOffer) {
                                    /** @var MarketplaceWebServiceProducts_Model_PriceType $buyingPrice */
                                    if (($buyingPrice = $oOffer->getBuyingPrice()) && ($oOffer->getFulfillmentChannel() == $sChannel)) {
                                        $lPrice = $buyingPrice->getLandedPrice();
                                        $oAmazonProductModel->cpr_OurLandedPrice = $lPrice->getAmount();
                                    }
                                }
                            }

                            /** @var MarketplaceWebServiceProducts_Model_ASINIdentifier $identifierASIN */
                            if ($identifierASIN = $identifier->getMarketplaceASIN()) {
                                $sAsin = $identifierASIN->getASIN();
                                if (!empty($sAsin)) {
                                    $oAmazonProductModel->ASIN = $sAsin;
                                }
                            }
                            $aResult[] = $oAmazonProductModel;
                        }
                    }
                }
            }
        }
        return $aResult;
    }

    public static function getListInventory(FBAInventoryServiceMWS_Model_ListInventorySupplyResponse $cpResult, $aProducts)
    {
        /** @var AmazonFbaProductModel[] $aResult */
        $aResult = [];
        $iReportDate = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

        if ($res = $cpResult->getListInventorySupplyResult()->getInventorySupplyList()->getmember()) {
            /** @var FBAInventoryServiceMWS_Model_InventorySupply $item */
            foreach ($res as $item) {

                $totalSupplyQuantity = $item->getTotalSupplyQuantity();
                $inStockSupplyQuantity = $item->getInStockSupplyQuantity();
                $sASIN = $item->getASIN();
                $sFNSKU = $item->getFNSKU();
                $sSKU = $item->getSellerSKU();

                $aProductModels = array_filter($aProducts, function ($e) use ($sSKU) {
                    return $e->productcode == $sSKU;
                });

                if (!empty($aProductModels)) {
                    $oProductModel = reset($aProductModels);
                    $params = ['productid' => $oProductModel->productid, 'report_date' => $iReportDate];
                    $oAmazonProductModel = AmazonHelper::getAmazonFbaProductModel($params);
                    if (!empty($sASIN)) {
                        $oAmazonProductModel->ASIN = $sASIN;
                    }
                    if (!empty($sFNSKU)) {
                        $oAmazonProductModel->setAttribute('FNSKU',  $sFNSKU);
                    }
                    if (!is_null($totalSupplyQuantity)) {
                        $oAmazonProductModel->lis_TotalSupplyQuantity = $totalSupplyQuantity;
                    }
                    if (!is_null($inStockSupplyQuantity)) {
                        $oAmazonProductModel->lis_InStockSupplyQuantity = $inStockSupplyQuantity;
                    }

                    $oAmazonProductModel->report_date = $iReportDate;
                    $oAmazonProductModel->productcode = $sSKU;
                    $aResult[] = $oAmazonProductModel;
                }
            }
        }
        return $aResult;
    }
}