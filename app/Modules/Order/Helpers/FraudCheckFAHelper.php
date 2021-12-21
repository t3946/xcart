<?php


namespace Modules\Order\Helpers;


use Exception;
use Modules\Core\Classes\MelissaAPI;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\FraudFAQuestionModel;
use Modules\Core\Models\StateModel;
use Modules\Order\Models\OrderAddressGeolocation;
use Modules\Order\Models\OrderAddressType;
use Modules\Order\Models\OrderModel;

class FraudCheckFAHelper
{
    private OrderModel $order_model;
    public MelissaAPI $ob_melissa;
    private const ADDITIONAL_INFO_NULL_CHECK = 'N/A';
    public bool $test_data = false; // Если нужно просто протестировать без запроса в мелису

    public function __construct(OrderModel $oder_model)
    {
        $this->order_model = $oder_model;
        $this->ob_melissa = new MelissaAPI();
    }

    /* Melissa data order */
    public function fetchBaseDataOrder(): void
    {
        if (!$this->test_data) {
            $this->fetchMelissaPhoneInfo();
            $this->fetchMelissaAddressList();
            $this->fetchMelissaEmailInfo();
            $this->fetchMelissaIP();
        } else {
            $this->ob_melissa->phone_data = null;
            $this->ob_melissa->ip_data = null;
            $this->ob_melissa->email_data = null;
            $this->ob_melissa->melissa_address['shipping'] = null;
            $this->ob_melissa->melissa_address['billing'] = null;
        }
    }

    /** Устанавливает значения адреса для shipping, billing адресов из мелиссы **/
    public function fetchMelissaAddressList(): void
    {
        $outcome_addresses = round($this->compareShippingBillingAddress() / 6, 2);
        if ((int)$outcome_addresses === 1) {
            // Если shipping и billing адреса равны, то 1 запрос вместо 2
            $this->ob_melissa->setOneMelissaAddressForMany([
                'address' => $this->order_model->b_address,
                'city' => $this->order_model->b_city,
                'state' => $this->order_model->b_state,
                'country' => $this->order_model->b_country,
                'zipcode' => $this->order_model->b_zipcode
            ], ['billing', 'shipping']);
        } else {
            $this->ob_melissa->setMelissaAddressByList(
                [
                    'billing' => [
                        'address' => $this->order_model->b_address,
                        'city' => $this->order_model->b_city,
                        'state' => $this->order_model->b_state,
                        'country' => $this->order_model->b_country,
                        'zipcode' => $this->order_model->b_zipcode
                    ],
                    'shipping' => [
                        'address' => $this->order_model->s_address,
                        'city' => $this->order_model->s_city,
                        'state' => $this->order_model->s_state,
                        'country' => $this->order_model->s_country,
                        'zipcode' => $this->order_model->s_zipcode
                    ]
                ]
            );
        }

    }

    public function fetchMelissaPhoneInfo(): void
    {
        $this->ob_melissa->setMelissaPhoneInfo($this->order_model->phone);
    }

    private function fetchMelissaEmailInfo(): void
    {
        $this->ob_melissa->setMelissaEmailInfo($this->order_model->email);
    }

    private function fetchMelissaIP(): void
    {
        if (!is_null($this->order_model->extra_model)) {
            $ip = $this->order_model->extra_model->getIP();
            if (!is_null($ip)) {
                $this->ob_melissa->setMelissaIpInfo($ip);

                if (!empty($this->ob_melissa->ip_data)) {
                    $extra_model = $this->order_model->extra_model;
                    $extra_model->longitude = $this->ob_melissa->ip_data['Longitude'];
                    $extra_model->latitude = $this->ob_melissa->ip_data['Latitude'];
                    $extra_model->save();
                }
                return;
            }
        }
        $this->ob_melissa->ip_data = null;
    }

