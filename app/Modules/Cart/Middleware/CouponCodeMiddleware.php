<?php
namespace Modules\Cart\Middleware;

use Modules\Cart\Models\CouponKitModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

/**
 * Class CouponCodeMiddleware
 *
 * Append coupon code from get parameter to session
 *
 * @package Modules\Sites\Middleware
 */
class CouponCodeMiddleware extends Middleware
{
    const CODE_PARAM = 'coupon_code';

    public function processRequest($request)
    {
        if (!Cli::isCli()) {

            if ( $code = $this->getCouponCode($request) ) {

                if ( $model = CouponKitModel::objects()->filter(['code' => $code, 'active' => true])->get()) {
                    $code = strtoupper($model->code);

                    if ($request->session->has(self::CODE_PARAM)) {
                        $cc_appended = $request->session->get(self::CODE_PARAM);
                        $cc_appended = strtoupper($cc_appended);

                        if ($cc_appended != $code) {
                            Xcart::app()->flash->info("Coupon code \"{$cc_appended}\" changed to \"{$code}\"");
                        }

                    }
                    else {
                        Xcart::app()->flash->success("Appended coupon code: \"{$code}\"");
                    }


                    $request->session->add(self::CODE_PARAM, $code);
                }
                else {

                    Xcart::app()->flash->error("Incorrect coupon code");
                }
            }

            if ($request->post->has('discard-coupon')) {
                $request->session->remove(self::CODE_PARAM);

                Xcart::app()->flash->success("Coupon has been discarded");
            }
        }
    }

    private function getCouponCode($request)
    {
        /** @var \Xcart\App\Request\HttpRequest $request */

        if ($request->post->has(self::CODE_PARAM)) {
            return $request->post->get(self::CODE_PARAM);
        }

        if ($request->get->has(self::CODE_PARAM)) {
            return $request->get->get(self::CODE_PARAM);
        }

        return null;
    }
}
