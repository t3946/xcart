<?php


namespace Modules\Order\Helpers;


use Modules\Core\Classes\MelissaAPI;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\FraudFAQuestionModel;
use Modules\Core\Models\StateModel;
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
        if ($this->compareShippingBillingAddress() == 1) {
            // Если shipping и billing адреса примерно равны, то отправил 1 запрос вместо 2х
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
                return;
            }
        }
        $this->ob_melissa->ip_data = null;
    }

    /* Address check */
    public function scoreBaseAddress(FraudFAQuestionModel $fraud)
    {
        $result = 'negative';
        $outcome = $this->compareShippingBillingAddress();
        $info = $this->getInfoAddress(
            $fraud,
            ['state' => $this->order_model->s_state, 'city' => $this->order_model->s_city, 'zipcode' => $this->order_model->s_zipcode],
            ['state' => $this->order_model->b_state, 'city' => $this->order_model->b_city, 'zipcode' => $this->order_model->b_zipcode],
        );
        if ($outcome) {
            $result = 'positive';
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    private function compareShippingBillingAddress(): float
    {
        $ar_s_address = preg_split('~\R~', $this->order_model->s_address);
        $ar_b_address = preg_split('~\R~', $this->order_model->b_address);
        return $this->compareAddress(
            [
                'country' => $this->order_model->s_country,
                'state' => $this->order_model->s_state,
                'city' => $this->order_model->s_city,
                'zipcode' => $this->order_model->s_zipcode,
                'street' => $ar_s_address[0] ?? $this->order_model->s_address,
                'street2' => $ar_s_address[1] ?? '',
            ],
            [
                'country' => $this->order_model->b_country,
                'state' => $this->order_model->b_state,
                'city' => $this->order_model->b_city,
                'zipcode' => $this->order_model->b_zipcode,
                'street' => $ar_b_address[0] ?? $this->order_model->b_address,
                'street2' => $ar_b_address[1] ?? '',
            ]);
    }

    public function scoreOwnerResidenceAddress(FraudFAQuestionModel $fraud, ?array $address_compare, string $type_address = 'shipping'): array
    {
        $result = 'negative';
        $owner_info = $this->ob_melissa->melissa_address[$type_address]['owner'] ?? null;
        $info = $this->getNullDataInfo($fraud, $address_compare);
        $outcome = 0;
        if (isset($owner_info, $address_compare)) {
            $zip = trim($owner_info['OwnerZip']);
            $zip = substr($zip, 0, 5);
            $owner_address_info = [
                'state' => $owner_info['OwnerState'],
                'city' => $owner_info['OwnerCity'],
                'zipcode' => $zip
            ];
            $info = $this->getInfoAddress(
                $fraud,
                $address_compare,
                $owner_address_info,
            );
            $outcome = $this->compareAddress($address_compare, $owner_address_info);
            if ($outcome) {
                $result = 'positive';
            }
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    public static function getStringAddressByArray(?array $address): ?string
    {
        if (isset($address['state'], $address['city'], $address['zipcode'])) {
            return "{$address['city']}, {$address['state']}  {$address['zipcode']}";
        }
        return null;
    }

    public function scorePhoneAddress(FraudFAQuestionModel $fraud, ?array $address_compare): array
    {
        $result = 'negative';
        $outcome = 0;
        $info = $this->getNullDataInfo($fraud, $address_compare);
        if (!is_null($this->ob_melissa->phone_data)) {
            $state = $this->ob_melissa->phone_data['State'] ?? '';
            /** @var CountryModel $country_model */
            $country_model = CountryModel::objects()->get(['name' => $this->ob_melissa->phone_data['CountryName']]);
            if (!is_null($country_model)) {
                /** @var StateModel $state */
                $state = StateModel::objects()->get(['state' => $this->ob_melissa->phone_data['State'], 'country_code' => $country_model->code]);
            }
            $phone_address = [
                'state' => $state->code,
                'city' => $this->ob_melissa->phone_data['City'],
                'zipcode' => $this->ob_melissa->phone_data['PostalCode']
            ];
            $info = $this->getInfoAddress($fraud, $address_compare, $phone_address);
            if (!is_null($address_compare)) {
                $outcome = $this->compareAddress($address_compare, $phone_address);
                if ($outcome) {
                    $result = 'positive';
                }
            }
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    public function scoreIpAddress(FraudFAQuestionModel $fraud, ?array $address_compare): array
    {
        $result = 'negative';
        $outcome = 0;
        $info = $this->getNullDataInfo($fraud);
        if (!is_null($this->ob_melissa->ip_data)) {
            /** @var CountryModel $country_model */
            $country_model = CountryModel::objects()->get(['name' => $this->ob_melissa->ip_data['Country']]);
            if (!is_null($country_model)) {
                $state = StateModel::objects()->get(['state' => $this->ob_melissa->ip_data['State'], 'country_code' => $country_model->code]);
            }
            $email_address = [
                'state' => $state->code ?? $this->ob_melissa->ip_data['State'],
                'city' => $this->ob_melissa->ip_data['City'],
                'zipcode' => $this->ob_melissa->ip_data['PostalCode']
            ];
            $info = $this->getInfoAddress($fraud, $address_compare, $email_address);
            if (!is_null($address_compare)) {
                $outcome = $this->compareAddress($address_compare, $email_address);
                if ($outcome) {
                    $result = 'positive';
                }
            }
        }

        return [$result, $fraud->weight, $info, $outcome];
    }

    /** Сравнивает два адреса по атрибутам, если они одинаковые то добавит значение к коэффиценту.
     *  Коэффицент = Количество одинаковых атрибутов в адресе / Общее количество атрибутов
     * @param array $compare_address - первый адрес в формате массива
     * @param array $addressData - второй адрес в формате массива
     * @return float
     */
    public function compareAddress(array $compare_address = [], array $addressData = []): float
    {
        if ($this->isEmptyAddress($compare_address) || $this->isEmptyAddress($addressData)) {
            return 0;
        }
        $compare_coef = 0;
        foreach ($addressData as $attr => $value) {
            if (strtoupper($value) === strtoupper($compare_address[$attr])) {
                $compare_coef++;
            } else {
                break;
            }
        }
        return round($compare_coef / count($addressData), 2);
    }

    public function isEmptyAddress(array $address): bool
    {
        $coef_empty = 0;
        foreach ($address as $attr) {
            if (empty($attr)) {
                $coef_empty++;
            }
        }
        return boolval($coef_empty / count($address) === 1);
    }

    /* Full name check */

    private function compareOwnerAddressByParams(string $name_address, string $full_name): bool
    {
        if (!is_null($this->ob_melissa->melissa_address[$name_address]['owner']['OwnerName1Full'])) {
            $result_compare = $this->compareClientName($full_name, $this->ob_melissa->melissa_address[$name_address]['owner']['OwnerName1Full']);
            if ($result_compare) {
                return true;
            }
        }
        return false;
    }

    private function compareTenantAddressByName(string $name_address, string $full_name)
    {
        if (!is_null($this->ob_melissa->melissa_address[$name_address]['address']['NameFull'])) {
            return $this->compareClientName($full_name, $this->ob_melissa->melissa_address[$name_address]['address']['NameFull']);
        }
        return false;
    }

    /** Парсит полное имя и сравнивает фамилию(последний элемент) в формате upperCase со вторым
     * @param string $f_full_name - Полное имя первого
     * @param string $t_full_name - Полное имя второго
     * @return bool
     */
    private function compareClientName(string $f_full_name, string $t_full_name)
    {
        $ar_f = explode(' ', $f_full_name);
        $ar_t = explode(' ', $t_full_name);
        if (strtoupper($ar_f[count($ar_f) - 1]) === strtoupper($ar_t[count($ar_t) - 1])) {
            return true;
        }
        return false;
    }

    public function comparePhoneCaller($full_name): bool
    {
        $phone_data = $this->ob_melissa->phone_data;
        if (!empty($phone_data)) {
            if (!empty(trim($phone_data['CallerID']))) {
                $phone_reverse = array_reverse(explode(' ', trim($this->ob_melissa->phone_data['CallerID'])));
                $phone = implode(' ', $phone_reverse);
                return $this->compareClientName($full_name, $phone);
            }
        }
        return false;
    }

    public function scorePhoneCaller(FraudFAQuestionModel $fraud, ?array $compare_info): array
    {
        $result = 'negative';
        $outcome = 0;
        $info = $this->getInfoFN($fraud, $compare_info, ['value' => $this->ob_melissa->phone_data['CallerID']]);
        if (!is_null($compare_info['value'])) {
            if ($this->comparePhoneCaller($compare_info['value'])) {
                $result = 'positive';
                $outcome = 1;
            }
        }
        return [$result, $fraud->weight, $info, $outcome];

    }

    public function scoreCardHolder(FraudFAQuestionModel $fraud, ?array $compare_name)
    {
        $result = 'negative';
        $outcome = 0;
        $info = $this->getInfoFN(
            $fraud,
            $compare_name,
            ['value' => $this->order_model->b_firstname, 'zipcode' => $this->order_model->b_zipcode]
        );
        if (!is_null($this->order_model->b_firstname) && !empty($compare_name['value'])) {
            $compare = $this->compareClientName($compare_name['value'], $this->order_model->b_firstname);
            if ($compare) {
                $result = 'positive';
                $outcome = 1;
            }
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    public function scoreBaseName(FraudFAQuestionModel $fraud, ?array $names = []): array
    {
        $result = 'negative';
        $outcome = 0;
        $info = $this->getInfoFN($fraud, $names[0], $names[1]);
        // Если какое-либо из имён имеет значение null, то его нельзя сравнить
        foreach ($names as $name) {
            if (is_null($name['value'])) {
                return [$result, $fraud->weight, $info, 0];
            }
        }
        $compare = $this->compareClientName($names[0]['value'], $names[1]['value']);
        if ($compare) {
            $outcome = 1;
            $result = 'positive';
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    public function scoreTenantAddress(FraudFAQuestionModel $fraud, ?array $compare_name, string $type_address = 'shipping'): array
    {
        $result = 'negative';
        $outcome = 0;
        $str_address = $this->ob_melissa->melissa_address[$type_address]['address']['NameFull'];
        $info = $this->getInfoFN(
            $fraud,
            $compare_name,
            ['value' => $str_address, 'zipcode' => $this->ob_melissa->melissa_address[$type_address]['address']['PostOfficeZip']]
        );
        if (!is_null($compare_name['value'])) {
            if ($this->compareTenantAddressByName($type_address, $compare_name['value'])) {
                $outcome = 1;
                $result = 'positive';
            }
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    public function scoreOwnerAddress(FraudFAQuestionModel $fraud, ?array $compare_name, string $type_address = 'shipping'): array
    {
        $result = 'negative';
        $outcome = 0;
        $str_address = $this->ob_melissa->melissa_address[$type_address]['owner']['OwnerName1Full'];
        $zip_owner = $this->ob_melissa->melissa_address[$type_address]['owner']['OwnerZip'];
        $zip = substr($zip_owner, 0, 5);
        $info = $this->getInfoFN($fraud, $compare_name, ['value' => $str_address, 'zipcode' => $zip]);
        if (!is_null($compare_name['value'])) {
            if ($this->compareOwnerAddressByParams($type_address, $compare_name['value'])) {
                $outcome = 1;
                $result = 'positive';
            }
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    public function scoreEmailAddress(FraudFAQuestionModel $fraud, ?array $compare_name): array
    {
        $result = 'negative';
        $outcome = 0;
        $info = $this->getInfoFN($fraud, $compare_name, ['value' => $this->ob_melissa->email_data['NameFull']]);
        if (!is_null($compare_name['value'])) {
            if ($this->compareClientName($compare_name['value'], $this->ob_melissa->email_data['NameFull'] ?? '')) {
                $outcome = 1;
                $result = 'positive';
            }
        }
        return [$result, $fraud->weight, $info, $outcome];
    }

    private function getInfoFN(FraudFAQuestionModel $question_model, array $f_value, array $t_value): array
    {
        return [
            "value{$question_model->f_fraud->fraud_code}" => !empty(trim($f_value['value']))
                ? [
                    'full_name' => $f_value['value'],
                    'zip' => !empty($f_value['zipcode']) ? $f_value['value'] : ''
                ]
                : self::ADDITIONAL_INFO_NULL_CHECK,
            "value{$question_model->t_fraud->fraud_code}" => !empty(trim($t_value['value']))
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
            "value{$question_model->f_fraud->fraud_code}" => self::getStringAddressByArray($f_value)
                ? $f_value
                : self::ADDITIONAL_INFO_NULL_CHECK,
            "value{$question_model->t_fraud->fraud_code}" => self::getStringAddressByArray($t_value)
                ? $t_value
                : self::ADDITIONAL_INFO_NULL_CHECK
        ];
    }

    /** Вывод массива пустых значений в поле additional info
     * @param FraudFAQuestionModel $question_model - модель вопроса, чтобы узнать что с чем сравнивается.
     * @param array|null $value - значение для f_fraud question
     * @return array
     */
    private function getNullDataInfo(FraudFAQuestionModel $question_model, array $value = null): array
    {
        return [
            "value{$question_model->f_fraud->fraud_code}" => $value ?? self::ADDITIONAL_INFO_NULL_CHECK,
            "value{$question_model->t_fraud->fraud_code}" => self::ADDITIONAL_INFO_NULL_CHECK
        ];
    }
}