<?php

use Modules\Brand\Models\BrandModel;
use Xcart\App\Main\Xcart;

require_once '../app/include/vendors/autoload.php';

$config = include '../app/config/settings.php';

Xcart::init($config);

Xcart::app()->request->redirect('cart:list', [], 301);
