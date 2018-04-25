<?php

 return [
     [
         'route' => '/product/{i:id}/',
         'target' => ['\Modules\Landing\Controllers\LandingController', 'index'],
         'name' => 'product'
     ],
 ];