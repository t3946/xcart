<?php


namespace Modules\Core\Classes;


use GuzzleHttp\Client;

class MelissaAPI
{
    private string $melissa_key = 'lBRkrbaK8DVVghZCwkUO2k**nSAcwXpxhQ0PC2lXxuDAZ-**';
    private Client $client;
    public array $melissa_address = [];
    public $phone_data = [];
    public $email_data = [];
    public $ip_data = [];

    public function __construct()
    {
        $this->client = new Client(['verify' => false, 'timeout' => 10]);
    }

    public function setMelissaAddressByList($address_list = [])
    {
        foreach ($address_list as $key => $attr) {
            $result = $this->fetchMelissaAddress([
                'address' => $attr['address'],
                'city' => $attr['city'],
                'state' => $attr['state'],
                'country' => $attr['country'],
                'zipcode' => $attr['zipcode'],
            ]);
            if (isset($result['MAK'])) {
                $this->melissa_address[$key]['address'] = $result;
                $owner_info = $this->fetchMelissaAddressOwner($result['MAK']);
                if (!isset($owner_info['Value1'])) {
                    $this->melissa_address[$key]['owner'] = $owner_info;
                }
            } else {
                $this->melissa_address[$key] = null;
            }
        }
    }

    /** Устанавливает один адрес для разных ключей адресов(способ исключения лишних запросов при схожих адресов)
     * @param array $address - адрес с параметрами: address, city, state, country, zipcode
     * @param array $many_address - ключи куда будут записываться адреса, напр: [billing, shipping]
     */
    public function setOneMelissaAddressForMany(array $address, array $many_address)
    {
        $result = $this->fetchMelissaAddress($address);
        $owner_info = $this->fetchMelissaAddressOwner($result['MelissaAddressKey']);
        foreach ($many_address as $name_address) {
            if (!isset($result['Value1'])) {
                $this->melissa_address[$name_address]['address'] = $result;
                if (!isset($owner_info['Value1'])) {
                    $this->melissa_address[$name_address]['owner'] = $owner_info;
                };
            } else {
                $this->melissa_address[$name_address] = null;
            }
        }
    }

    public function setMelissaEmailInfo(string $email)
    {
        $email_info = $this->fetchMelissaEmail($email);
        if (!isset($email_info['Value1'])) {
            $this->email_data = $email_info;
        }
    }

    public function setMelissaPhoneInfo(string $phone)
    {
        $phone_info = $this->fetchMelissaPhone($phone);
        if (!isset($phone_info['Value1'])) {
            $this->phone_data = $phone_info;
        } else $this->phone_data = null;
    }

    public function setMelissaIpInfo(string $ip)
    {
        $ip_info = $this->fetchMelissaIp($ip);
        if (!isset($ip_info['Value1'])) {
            $this->ip_data = $ip_info;
        } else {
            $this->ip_data = null;
        }
    }

    private function fetchMelissaPhone($phone)
    {
        $params = [
            'phone' => $phone,
            'fmt' => 'json',
            'id' => $this->melissa_key
        ];
        $url = 'https://www.melissa.com/v2/lookups/phonecheck/';
        if ($response = $this->client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
        return null;
    }

    private function fetchMelissaEmail($email)
    {
        $params = [
            'emailAddress' => $email,
            'fmt' => 'json',
            'id' => $this->melissa_key
        ];
        $url = 'https://www.melissa.com/v2/lookups/personator/';
        if ($response = $this->client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
        return null;
        //throw new \Exception('Melissa has no answer');
    }

    private function fetchMelissaAddress($address)
    {
        $params = [
            'freeForm' => self::correct("{$address['address']} {$address['city']} {$address['state']} {$address['country']} {$address['zipcode']}"),
            'fmt' => 'json',
            'id' => $this->melissa_key
        ];
        $url = 'https://www.melissa.com/v2/lookups/addresscheck/address/';
        if ($response = $this->client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
        return null;
        //throw new \Exception('Melissa has no answer');
    }

    private function fetchMelissaPersonal($info)
    {
        $params = [
            'fullName' => $info['full_name'] ?? '',
            'state' => $info['state'] ?? '',
            'city' => $info['city'] ?? '',
            'addressLine1' => $info['address'] ?? '',
            'melissaAddressKey' => $info['mak'] ?? '',
            'postalCode' => $info['zipcode'] ?? '',
            'fmt' => 'json',
            'id' => $this->melissa_key
        ];
        $url = 'https://www.melissa.com/v2/lookups/personator/';
        if ($response = $this->client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
        return null;
    }

    private function fetchMelissaIp($ip)
    {
        $params = [
            'ip' => $ip,
            'fmt' => 'json',
            'id' => $this->melissa_key
        ];
        $url = 'https://www.melissa.com/v2/lookups/iplocation/ip/';
        if ($response = $this->client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
        //throw new \Exception('Melissa has no answer');
    }

    private function fetchMelissaAddressOwner($mak)
    {
        $params = [
            'mak' => $mak,
            'fmt' => 'json',
            'id' => $this->melissa_key
        ];
        $url = 'https://www.melissa.com/v2/lookups/property/';
        if ($response = $this->client->request('GET', $url, ['query' => $params])) {
            if ($res = json_decode($response->getBody(), true)) {
                $res = reset($res);
                return $res;
            }
        }
        return null;
    }

    private function correct($field): string
    {
        $field = trim($field);
        $field = preg_replace('/\s+/', ' ', $field);
        $field = preg_replace("/[^\w\s\[,.\-\/@_\]]/", '', $field);
        $field = strtoupper($field);
        return $field;
    }
}