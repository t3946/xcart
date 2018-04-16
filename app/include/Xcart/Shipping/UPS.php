<?php

namespace Xcart\Shipping;

use Monolog\Logger;
use Ups\Entity\Address;
use Ups\Entity\Package;
use Ups\Entity\PackagingType;
use Ups\Entity\ShipFrom;
use Ups\Entity\Shipment;
use Ups\Rate;
use Xcart\App\Main\Xcart;
use Xcart\Logs;
use Xcart\Product;
use Xcart\ApproximationShippingRates;
use Xcart\ShippingRate;
use Xcart\SQLBuilder;

class UPS extends ShippingProcessor
{
    const APPROXIMATION_MAX_VALID_TIME = 5184000; //2 months
    const MAX_WEIGHT_FOR_UPS_PACKAGE = 150; //2 months

    /*This table provides correct service codes for different origins
    <Code returned from UPS> => array (<origin> => <service_code in xcart_shipping>)*/
    private $ups_services = [
        "01" => array("US" => 5, "CA" => 8, "PR" => 5),
        "02" => array("US" => 1, "PR" => 1),
        "03" => array("US" => 4, "PR" => 4),
        "07" => array("US" => 16, "EU" => 8, "CA" => 16, "PR" => 16, "MX" => 8, "OTHER_ORIGINS" => 16, "PL" => 8),
        "08" => array("US" => 15, "EU" => 13, "CA" => 15, "PR" => 15, "MX" => 13, "OTHER_ORIGINS" => 15, "PL" => 13),
        "11" => array("US" => 14, "EU" => 14, "CA" => 14, "PL" => 14),
        "12" => array("US" => 3, "CA" => 3),
        "13" => array("US" => 7, "CA" => 12),
        "14" => array("US" => 6, "CA" => 9, "PR" => 6),
        "54" => array("US" => 17, "EU" => 17, "PR" => 17, "MX" => 11, "OTHER_ORIGINS" => 17, "PL" => 17),
        "59" => array("US" => 2),
        "65" => array("US" => 12, "EU" => 12, "CA" => 12, "PR" => 12, "MX" => 12, "OTHER_ORIGINS" => 12, "PL" => 12),
        "82" => array("PL" => 18),
        "83" => array("PL" => 19),
        "84" => array("PL" => 20),
        "85" => array("PL" => 21),
        "86" => array("PL" => 22)
    ];

    private $ups_approximation_shipping_methods = [
        '' => 1,
        'US' => 1,
        'CA' => 65
    ];

    public function isProcessorApplicable()
    {
        return true;
    }

    /**
     * @param $aShippingRates ShippingRate[]
     * @return null|\Ups\Entity\RateRequest
     */
    public function getServerQuotes($aShippingRates)
    {
        /* get UPS Rates from server */
        global $config;
        $aResponses = null;
        if (!empty($aShippingRates)) {
            $oShippingRate = reset($aShippingRates);

            $UPS_username = text_decrypt(trim($config["UPS_OnLine_Tools"]["UPS_username"]));
            $UPS_password = text_decrypt(trim($config["UPS_OnLine_Tools"]["UPS_password"]));
            $UPS_accesskey = text_decrypt(trim($config["UPS_OnLine_Tools"]["UPS_accesskey"]));

            $rate = new Rate($UPS_accesskey, $UPS_username, $UPS_password);

            try {
                $oCustomer = $this->getCustomer();

                $shipment = new Shipment();

                $shipperAddress = $shipment->getShipper()->getAddress();
                $shipperAddress->setPostalCode($this->getManufacturer()->m_zipcode);

                $address = new Address();
                $address->setPostalCode($this->getManufacturer()->m_zipcode);
                $address->setCountryCode($this->getManufacturer()->m_country);

                $shipFrom = new ShipFrom();
                $shipFrom->setAddress($address);
                $shipment->setShipFrom($shipFrom);

                $shipTo = $shipment->getShipTo();
                $shipTo->setCompanyName("Shipping To {$oCustomer->s_zipcode}");

                $shipToAddress = $shipTo->getAddress();

                //$shipToAddress->setResidentialAddressIndicator(true);

                $shipToAddress->setPostalCode($oCustomer->s_zipcode);
                if ($oCustomer->s_state) {
                    $shipToAddress->setStateProvinceCode($oCustomer->s_state);
                }
                $shipToAddress->setCountryCode($oCustomer->s_country);

                $shippingWeight = min(self::MAX_WEIGHT_FOR_UPS_PACKAGE, $oShippingRate->getCartShippingWeight());

                $package = new Package();
                $package->getPackagingType()->setCode(PackagingType::PT_PACKAGE);
                $package->getPackageWeight()->setWeight($shippingWeight);

                $shipment->addPackage($package);
                $aResponses = $rate->shopRates($shipment);


            } catch (\Exception $e) {
                //TODO Add logging
                //Logs::_log(Logs::LOG_RESOURCE_SHIPPING_QUOTES, time(), Logs::LOG_TYPE_SYSTEM, __CLASS__ . ': ' . $e->getMessage());
            }
        }

        return $aResponses;
    }

