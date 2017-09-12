<?php

namespace Modules\Amazon\ClientPack;

use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottleAwareClientPackInterface;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestLogCollection;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestManager;

class MwsFeedAndReportClient extends MwsFeedAndReportClientPack implements ThrottleAwareClientPackInterface
{
    const METHOD_SUBMIT_FEED   = 'submitFeed';
    private $throttleManager;

    public function __construct(MwsClientPoolConfig $poolConfig) {
        parent::__construct($poolConfig);

        $this->initThrottleManager();
    }

    private function getServiceUrlSuffix() {
        return '/';
    }

    public function callSubmitFeed($feedType, $feedContent) {
        $contentHash = base64_encode(md5(stream_get_contents($feedContent), true));
        rewind($feedContent);

        $parameters = [
            self::PARAM_FEED_CONTENT        => $feedContent,
            self::PARAM_FEED_CONTENT_MD5    => $contentHash,
            self::PARAM_FEED_TYPE           => $feedType,
            self::PARAM_MARKETPLACE_ID_LIST => array('Id' => $this->marketplaceId),
            self::PARAM_MERCHANT            => $this->sellerId,
        ];
        return self::throttledCall($this, self::METHOD_SUBMIT_FEED, $parameters, 1);
    }

    public function initThrottleManager() {
        $this->throttleManager = new ThrottledRequestManager(
            [
                self::METHOD_SUBMIT_FEED   => [15, 0.008, ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
            ]
        );
    }

    public function getThrottleManager() {
        return $this->throttleManager;
    }

    public static function throttledCall(ThrottleAwareClientPackInterface $clientPack, $method, $options, $weight=null) {
        try {
            self::snooze($clientPack->getThrottleManager()->snoozeRequiredBeforeNewRequest($method, $weight));
            $clientPack->getThrottleManager()->addRequestLogForMethod($method, $weight);
            return $clientPack->$method($options);
        } catch (\Exception $e) {
            if (method_exists($e, 'getErrorCode') && ('RequestThrottled' == $e->getErrorCode() || 'QuotaExceeded' == $e->getErrorCode())) {
                echo "\nThe request was throttled ".$e->getErrorCode();
                $snoozeLength = $clientPack->getThrottleManager()->getRestoreInterval($method, $weight);
                $clientPack->getThrottleManager()->exhaustRequestQuotaForMethod($method);
                self::snooze(ceil($snoozeLength) * 2); // Double the normal snooze since we bounced off the server limit
                    // try again. If there's another exception it will bubble up to the caller.
                return $clientPack->$method($options);

            }
            throw $e;
        }
    }

    /**
     * @param int|float $snoozeLength
     */
    private static function snooze($snoozeLength) {
        if ($snoozeLength > 0) {
            echo "\nSnoozing for $snoozeLength seconds";
            if (is_int($snoozeLength)) {
                sleep($snoozeLength);
            } else {
                usleep($snoozeLength * 1000000);
            }
        }
    }
}