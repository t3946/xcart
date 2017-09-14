<?php

namespace Modules\Amazon\ClientPack;


use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottleAwareClientPackInterface;

class AmazonClientPack extends CaponicaClientPack
{
    public static function throttledCall(ThrottleAwareClientPackInterface $clientPack, $method, $options, $weight = null)
    {
        try {
            self::snooze($clientPack->getThrottleManager()->snoozeRequiredBeforeNewRequest($method, $weight));
            $clientPack->getThrottleManager()->addRequestLogForMethod($method, $weight);
            return $clientPack->$method($options);
        } catch (\Exception $e) {
            if (method_exists($e, 'getErrorCode') && ('RequestThrottled' == $e->getErrorCode() || 'QuotaExceeded' == $e->getErrorCode())) {
                echo "\nThe request was throttled";
                func_backprocess_log('incremental feeds', "{$options[MwsFeedAndReportClient::PARAM_FEED_TYPE]} - {$e->getErrorCode()}: {$e->getMessage()}");
                $snoozeLength = $clientPack->getThrottleManager()->getRestoreInterval($method, $weight);
                $clientPack->getThrottleManager()->exhaustRequestQuotaForMethod($method);
                self::snooze(ceil($snoozeLength) * 2); // Double the normal snooze since we bounced off the server limit

                // try again. If there's another exception it will bubble up to the caller.
                return self::throttledCall($clientPack, $method, $options, $weight);
            }
            throw $e;
        }
    }

    /**
     * @param int|float $snoozeLength
     */
    private static function snooze($snoozeLength)
    {
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