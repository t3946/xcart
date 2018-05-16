<?php

namespace Modules\Subscribe\Helpers;

class SubscribeHelper
{

    public static function getHashData($email, $code)
    {
        return md5($email.$code);
    }

}