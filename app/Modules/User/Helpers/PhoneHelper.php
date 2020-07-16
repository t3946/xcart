<?php


namespace Modules\User\Helpers;


class PhoneHelper
{
    public static function getGooglePhone($phone, $phone_ext): array
    {
        $userinfo_area_code = '';
        $google_phone = preg_replace('/[^0-9]/S', '', $phone);
        $google_phone_strlen = strlen($google_phone);

        if ($google_phone_strlen === 11 && (int)$google_phone[0] === 1) {
            $google_phone[0] = '';
            $google_phone = trim($google_phone);
            $google_phone_strlen = strlen($google_phone);
        }

        if ($google_phone_strlen >= 10) {
            $tmp_counter = 0;
            $google_phone_new = '';
            for ($i = $google_phone_strlen; $i >= 0; $i--) {
                if ($tmp_counter > 7 && $tmp_counter <= 10) {
                    $userinfo_area_code = $google_phone[$i] . $userinfo_area_code;
                }
                $google_phone_new = $google_phone[$i] . $google_phone_new;
                if ($tmp_counter === 4) {
                    $google_phone_new = '-' . $google_phone_new;
                }
                if ($tmp_counter === 7) {
                    $google_phone_new = ') ' . $google_phone_new;
                }
                if ($tmp_counter === 10) {
                    $google_phone_new = '(' . $google_phone_new;
                    if ($google_phone_strlen > 10) {
                        $google_phone_new = '] ' . $google_phone_new;
                    }
                }
                $tmp_counter++;
            }
            if ($google_phone_strlen > 10) {
                $google_phone_new = '[+' . $google_phone_new;
                $google_phone_new = urlencode($google_phone_new);
            }
            $google_phone = $google_phone_new;
        }

        $google_phone .= ($phone_ext ? " ext {$phone_ext}" : '');
        return [$userinfo_area_code, str_replace(' ', '+', $google_phone)];
    }

    public static function phoneNormalize($phone): string
    {
        return preg_replace('/[^0-9]/S','', $phone);
    }

    public static function getPhonePrefix($country): string
    {
        switch($country) {
            case 'US':
            case 'CA':
                $prefix = '+1';
                break;
        }
        return $prefix ?? '';
    }

    public static function getPhoneNormalized($phone, $country)
    {
        if (strlen($phone_normalized = PhoneHelper::phoneNormalize($phone)) === 10){
            return PhoneHelper::getPhonePrefix($country) . $phone_normalized;
        }
        return $phone;
    }
}