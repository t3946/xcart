<?php

use Modules\Core\Middleware\CorsMiddleware;
use Modules\Order\Middleware\OrderCheckoutMiddleware;
use Modules\Order\Middleware\PurchaseOrderMiddleware;
use Modules\User\Middleware\ExpireHeadersMiddleware;
use Modules\User\Middleware\ReferrerSearchMiddleware;
use Modules\User\Middleware\BotsMiddleware;
use Modules\Cart\Middleware\CouponCodeMiddleware;
use Modules\Sites\Middleware\CurrentSiteMiddleware;
use Modules\Core\Middleware\CacheMiddleware;
use Modules\User\Middleware\UserDiscountMiddleware;

return [
    'CORS' => [
        'class' => CorsMiddleware::class,
    ],
    'CurrentSiteMiddleware' => [
        'class' => CurrentSiteMiddleware::class,
    ],
    'CouponCodeMiddleware' => [
        'class' => CouponCodeMiddleware::class
    ],
    'BotsMiddleware' => [
        'class' => BotsMiddleware::class,
    ],
    'ReferrerSearch' => [
        'class' => ReferrerSearchMiddleware::class
    ],
    'ExpireHeaders' => [
        'class' => ExpireHeadersMiddleware::class
    ],
    'DiscountMiddleware' => [
        'class' => UserDiscountMiddleware::class
    ],
    'PurchaseOrderMiddleware' => [
        'class' => PurchaseOrderMiddleware::class
    ],
    'OrderCheckoutMiddleware' => [
        'class' => OrderCheckoutMiddleware::class
    ],
];