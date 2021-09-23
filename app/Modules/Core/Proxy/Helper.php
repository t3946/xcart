<?php

namespace Modules\Core\Proxy;

final class Helper {

    public static function validateProxyIpPort($string) {
        if (preg_match('/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})\:?([0-9]{1,5})?/', $string)) {
            return true;
        }
        return false;
    }

    public static function getProxyIpPort($string) {
        $data = [];
        if (preg_match('/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})\:?([0-9]{1,5})?/', $string, $data)) {
            return ['ip' => $data['1'], 'port' => isset($data['2']) ? $data['2'] : ''];
        }
        return false;
    }

}