    /* Address check */
    public function scoreBaseAddress(FraudFAQuestionModel $fraud): array
    {
        $coefficient = $this->compareShippingBillingAddress();

        $ar_s_address = self::getLinesAddress($this->order_model->s_address);
        $ar_b_address = self::getLinesAddress($this->order_model->b_address);

        $info = $this->getInfoAddress(
            $fraud,
            [
                'street1' => $ar_s_address[0] ?? $this->order_model->s_address,
                'street2' => $ar_s_address[1] ?? '',
                'state' => $this->order_model->s_state,
                'city' => $this->order_model->s_city,
                'zipcode' => $this->order_model->s_zipcode,
                'country' => $this->order_model->s_country ?? '',
            ],
            [
                'street1' => $ar_b_address[0] ?? $this->order_model->s_address,
                'street2' => $ar_b_address[1] ?? '',
                'state' => $this->order_model->b_state,
                'city' => $this->order_model->b_city,
                'zipcode' => $this->order_model->b_zipcode,
                'country' => $this->order_model->b_country ?? '',
            ],
        );

        return [$fraud->weight, $info, $coefficient];
    }

    private function compareShippingBillingAddress(): float
    {
        $ar_s_address = self::getLinesAddress($this->order_model->s_address);
        $ar_b_address = self::getLinesAddress($this->order_model->b_address);
        return $this->compareAddress(
            [
                'country' => $this->order_model->s_country,
                'state' => $this->order_model->s_state,
                'city' => $this->order_model->s_city,
                'zipcode' => $this->order_model->s_zipcode,
                'street1' => $ar_s_address[0] ?? $this->order_model->s_address,
                'street2' => $ar_s_address[1] ?? '',
            ],
            [
                'country' => $this->order_model->b_country,
                'state' => $this->order_model->b_state,
                'city' => $this->order_model->b_city,
                'zipcode' => $this->order_model->b_zipcode,
                'street1' => $ar_b_address[0] ?? $this->order_model->b_address,
                'street2' => $ar_b_address[1] ?? '',
            ]);
    }

    public function scoreOwnerResidenceAddress(FraudFAQuestionModel $fraud, ?array $address_compare, string $type_address = 'shipping'): array
    {
        $owner_info = $this->ob_melissa->melissa_address[$type_address]['owner'] ?? null;
        $info = $this->getNullDataInfo($fraud, $address_compare, null);
        $coefficient = 0;
        if (isset($owner_info, $address_compare)) {
            $zip = trim($owner_info['OwnerZip']);
            $zip = substr($zip, 0, 5);
            $owner_address_info = [
                'country' => 'US',
                'state' => $owner_info['OwnerState'],
                'city' => $owner_info['OwnerCity'],
                'zipcode' => $zip,
                'street1' => $owner_info['OwnerAddress'],
            ];
            $info = $this->getInfoAddress(
                $fraud,
                $address_compare,
                $owner_address_info,
            );
            $coefficient = $this->compareAddress($address_compare, $owner_address_info);
        }
        return [$fraud->weight, $info, $coefficient];
    }

