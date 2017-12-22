<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/21/2017
 * Time: 4:36 PM
 */

namespace Modules\User\Helpers;

class IdentityCheckHelper
{

    public static function getCurlContent($url, $headers, $query)
    {
        $curl = curl_init();

        $query = http_build_query($query);

        $url .= "?{$query}";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);


        $data = curl_exec($curl);
        if (!$data){
            $data = curl_error($curl);
        }
        curl_close($curl);

        return $data;
    }

}