    public function getShippingQuotes()
    {
        if (empty($this->aShippingRates)) {
            $aShippingRates = $this->getShippingRatesEntities();
            if (!empty($aShippingRates)) {
                if ($this->useApproximation) {
                    foreach ($aShippingRates as $oShippingRate) {
                        if ($oShippingRate->getShippingId() == $this->ups_approximation_shipping_methods[$this->oManufacturer->m_country]) {
                            /*get aproximation rates for UPS Ground*/
                            $oApproximationRates = ApproximationShippingRates::model()->find(
                                SQLBuilder::getInstance()->
                                addCondition('manufacturerid = ' . $this->getManufacturer()->manufacturerid)->
                                addCondition('last_updated_date >= ' . (time() - self::APPROXIMATION_MAX_VALID_TIME))->
                                addCondition("state = '{$this->getCustomer()->s_state}'")
                            );
                            if ($oApproximationRates->manufacturerid) {
                                $weight = ceil($oShippingRate->getCartShippingWeight());
                                $shippingCharge = 0;
                                switch ($weight) {
                                    case ($weight > 0 && $weight <= 1):
                                        $shippingCharge = $oApproximationRates->bw_1;
                                        break;
                                    case ($weight > 1 && $weight <= 75):
                                        $shippingCharge = $oApproximationRates->bw_1 + ($oApproximationRates->bw_75 - $oApproximationRates->bw_1) / (75 - 1) * ($weight - 1);
                                        break;
                                    case ($weight > 75):
                                        $shippingCharge = $oApproximationRates->bw_75 + ($oApproximationRates->bw_150 - $oApproximationRates->bw_75) / (150 - 75) * ($weight - 75);
                                        break;
                                }
                                $this->aShippingRates[$oShippingRate->getShippingId()] = $oShippingRate->setShippingChargeQuote(round($shippingCharge, 2));
                            }
                            break;
                        }
                    }
                }
                if ($this->bGetOnlyApproximationRates && !empty($this->aShippingRates)) {
                    $this->saveShippingQuotesCached();
                    return $this->aShippingRates;
                }
                $aResponses = $this->getServerQuotes($aShippingRates);
                if (!empty($aResponses)) {
                    foreach ($aResponses as $aResponse) {
                        foreach ($aResponse as $Rate) {
                            foreach ($aShippingRates as $oShippingRate) {
                                if (in_array($oShippingRate->getShippingEntity()->getField('service_code'), $this->ups_services[$Rate->Service->getCode()])) {
                                    if ($oShippingRate->getShippingId() != $this->ups_approximation_shipping_methods[$this->oManufacturer->m_country] ||
                                        ($oShippingRate->getShippingId() == $this->ups_approximation_shipping_methods[$this->oManufacturer->m_country] && empty($this->aShippingRates[$oShippingRate->getShippingId()]))
                                    ) {
                                        $weight = ceil($oShippingRate->getCartShippingWeight());
                                        if ($weight >= self::MAX_WEIGHT_FOR_UPS_PACKAGE) {
                                            $weight_multiplier = ($weight / self::MAX_WEIGHT_FOR_UPS_PACKAGE);
                                        } else {
                                            $weight_multiplier = 1;
                                        }
                                        $oShippingRate->setShippingChargeQuote(round($Rate->TotalCharges->MonetaryValue * $weight_multiplier + $this->getAdditionalShippingFee($weight), 2));
                                        //$oShippingRate->setAdditionalShippingCharge($this->getAdditionalShippingFee($weight));
                                        $this->aShippingRates[$oShippingRate->getShippingId()] = $oShippingRate;
                                    }
                                }
                            }
                        }
                    }
                }
                $this->saveShippingQuotesCached();
            }
        }

        return $this->aShippingRates;
    }

    public function getAdditionalShippingFee($weight)
    {
        global $config;
        $fAdditionalShippingFee = 0;
        if ($weight >= $config['Oversize_Package']['oversize_lg_threshold']) {
            $fAdditionalShippingFee = $config['Oversize_Package']['oversize_surcharge'];
        }
        return $fAdditionalShippingFee;
    }
}