    public static function getStringAddressByArray(?array $address): ?string
    {
        if (isset($address['state'], $address['city'], $address['zipcode'])) {
            $normalize_address = array_filter($address);
            $str_address = implode(', ', $normalize_address);
            return trim($str_address);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function collectAddressesGeolocation(): void
    {
        /** @var OrderAddressType $address_type */
        foreach (OrderAddressType::objects()->all() as $address_type) {
            $field_latitude = 'Latitude';
            $field_longitude = 'Longitude';
            $type = $address_type->code;
            switch ($type) {
                case OrderAddressType::ADDRESS_TYPE_BILLING:
                case OrderAddressType::ADDRESS_TYPE_SHIPPING:
                    $address_info = $this->ob_melissa->melissa_address[$type]['address'];
                    break;
                case OrderAddressType::ADDRESS_TYPE_OWNER_BILLING:
                case OrderAddressType::ADDRESS_TYPE_OWNER_SHIPPING:
                    [$owner, $type_name] = explode('_', $type);
                    $address_info = $this->ob_melissa->melissa_address[$type_name][$owner];
                    break;
                case OrderAddressType::ADDRESS_TYPE_IP_LOCATION:
                    $address_info = $this->ob_melissa->ip_data;
                    break;
                case OrderAddressType::ADDRESS_TYPE_PHONE_LOCATION:
                    $address_info = $this->ob_melissa->phone_data;
                    $field_latitude = 'Lat';
                    $field_longitude = 'Lng';
                    break;
            }
            if (!empty($address_info)) {
                // Валидация элементов(широта и долгота) в объекте
                foreach ([$field_longitude, $field_latitude] as $type_location) {
                    if (empty($address_info[$type_location]) || !is_numeric($address_info[$type_location])) {
                        continue 2;
                    }
                }
                $longitude = (float)$address_info[$field_latitude];
                $latitude = (float)$address_info[$field_longitude];
                $this->saveGeolocationData($address_type, $latitude, $longitude);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function saveGeolocationData(OrderAddressType $type_model, float $latitude, float $longitude): void
    {
        $geolocation_model = new OrderAddressGeolocation([
            'order_id' => $this->order_model->pk,
            'address_type_id' => $type_model->pk,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ]);
        $geolocation_model->save();
    }

    public function scorePhoneAddress(FraudFAQuestionModel $fraud, ?array $address_compare): array
    {
        $coefficient = 0;
        $info = $this->getNullDataInfo($fraud, $address_compare, null);
        if (!is_null($this->ob_melissa->phone_data)) {
            $state = $this->ob_melissa->phone_data['State'] ?? '';
            /** @var CountryModel $country_model */
            $country_model = CountryModel::objects()->get(['name' => $this->ob_melissa->phone_data['CountryName']]);
            if (!is_null($country_model)) {
                /** @var StateModel $state */
                $state = StateModel::objects()->get(['state' => $this->ob_melissa->phone_data['State'], 'country_code' => $country_model->code]);
            }
            $phone_address = [
                'country' => $this->ob_melissa->phone_data['CountryCode'],
                'state' => $state->code,
                'city' => $this->ob_melissa->phone_data['City'],
                'zipcode' => $this->ob_melissa->phone_data['PostalCode']
            ];
            $info = $this->getInfoAddress($fraud, $address_compare ?? [], $phone_address);
            if ($address_compare) {
                $coefficient = $this->compareAddress($address_compare, $phone_address);
            }
        }
        return [$fraud->weight, $info, $coefficient];
    }

    public function scoreIpAddress(FraudFAQuestionModel $fraud, ?array $address_compare): array
    {
        $coefficient = 0;
        $info = $this->getNullDataInfo($fraud, $address_compare, null);
        if (!is_null($this->ob_melissa->ip_data)) {
            /** @var CountryModel $country_model */
            $country_model = CountryModel::objects()->get(['name' => $this->ob_melissa->ip_data['Country']]);
            if (!is_null($country_model)) {
                $state = StateModel::objects()->get(['state' => $this->ob_melissa->ip_data['State'], 'country_code' => $country_model->code]);
            }
            $email_address = [
                'country' => $country_model->code,
                'state' => $state->code ?? $this->ob_melissa->ip_data['State'],
                'city' => $this->ob_melissa->ip_data['City'],
                'zipcode' => $this->ob_melissa->ip_data['PostalCode']
            ];
            $info = $this->getNullDataInfo($fraud, $address_compare ?? [], $email_address);
            if ($address_compare) {
                $coefficient = $this->compareAddress($address_compare, $email_address);
            }
        }

        return [$fraud->weight, $info, $coefficient];
    }

    /** Сравнивает два адреса по атрибутам, если они одинаковые то добавит значение к коэффициенту.
     *  Коэффициент = Количество одинаковых атрибутов в адресе / Общее количество атрибутов
     * @param array $compare_address - первый адрес в формате массива
     * @param array $addressData - второй адрес в формате массива
     * @return float
     */
    public function compareAddress(array $compare_address, array $addressData): float
    {
        if ((!$compare_address = array_filter($compare_address)) || (!$addressData = array_filter($addressData))) {
            return 0;
        }
        $coefficient = 0;
        foreach ($compare_address as $attr => $value) {
            $correct_compare_address = BaseFraudCheckHelperV2::addressAbbreviationsPrepare(strtoupper(trim($value)));
            $correct_data_address = BaseFraudCheckHelperV2::addressAbbreviationsPrepare(strtoupper(trim($addressData[$attr])));
            if ($correct_compare_address === $correct_data_address) {
                $coefficient++;
            } else {
                break;
            }
        }
        if (($coefficient === 5) && empty($compare_address['street2']) && empty($addressData['street2'])) {
            $coefficient++;
        }
        return $coefficient;
    }

    /* Full name check */

    private function compareOwnerAddress(string $name_address, $full_name): array
    {
        $best_owner = '';
        $best_outcome = 0;
        $best_compare = '';
        foreach (['OwnerName1Full', 'OwnerName2Full'] as $owner_type) {
            if ($name_owner = $this->ob_melissa->melissa_address[$name_address]['owner'][$owner_type]) {
                if (is_array($full_name)) {
                    [$outcome, $best_compare_name] = self::compareManyName(array_filter($full_name), $name_owner);
                } else {
                    $outcome = self::compareClientName($full_name, trim($name_owner));
                    $best_compare_name = $full_name;
                }
                if ($outcome >= $best_outcome) {
                    $best_outcome = $outcome;
                    $best_owner = $name_owner;
                    $best_compare = $best_compare_name;
                }
            }
        }
        return [$best_outcome, $best_owner, $best_compare];
    }


    private static function compareTelephoneClientName(string $f_full_name, string $t_full_name): bool
    {
        $ar_f = explode(' ', $f_full_name);
        $ar_t = explode(' ', $t_full_name);

        $t_reverse = array_reverse(explode(' ', $t_full_name));
        // Если имени в последнем параметр имени одинаковый с последним параметром перевернутого имени, то true
        if (count($t_reverse) > 1) {
            foreach ([$ar_t, $t_reverse] as $name) {
                if ((strtoupper($ar_f[count($ar_f) - 1]) === strtoupper($name[count($name) - 1]))
                    || soundex($ar_f[count($ar_f) - 1]) === soundex($name[count($name) - 1])) {
                    return true;
                }
            }
        }
        return self::compareClientName($f_full_name, $t_full_name);
    }

    /** Парсит полное имя и сравнивает фамилию(последний элемент) в формате upperCase со вторым
     * @param string $f_full_name - Полное имя первого
     * @param string $t_full_name - Полное имя второго
     * @return bool
     */
    private static function compareClientName(string $f_full_name, string $t_full_name): bool
    {
        if (empty(trim($f_full_name)) || empty(trim($t_full_name))) {
            return false;
        }
        $ar_f = explode(' ', $f_full_name);
        $ar_t = explode(' ', $t_full_name);

        $unique_attr = array_intersect($ar_f, $ar_t);
        if (count($unique_attr) === count($ar_t)) {
            return true;
        }

        return strtoupper($ar_f[count($ar_f) - 1]) === strtoupper($ar_t[count($ar_t) - 1])
            || soundex($ar_f[count($ar_f) - 1]) === soundex($ar_t[count($ar_t) - 1]);
    }

    public function scorePhoneCaller(FraudFAQuestionModel $fraud, ?array $compare_info): array
    {
        $outcome = 0;
        $caller_name = $this->ob_melissa->phone_data['CallerID'];
        if (!$caller_name) { // Если имя владельца телефона не известно
            $info = $this->getInfoFN($fraud,
                array_merge($compare_info, ['value' => self::getFrontendValue($compare_info['value'])]),
                ['value' => '']
            );
            return [$fraud->weight, $info, 0];
        }
        $name = $compare_info['value'];
        if ($compare_info['value']) {
            if (is_array($compare_info['value'])) {
                [$outcome, $name] = self::compareManyName(array_filter($compare_info['value']), $caller_name, true);
            } else {
                $outcome = self::compareTelephoneClientName($compare_info['value'], $caller_name);
            }
        }
        $info = $this->getInfoFN($fraud,
            array_merge($compare_info, ['value' => $name]),
            ['value' => $caller_name]
        );
        return [$fraud->weight, $info, $outcome];

    }

    public function scoreCardHolder(FraudFAQuestionModel $fraud, ?array $compare_name): array
    {
        $outcome = 0;
        $info = $this->getInfoFN(
            $fraud,
            $compare_name,
            ['value' => $this->order_model->b_firstname, 'zipcode' => $this->order_model->b_zipcode]
        );
        if ($this->order_model->b_firstname && !empty($compare_name['value'])) {
            $outcome = self::compareClientName($compare_name['value'], $this->order_model->b_firstname);
        }
        return [$fraud->weight, $info, $outcome];
    }

    public function scoreBaseName(FraudFAQuestionModel $fraud, ?array $names = []): array
    {
        $outcome = 0;
        $info = $this->getInfoFN($fraud, $names[0], $names[1]);
        // Если какое-либо из имён имеет значение null, то его нельзя сравнить
        foreach ($names as $name) {
            if (is_null($name['value'])) {
                return [null, $fraud->weight, $info, 0];
            }
        }
        $compare = self::compareClientName($names[0]['value'], $names[1]['value']);
        if ($compare) {
            $outcome = 1;
        }
        return [$fraud->weight, $info, $outcome];
    }

    public function scoreTenantAddress(FraudFAQuestionModel $fraud, ?array $compare_name, string $type_address = 'shipping'): array
    {
        $outcome = 0;
        $tenant_name = $this->ob_melissa->melissa_address[$type_address]['address']['NameFull'];

        if (!$tenant_name) { // Если Tenant не указан
            $info = $this->getInfoFN(
                $fraud,
                ['value' => is_array($compare_name['value']) ? $compare_name['value'][0] : $compare_name['value'], 'zipcode' => $compare_name['zipcode']],
                ['value' => $tenant_name, 'zipcode' => $this->ob_melissa->melissa_address[$type_address]['address']['PostOfficeZip']]
            );
            return [$fraud->weight, $info, 0];
        }

        $name_value = $compare_name['value'];
        if ($compare_name['value']) {
            if (is_array($compare_name['value'])) { // Если сравнение с владельцами адреса(т.к их много)
                [$outcome, $name_value] = self::compareManyName(array_filter($compare_name['value']), $tenant_name);
            } else {
                $outcome = self::compareClientName($compare_name['value'], $tenant_name);
            }
        }
        $info = $this->getInfoFN(
            $fraud,
            ['value' => $name_value, 'zipcode' => $compare_name['zipcode']],
            ['value' => $tenant_name, 'zipcode' => $this->ob_melissa->melissa_address[$type_address]['address']['PostOfficeZip']]
        );
        return [$fraud->weight, $info, $outcome];
    }


    private static function compareManyName(array $compare_names, string $full_name, bool $phone_compare = false): array
    {
        $outcome = 0;
        $best_result_name = '';
        if (empty($compare_names)) {
            return [$outcome, null];
        }
        foreach ($compare_names as $name_item) {
            if ($phone_compare) {
                $compare_outcome = self::compareTelephoneClientName($name_item, $full_name);
            } else {
                $compare_outcome = self::compareClientName($name_item, $full_name);
            }
            if ($compare_outcome >= $outcome) {
                $outcome = $compare_outcome;
                $best_result_name = $name_item;
            }
        }
        return [$outcome, $best_result_name];
    }

    public function scoreOwnerAddress(FraudFAQuestionModel $fraud, ?array $compare_name, string $type_address = 'shipping'): array
    {
        $outcome = 0;
        $owner_data = $this->ob_melissa->melissa_address[$type_address]['owner'];
        if (!$owner_data) {
            $info = $this->getInfoFN(
                $fraud,
                array_merge($compare_name, ['value' => self::getFrontendValue($compare_name['value'])]),
                ['value' => '']
            );
            return [$fraud->weight, $info, 0];
        }
        $zip_owner = $owner_data['OwnerZip'];
        $zip = substr($zip_owner, 0, 5);

        if ($name = $compare_name['value']) {
            [$outcome, $owner_name, $name] = $this->compareOwnerAddress($type_address, $compare_name['value']);
        }

        $info = $this->getInfoFN(
            $fraud,
            array_merge($compare_name, ['value' => $name ?? '']),
            ['value' => $owner_name ?? '', 'zipcode' => $zip]
        );
        return [$fraud->weight, $info, $outcome];
    }

    public function scoreEmailAddress(FraudFAQuestionModel $fraud, ?array $compare_name): array
    {
        $outcome = 0;
        $email_name = $this->ob_melissa->email_data['NameFull'];
        if (!$email_name) {
            $info = $this->getInfoFN(
                $fraud,
                array_merge($compare_name, ['value' => self::getFrontendValue($compare_name['value'])]),
                ['value' => $email_name]
            );
            return [$fraud->weight, $info, 0];
        }
        $name = $compare_name['value'];
        if ($compare_name['value']) {
            if (is_array($compare_name['value'])) {
                [$outcome, $name] = self::compareManyName(array_filter($compare_name['value']), $email_name);
            } else {
                $outcome = self::compareClientName($compare_name['value'], $email_name);
            }
        }
        $info = $this->getInfoFN(
            $fraud,
            array_merge($compare_name, ['value' => $name]),
            ['value' => $this->ob_melissa->email_data['NameFull']]
        );
        return [$fraud->weight, $info, $outcome];
    }

    private function getInfoFN(FraudFAQuestionModel $question_model, array $f_value, array $t_value): array
    {
        return [
            "value{$question_model->f_fraud->code}" => !empty(trim($f_value['value']))
                ? [
                    'full_name' => $f_value['value'],
                    'zip' => !empty($f_value['zipcode']) ? $f_value['value'] : ''
                ]
                : self::ADDITIONAL_INFO_NULL_CHECK,
            "value{$question_model->t_fraud->code}" => !empty(trim($t_value['value']))
                ? [
                    'full_name' => $t_value['value'],
                    'zip' => !empty($t_value['zipcode']) ? $t_value['zipcode'] : ''
                ]
                : self::ADDITIONAL_INFO_NULL_CHECK
        ];
    }

    /** Вывод данных в поле additional_info для проверок адреса
     * @param FraudFAQuestionModel $question_model - модель вопроса, чтобы определить что с чем сравнивается
     * @param array $f_value
     * @param array $t_value
     * @return array[]|string[]
     */
    private function getInfoAddress(FraudFAQuestionModel $question_model, array $f_value, array $t_value): array
    {
        return [
            "value{$question_model->f_fraud->code}" => self::getStringAddressByArray($f_value)
                ? $this->formatAdditional($f_value)
                : self::ADDITIONAL_INFO_NULL_CHECK,
            "value{$question_model->t_fraud->code}" => self::getStringAddressByArray($t_value)
                ? $this->formatAdditional($t_value)
                : self::ADDITIONAL_INFO_NULL_CHECK
        ];
    }

    /** Вывод массива пустых значений в поле additional info
     * @param FraudFAQuestionModel $question_model - модель вопроса, чтобы узнать что с чем сравнивается.
     * @param array|null $from_value
     * @return array
     */
    private function getNullDataInfo(FraudFAQuestionModel $question_model, ?array $from_value, ?array $to_value): ?array
    {
        return [
            "value{$question_model->f_fraud->code}" => $from_value ? $this->formatAdditional($from_value) : self::ADDITIONAL_INFO_NULL_CHECK,
            "value{$question_model->t_fraud->code}" => $to_value ? $this->formatAdditional($to_value) : self::ADDITIONAL_INFO_NULL_CHECK
        ];
    }

    private function formatAdditional(array $address): array
    {
        $params = [];
        foreach (['street1', 'street2', 'city', 'state', 'zipcode', 'country'] as $attr) {
            if (!empty($address[$attr])) {
                $params[$attr] = $address[$attr];
            }
        }
        return $params;
    }

    public static function getLinesAddress(string $address)
    {
        return preg_split('~\R~', $address);
    }

    private static function getFrontendValue($full_name)
    {
        return is_array($full_name) ? $full_name[0] : $full_name;
    }
}