<?php

use Modules\Landing\Controllers\LandingController;

return [
     [
         'route' => '/wick-candle-maker/',
         'target' => [LandingController::class, 'index'],
         'name' => 'product'
     ],
 ];