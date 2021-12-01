<?php

namespace Modules\Core\Classes;

use Exception;
use SoapClient;

class SoapClientTimeout extends SoapClient
{
    private int $timeout;

    public function setTimeout(int $timeout): void
    {
        if (!is_int($timeout) && !is_null($timeout))
        {
            throw new Exception("Invalid timeout value");
        }

        $this->timeout = $timeout;
    }

    public function __doRequest($request, $location, $action, $version, $oneWay = false)
    {
        if (!$this->timeout)
        {
            // Call via parent because we require no timeout
            $response = parent::__doRequest($request, $location, $action, $version, $oneWay);
        }
        else
        {
            // Call via Curl and use the timeout
            $curl = curl_init($location);

            curl_setopt($curl, CURLOPT_VERBOSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);

            $response = curl_exec($curl);

            if (curl_errno($curl))
            {
                throw new Exception(curl_error($curl));
            }

            curl_close($curl);
        }

        // Return?
        if (!$oneWay)
        {
            return ($response);
        }
    }
}