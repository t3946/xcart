<?php
return [
//               'RedirectMiddleware' => [
//                   'class' => '\Modules\Redirect\Middleware\RedirectMiddleware'
//               ],
//               'AutoCacheMiddleware' => [
//                   'class' => '\\Modules\\Core\\Middleware\\CacheMiddleware',
//               ],
    'CORS' => [
        'class' => '\\Modules\\Core\\Middleware\\CorsMiddleware',
    ],
    'static_cache' => [
        'class' => '\\Modules\\Core\\Middleware\\CacheMiddleware',
        'cacheEnabled' => (defined('APP_DEBUG') && APP_DEBUG)? false : true,
    ],
    'CurrentSiteMiddleware' => [
        'class' => '\\Modules\\Sites\\Middleware\\CurrentSiteMiddleware',
    ],
    'CouponCodeMiddleware' => [
        'class' => '\Modules\Cart\Middleware\CouponCodeMiddleware'
    ],
//    'UserAdminMiddleware' => [
//        'class' => '\Modules\User\Middleware\UserAdminMiddleware'
//    ],
    'BotsMiddleware' => [
        'class' => '\\Modules\\User\\Middleware\\BotsMiddleware',
    ],
    'ReferrerSearch' => [
        'class' => '\\Modules\\User\\Middleware\\ReferrerSearchMiddleware'
    ],
    'ExpireHeaders' => [
        'class' => '\\Modules\\User\\Middleware\\ExpireHeadersMiddleware'
    ],
];