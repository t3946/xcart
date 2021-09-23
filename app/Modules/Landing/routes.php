<?php

use Modules\Landing\Controllers\LandingController;

return [
     [
         'route' => '/wick-candle-maker/',
         'target' => [LandingController::class, 'index'],
         'name' => 'product'
     ],
     [
         'route' => '/wick-candle-maker/buy/',
         'target' => [LandingController::class, 'order'],
         'name' => 'order'
     ],
 ];