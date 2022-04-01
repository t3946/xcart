<?php

namespace Modules\Payment\Gateways;

use Modules\Order\Models\OrderTransactionModel;
use Modules\Order\Stores\OrderTransactionStore;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class PayPal extends AbstractGateway
{
    public static function getProcessorName(): string
    {
        return 'PayPal';
    }

    public static function isPartiallyCaptureEnabled(): bool
    {
        return true;
    }

    public function init(): void
    {
        parent::init();

        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getConfig();
        if (!isset($config['sandbox_client_id'], $config['sandbox_secret_key'], $config['live_client_id'], $config['live_secret_key'])) {
            $config = $site->getGlobalConfig();
        }

        $this->gateway->initialize(['testMode' => $this->test_mode]);

        switch ($this->test_mode) {
            case 'Y' :
                $this->gateway->setClientId($config['sandbox_client_id']);
                $this->gateway->setSecret($config['sandbox_secret_key']);
                break;
            default:
                $this->gateway->setClientId($config['live_client_id']);
                $this->gateway->setSecret($config['live_secret_key']);
                break;
        }

    }

    public function refund($params): bool
    {
        $this->result = $this->gateway
            ->refundCapture($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function void($params): bool
    {
        $this->result = $this->gateway
            ->void($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function capture($params): bool
    {
        $this->result = $this->gateway
            ->capture($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function lookup($params): bool
    {
        switch (strtolower($params['status'])) {
            case OrderTransactionModel::STATUS_AUTHORIZED :
            case OrderTransactionModel::STATUS_CAPTURED :
            case OrderTransactionModel::STATUS_PARTIALLY_CAPTURED :
            case OrderTransactionModel::STATUS_PENDING :
            case OrderTransactionModel::STATUS_VOIDED :
            case OrderTransactionModel::STATUS_EXPIRED :
                $params['statusLookup'] = 'authorization';
                break;
            case OrderTransactionModel::STATUS_COMPLETED :
            case OrderTransactionModel::STATUS_REFUNDED :
            case OrderTransactionModel::STATUS_PARTIALLY_RUFUNDED :
                $params['statusLookup'] = 'capture';
                break;
        }
        $this->result = $this->gateway
            ->lookup($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function authorize($params): bool
    {
        $this->result = $this->gateway
            ->authorize($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function getState($mode):? string
    {
        $state = null;

        if (!$this->result->isSuccessful()) {
            return OrderTransactionModel::STATUS_FAILED;
        }

        /*if (isset(self::$gatewayMethods[$mode])) {
            $state = self::$gatewayMethods[$mode]['status'];
        }*/
        $data = $this->result->getData();
        if (!$state && ($state = $data['state'])) {
            $statuses = array_map(static fn($a) => $a['status'], OrderTransactionStore::$gatewayMethods);
            if (!in_array($state, $statuses, true)) {
                switch ($data['intent']) {
                    case 'authorize':
                        return OrderTransactionModel::STATUS_AUTHORIZED;
                    case 'capture':
                        return OrderTransactionModel::STATUS_COMPLETED;
                }
            }
        }
        switch ($data['name']) {
            case 'AUTHORIZATION_EXPIRED' :
                return OrderTransactionModel::STATUS_EXPIRED;
            case 'AUTHORIZATION_ALREADY_COMPLETED' :
                return OrderTransactionModel::STATUS_COMPLETED;
        }
        return $state;
    }

    /**
     * @param $params
     * @return bool
     */
    public function reauthorize($params): bool
    {
        $this->result = $this->gateway
            ->reauthorize($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function purchase($params): bool
    {
        $this->result = $this->gateway
            ->purchase($params)
            ->send();

        return $this->result->isSuccessful();

    }

    public function complete($params): bool
    {
        $this->result = $this->gateway->completePurchase($params)->send();

        return $this->result->isSuccessful();
    }
}