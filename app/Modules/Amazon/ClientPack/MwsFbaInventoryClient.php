<?php

namespace Modules\Amazon\ClientPack;

use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottleAwareClientPackInterface;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestManager;
use FBAInventoryServiceMWS_Client;

class MwsFbaInventoryClient extends FBAInventoryServiceMWS_Client implements ThrottleAwareClientPackInterface
{
    const PARAM_MERCHANT                    = 'SellerId';
    const PARAM_MARKETPLACE_ID              = 'MarketplaceId';
    const PARAM_SELLER_SKU_IDS              = 'SellerSkus';

    const METHOD_GET_INVENTORY_SUPPLY       = 'listInventorySupply';

    private $throttleManager;
    private $marketplaceId;
    private $sellerId;

    public function __construct(MwsClientPoolConfig $poolConfig) {
        $this->marketplaceId    = $poolConfig->getMarketplaceId();
        $this->sellerId         = $poolConfig->getSellerId();

        $this->initThrottleManager();

        parent::__construct(
            $poolConfig->getAccessKey(),
            $poolConfig->getSecretKey(),
            $poolConfig->getConfigForOrder($this->getServiceUrlSuffix()),
            $poolConfig->getApplicationName(),
            $poolConfig->getApplicationVersion()
        );
    }

    public function callGetListInventory($SKUs) {

        $requestArray = [
            self::PARAM_MERCHANT            => $this->sellerId,
            self::PARAM_MARKETPLACE_ID      => $this->marketplaceId,
            self::PARAM_SELLER_SKU_IDS      => array('member' => $SKUs),
        ];

        return CaponicaClientPack::throttledCall($this, self::METHOD_GET_INVENTORY_SUPPLY, $requestArray);
    }

    private function getServiceUrlSuffix() {
        return '/FulfillmentInventory/' . self::SERVICE_VERSION;
    }

    public function initThrottleManager()
    {
        $this->throttleManager = new ThrottledRequestManager(
            [
                self::METHOD_GET_INVENTORY_SUPPLY => [30, 2],
            ]
        );
    }

    public function getThrottleManager()
    {
        return $this->throttleManager;
    }
}