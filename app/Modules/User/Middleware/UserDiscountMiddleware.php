<?php


namespace Modules\User\Middleware;


use Modules\User\Helpers\DiscountHelper;
use Xcart\App\Middleware\Middleware;

class UserDiscountMiddleware extends Middleware
{


    public function processHttpRequest($request)
    {
        $now = new \DateTime('now');
        $min = DiscountHelper::DISCOUNT_PERIODS[random_int(0, count(DiscountHelper::DISCOUNT_PERIODS) - 1)];

        if ($request->session->has(DiscountHelper::CODE_PARAM) && $d_timestamp = $request->session->get(DiscountHelper::CODE_PARAM)) {
            $d_min = (int) $request->session->get(DiscountHelper::CODE_PARAM_MINUTES);
            $d = (new \DateTime())->setTimestamp($d_timestamp);
            $d->add(new \DateInterval("PT{$d_min}M"));
            if ($d->getTimestamp() >= $now->getTimestamp()) {
                return;
            }
            if (in_array($d_min, DiscountHelper::DISCOUNT_PERIODS, true)) {
                $min =  $d_min;
            }
        }
        $request->session->add(DiscountHelper::CODE_PARAM, $now->getTimestamp());
        $request->session->add(DiscountHelper::CODE_PARAM_MINUTES, $min);
    }
}