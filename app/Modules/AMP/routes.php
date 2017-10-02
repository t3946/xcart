<?php

return [
    [
        'route' => '/product/{i:id}',
        'target' => ['\Modules\Amp\Controllers\AmpController', 'amp'],
        'name' => 'amp'
    ]
];