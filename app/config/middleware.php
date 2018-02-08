<?php
return [
//               'RedirectMiddleware' => [
//                   'class' => '\Modules\Redirect\Middleware\RedirectMiddleware'
//               ],
//               'AutoCacheMiddleware' => [
//                   'class' => '\\Modules\\Core\\Middleware\\CacheMiddleware',
//               ],
    'CurrentSiteMiddleware' => [
        'class' => '\Modules\Sites\Middleware\CurrentSiteMiddleware',
    ],
    'CouponCodeMiddleware' => [
        'class' => '\Modules\Cart\Middleware\CouponCodeMiddleware'
    ],
    'UserAdminMiddleware' => [
        'class' => '\Modules\User\Middleware\UserAdminMiddleware'
    ],
    'BotsMiddleware' => [
        'class' => '\Modules\User\Middleware\BotsMiddleware',
    ],
    'ReferrerSearch' => [
        'class' => '\Modules\User\Middleware\ReferrerSearchMiddleware'
    ],
    'ExpireHeaders' => [
        'class' => '\Modules\User\Middleware\ExpireHeadersMiddleware'
    ],
];