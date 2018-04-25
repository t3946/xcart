<?php

 return [
     [
         'route' => '/promo/product/{i:id}/',
         'target' => ['\Modules\Landing\Controllers\LandingController', 'index'],
         'name' => 'product'
     ],
 ];