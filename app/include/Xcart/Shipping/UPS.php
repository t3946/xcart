<?php

namespace Xcart\Shipping;

use Exception;
use Modules\Shipping\Helpers\ShippingHelper;
use Ups\Entity\Address;
use Ups\Entity\Dimensions;
use Ups\Entity\Package;
use Ups\Entity\PackagingType;
use Ups\Entity\ShipFrom;
use Ups\Entity\Shipment;
use Ups\Entity\UnitOfMeasurement;
use Ups\Rate;
use Xcart\Logs;
use Xcart\ShippingRate;

class UPS extends ShippingProcessor
{
    const APPROXIMATION_MAX_VALID_TIME = 5184000; //2 months
    const MAX_WEIGHT_FOR_UPS_PACKAGE = 150; //2 months

    /*This table provides correct service codes for different origins
    <Code returned from UPS> => array (<origin> => <service_code in xcart_shipping>)*/
    private $ups_services = [
        '01' => ['US' => 5, 'CA' => 8, 'PR' => 5],
        '02' => ['US' => 1, 'PR' => 1],
        '03' => ['US' => 4, 'PR' => 4],
        '07' => ['US' => 16, 'EU' => 8, 'CA' => 16, 'PR' => 16, 'MX' => 8, 'OTHER_ORIGINS' => 16, 'PL' => 8],
        '08' => ['US' => 15, 'EU' => 13, 'CA' => 15, 'PR' => 15, 'MX' => 13, 'OTHER_ORIGINS' => 15, 'PL' => 13],
        '11' => ['US' => 14, 'EU' => 14, 'CA' => 14, 'PL' => 14],
        '12' => ['US' => 3, 'CA' => 3],
        '13' => ['US' => 7, 'CA' => 12],
        '14' => ['US' => 6, 'CA' => 9, 'PR' => 6],
        '54' => ['US' => 17, 'EU' => 17, 'PR' => 17, 'MX' => 11, 'OTHER_ORIGINS' => 17, 'PL' => 17],
        '59' => ['US' => 2],
        '65' => ['US' => 12, 'EU' => 12, 'CA' => 12, 'PR' => 12, 'MX' => 12, "OTHER_ORIGINS" => 12, 'PL' => 12],
        '82' => ['PL' => 18],
        '83' => ['PL' => 19],
        '84' => ['PL' => 20],
        '85' => ['PL' => 21],
        '86' => ['PL' => 22]
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
     * @param $aShippingRates
     * @return null|\Ups\Entity\RateResponse
     */
    public function getServerQuotes($aShippingRates)
    {
        /* get UPS Rates from server */
        $aResponses = null;
        if (!empty($aShippingRates)) {
            $oShippingRate = reset($aShippingRates);

            $UPS_username = 'b116b3e4cdf3';
            $UPS_password = 'f3ddbec6bf';
            $UPS_accesskey = '8C381B1AAE49E83E';

            $rate = new Rate($UPS_accesskey, $UPS_username, $UPS_password);

            try {
                $oCustomer = $this->getCustomer();

                $shipment = new Shipment();

                $shipperAddress = $shipment->getShipper()->getAddress();
                $shipperAddress->setPostalCode($this->getManufacturer()->m_zipcode);
                $shipperAddress->setCountryCode($this->getManufacturer()->m_country);

                $address = new Address();
                $address->setPostalCode($this->getManufacturer()->m_zipcode);
                $address->setCountryCode($this->getManufacturer()->m_country);
                $address->setCity($this->getManufacturer()->m_city);

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
                $shippingWeight = max ($shippingWeight , 1);

                $shipping_dimensions = $oShippingRate->getCartShippingDimentions();

                $package = new Package();
                $package->getPackagingType()->setCode(PackagingType::PT_PACKAGE);
                $package->getPackageWeight()->setWeight($shippingWeight);

                if ($shipping_dimensions) {
                    $dimensions = new Dimensions();
                    $dimensions->setLength(ceil($shipping_dimensions[0]));
                    $dimensions->setWidth(ceil($shipping_dimensions[1]));
                    $dimensions->setHeight(ceil($shipping_dimensions[2]));

                    $unit = new UnitOfMeasurement;
                    $unit->setCode(UnitOfMeasurement::UOM_IN);

                    $dimensions->setUnitOfMeasurement($unit);
                    $package->setDimensions($dimensions);
                }

                $shipment->addPackage($package);
                $aResponses = $rate->shopRates($shipment);

            } catch (Exception $e) {
                Logs::_log(Logs::LOG_RESOURCE_SHIPPING_QUOTES, time(), Logs::LOG_TYPE_SYSTEM, __CLASS__ . ': ' . $e->getMessage());
            }
        }

        return $aResponses;
    }

    public function getShippingQuotes()
    {
        /** @var ShippingRate[] $aShippingRates */
        if (!$this->aShippingRates && $aShippingRates = $this->getShippingRatesEntities()) {
            if ($aResponses = $this->getServerQuotes($aShippingRates)) {
                foreach ($aResponses as $aResponse) {
                    foreach ($aResponse as $Rate) {
                        foreach ($aShippingRates as $oShippingRate) {
                            if (in_array($oShippingRate->getShippingEntity()->service_code, $this->ups_services[$Rate->Service->getCode()])) {
                                if ($oShippingRate->shippingid != $this->ups_approximation_shipping_methods[$this->oManufacturer->m_country] ||
                                    ($oShippingRate->shippingid == $this->ups_approximation_shipping_methods[$this->oManufacturer->m_country] && empty($this->aShippingRates[$oShippingRate->shippingid]))
                                ) {

                                    $value = $Rate->TotalCharges->MonetaryValue;
                                    if ($Rate->TotalCharges->CurrencyCode ==='CAD') {
                                        $value *= 0.77; //TODO multi currency support
                                    }

                                    $oShippingRate->setShippingChargeQuote(round($value,  2));

                                    if (((int)$oShippingRate->shippingid === 1) &&
                                        $app_s = ShippingHelper::getApproximateShippingCharge($oShippingRate->getShippingQuote(), $this->oManufacturer->manufacturerid))
                                    {
                                        $oShippingRate->setShippingCharge($app_s);
                                    }
                                    $this->aShippingRates[$oShippingRate->shippingid] = $oShippingRate;
                                }
                            }
                        }
                    }
                }
            }
            $this->saveShippingQuotesCached();
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