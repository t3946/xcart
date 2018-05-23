<?php

namespace Modules\GeoIp\Helpers;


use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Core\Models\StateModel;
use Modules\Core\Models\TelephoneAreaModel;
use Modules\GeoIp\Models\GeoipLitecityLocationModel;
use Modules\Sites\Models\SiteConfigModel;

class GeoIpHelper
{

    public static $geoIp;
    /**
     * @param string $ip
     * @return GeoipLitecityLocationModel|null
     */
    public static function getGeoipLocation2($ip)
    {
        $model = null;

        if (self::$geoIp === null) {
            try {

                $reader = new Reader(__DIR__ . '/../GeoLite2/GeoLite2-City.mmdb');
                $result = $reader->city($ip);
                $model = new GeoipLitecityLocationModel(
                    [
                        'country' => $result->country->isoCode,
                        'region' => $result->mostSpecificSubdivision->isoCode,
                        'city' => $result->city->name,
                        'postalCode' => $result->postal->code,
                    ]
                );
                self::$geoIp = $model;

            } catch (AddressNotFoundException $addressNotFoundException) {

            } catch (\Exception $addressNotFoundException) {

            }
        }

        return self::$geoIp;
    }

    public static function getGeoipLocation($ip)
    {
        return self::getGeoipLocation2($ip);
    }

    public static function getAreaCodeFromPhone($phone)
    {
        $area = null;

        if (preg_match('/\d{3}/', $phone, $match)) {
            $area = $match[0];
        }

        return $area;
    }

    /**
     * @param $phone
     * @return null|StateModel
     */
    public static function getStateByPhone($phone)
    {
        /** @var StateModel $model */
        $model = null;

        if ($area = static::getAreaCodeFromPhone($phone)) {
            if ($areaModel = TelephoneAreaModel::objects()->get(['area_code' => $area])) {
                if (array_key_exists($areaModel->country, CountryModel::$codes)) {
                    $model = StateModel::objects()
                        ->filter(
                            [
                                'country_code' => CountryModel::$codes[$areaModel->country],
                                'state' => $areaModel->state,
                            ]
                        )
                        ->limit(1)
                        ->get();
                }
            }
        }

        return $model;
    }

    public static function getPhones($params)
    {
        $state = $orderState = null;

        if ($params['state'] && $params['country']) {
            $state = StateModel::objects()->get(
                [
                    'code' => $params['state'],
                    'country_code' => $params['country']
                ]
            );
        }

        if ($params['phone']) {
            $orderState = static::getStateByPhone($params['phone']);
        }

        $phones = ($state ? $state->phone : '') . ($orderState ? ($state->phone && $orderState->phone ? ', ' : '') . $orderState->phone : '');

        if (empty($phones)) {
            $phones = $params['storefrontid']
                ? SiteConfigModel::objects()->get(['name' => 'cidev_top_header_code', 'storefrontid' => $params['storefrontid']])->value
                : GlobalConfigModel::objects()->get(['name' => 'cidev_top_header_code'])->value;
        }

        return $phones;
    }
}