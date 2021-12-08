<?php

namespace Modules\Core\Models;

use Modules\Order\Helpers\FraudCheckFAHelper;
use Modules\Order\Helpers\BaseFraudCheckHelperV2;
use Modules\Order\Models\OrderFraudCheckModel;
use Modules\Order\Models\OrderFraudFACheckModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property int|string question_id
 * @property FraudCheckColumnModel f_fraud
 * @property FraudCheckColumnModel t_fraud
 * @property mixed weight
 * @property string template
 */
class FraudFAQuestionModel extends Model
{
    use AutoMetaTrait;

    private $result;

    public static function tableName()
    {
        return 'xcart_fraud_fa_question';
    }

    public function __toString()
    {
        return "{$this->f_fraud->name}-{$this->t_fraud->name}";
    }

    public static function getFields()
    {
        return [
            'question_id' => [
                'class' => AutoField::class,
                'primary' => true,
            ],
            'f_fraud' => [
                'field' => 'f_fraud_id',
                'class' => ForeignField::class,
                'modelClass' => FraudCheckColumnModel::class,
                'link' => ['f_fraud_id' => 'fraud_id'],
                'null' => false,
            ],
            't_fraud' => [
                'field' => 't_fraud_id',
                'class' => ForeignField::class,
                'modelClass' => FraudCheckColumnModel::class,
                'link' => ['t_fraud_id' => 'fraud_id'],
                'null' => false,
            ],
            'weight' => [
                'class' => IntField::class,
                'default' => '0',
                'null' => false,
            ],
            'type' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'template' => [
                'class' => CharField::class,
            ],
            'order_by' => [
                'class' => IntField::class,
            ],
        ];
    }

    public function getScore(OrderModel $order, FraudCheckFAHelper $helper): ?array
    {
        if ($result = $this->getMethodResult($order, $helper)) {
            [$fraud_result, $weight, $info, $outcome] = $result;
            return [$fraud_result, round($weight * $outcome, 2), $info, $outcome];
        }
        return null;
    }

    public function getMethodResult(OrderModel $order, FraudCheckFAHelper $helper): array
    {
        $result = [];
        $compare_value = $this->getNameValueByMatrixCode($this->f_fraud->code, $order, $helper);
        switch ($this->t_fraud->code) {
            case 'FN_BA':
                $result = $helper->scoreBaseName($this, [$compare_value, ['value' => $order->b_firstname, 'zipcode' => $order->b_zipcode]]);
                break;
            case 'FN_SA':
                $result = $helper->scoreBaseName($this, [
                    ['value' => $order->firstname],
                    ['value' => $order->s_firstname, 'zipcode' => $order->s_zipcode]
                ]);
                break;
            case 'FN_CH':
                $result = $helper->scoreCardHolder($this, $compare_value);
                break;
            case 'FN_T_BA':
                $result = $helper->scoreTenantAddress($this, $compare_value, 'billing');
                break;
            case 'FN_O_BA':
                $result = $helper->scoreOwnerAddress($this, $compare_value, 'billing');
                break;
            case 'FN_O_SA':
                $result = $helper->scoreOwnerAddress($this, $compare_value);
                break;
            case 'FN_T_SA':
                $result = $helper->scoreTenantAddress($this, $compare_value);
                break;
            case 'FN_TN':
                $result = $helper->scorePhoneCaller($this, $compare_value);
                break;
            case 'FN_EA':
                $result = $helper->scoreEmailAddress($this, $compare_value);
                break;
            case 'BA':
                $result = $helper->scoreBaseAddress($this);
                break;
            case 'CSZ_IP':
                $result = $helper->scoreIpAddress($this, $compare_value);
                break;
            case 'CSZ_TN':
                $result = $helper->scorePhoneAddress($this, $compare_value);
                break;
            case 'ORA_BA':
                $result = $helper->scoreOwnerResidenceAddress($this, $compare_value, 'billing');
                break;
            case 'ORA_SA':
                $result = $helper->scoreOwnerResidenceAddress($this, $compare_value);
                break;
            default:
                $result = ['negative', $this->weight];
                break;
        }
        return $result;
    }

