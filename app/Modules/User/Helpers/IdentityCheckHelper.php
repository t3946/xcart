<?php

namespace Modules\User\Helpers;

class IdentityCheckHelper
{

    public static function getCurlContent($url, $headers, $query)
    {
        $query = http_build_query($query);

        $url .= "?{$query}";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        $data = curl_exec($curl);
        if (!$data){
            $data = curl_error($curl);
        }
        curl_close($curl);

        return $data;
    }

}