<?php

use Modules\Account\Controllers\Api\ProfileApi;

return [
    [
        'route' => '/save-pubic-profile',
        'target' => [ProfileApi::class, 'savePublicProfile'],
        'name' => 'save_public_profile'
    ],
];
