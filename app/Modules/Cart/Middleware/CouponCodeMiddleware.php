<?php
namespace Modules\Cart\Middleware;

use Modules\Cart\Helpers\CouponOldCart;
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

    public $isProcessRequest = true;

    public function processHttpRequest($request)
    {
        if (!Cli::isCli()) {

            $flash = Xcart::app()->flash;

            if ( $code = $this->getCouponCode($request) ) {

                if ( $model = CouponKitModel::objects()->filter(['code' => $code, 'active' => true])->get()) {
                    $code = strtoupper($model->code);

                    if ($request->session->has(self::CODE_PARAM)) {
                        $cc_appended = $request->session->get(self::CODE_PARAM);
                        $cc_appended = strtoupper($cc_appended);

                        if ($cc_appended != $code) {
                            $flash->addWithCode(self::CODE_PARAM, "Coupon code \"{$cc_appended}\" changed to \"{$code}\"", $flash::TYPE_INFO);
                        }

                    }
                    else {
                        $flash->addWithCode(self::CODE_PARAM, "Appended coupon code: \"{$code}\"", $flash::TYPE_SUCCESS);
                    }

                    $request->session->add(self::CODE_PARAM, $code);
                }
                else {

                    $flash->error("Incorrect coupon code");
                }

                CouponOldCart::getInstance()->validateCoupon();

                $request->getIsGet()?: $request->refresh();
            }
            elseif ($request->post->has('discard-coupon')) {
                $request->session->remove(self::CODE_PARAM);
                $flash->success("Coupon has been discarded");
                $request->refresh();
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