    private function getNameValueByMatrixCode(string $fraud_code, OrderModel $order, FraudCheckFAHelper $helper): ?array
    {
        switch ($fraud_code) {
            case 'FN_CI':
                return ['value' => $order->firstname];
            case 'FN_SA':
                return [
                    'value' => $order->s_firstname ?? null,
                    'zipcode' => $order->s_zipcode ?? null
                ];
            case 'FN_CH':
            case 'FN_BA':
                return [
                    'value' => $order->b_firstname ?? null,
                    'zipcode' => $order->b_zipcode ?? null
                ];
            case 'FN_T_SA':
                return [
                    'value' => $helper->ob_melissa->melissa_address['shipping']['address']['NameFull'] ?? null,
                    'zipcode' => $helper->ob_melissa->melissa_address['shipping']['address']['PostOfficeZip'] ?? null
                ];
            case 'FN_O_SA':
                $zip = trim($helper->ob_melissa->melissa_address['shipping']['owner']['OwnerZip']);
                $zip = substr($zip, 0, 5);
                return [
                    'value' => $helper->ob_melissa->melissa_address['shipping']['owner']['OwnerName1Full'] ?? null,
                    'zipcode' => $zip ?? null
                ];
            case 'FN_T_BA':
                return [
                    'value' => $helper->ob_melissa->melissa_address['billing']['address']['NameFull'] ?? null,
                    'zipcode' => $helper->ob_melissa->melissa_address['billing']['address']['PostOfficeZip'] ?? null
                ];
            case 'FN_O_BA':
                $zip = trim($helper->ob_melissa->melissa_address['billing']['owner']['OwnerZip']);
                $zip = substr($zip, 0, 5);
                return [
                    'value' => $helper->ob_melissa->melissa_address['billing']['owner']['OwnerName1Full'] ?? null,
                    'zipcode' => $zip ?? null
                ];
            case 'FN_TN':
                return ['value' => $helper->ob_melissa->phone_data['CallerID'] ?? null];
            case 'SA':
                return [
                    'state' => $order->s_state,
                    'city' => $order->s_city,
                    'zipcode' => $order->s_zipcode,
                ];
            case 'BA':
                return [
                    'state' => $order->b_state,
                    'city' => $order->b_city,
                    'zipcode' => $order->b_zipcode,
                ];
            case 'ORA_SA':
            case 'ORA_BA':
                $type_address = ($fraud_code === 'ORA_BA') ? 'billing' : 'shipping';
                if (!is_null($helper->ob_melissa->melissa_address[$type_address]['owner'])) {
                    $zip = trim($helper->ob_melissa->melissa_address[$type_address]['owner']['OwnerZip']);
                    // Fix от zip кода длинной более 5 символов
                    $zip = substr($zip, 0, 5);
                    return [
                        'state' => $helper->ob_melissa->melissa_address[$type_address]['owner']['OwnerState'],
                        'city' => $helper->ob_melissa->melissa_address[$type_address]['owner']['OwnerCity'],
                        'zipcode' => $zip,
                    ];
                }
            case 'CSZ_TN':
                if (!is_null($helper->ob_melissa->phone_data)) {
                    /** @var CountryModel $country_model */
                    $country_model = CountryModel::objects()->get(['name' => $helper->ob_melissa->phone_data['CountryName']]);
                    if (!is_null($country_model)) {
                        /** @var StateModel $state_model */
                        $state_model = StateModel::objects()->get(['state' => $helper->ob_melissa->phone_data['State'], 'country_code' => 'US']);
                        if (!is_null($state_model)) {
                            return [
                                'state' => $state_model->code,
                                'city' => $helper->ob_melissa->phone_data['City'],
                                'zipcode' => $helper->ob_melissa->phone_data['PostalCode'],
                            ];
                        }
                    }
                }
            case 'CSZ_IP':
                if (!is_null($helper->ob_melissa->ip_data)) {
                    /** @var CountryModel $country_model */
                    $country_model = CountryModel::objects()->get(['name' => $helper->ob_melissa->ip_data['Country']]);
                    if (!is_null($country_model)) {
                        /** @var StateModel $state_model */
                        $state_model = StateModel::objects()->get(['state' => $helper->ob_melissa->ip_data['State'], 'country_code' => $country_model->code]);
                        if (!is_null($state_model)) {
                            return [
                                'state' => $state_model->code,
                                'city' => $helper->ob_melissa->ip_data['City'],
                                'zipcode' => $helper->ob_melissa->ip_data['PostalCode'],
                            ];
                        }
                    }
                }
        }
        return null;
    }
}