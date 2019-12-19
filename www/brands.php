<?php

use Modules\Brand\Models\BrandModel;
use Xcart\App\Main\Xcart;

require_once '../app/include/vendors/autoload.php';

$config = include '../app/config/settings.php';

Xcart::init($config);

if ($brand_model = BrandModel::objects()->get(['brandid' => (int) $brandid])) {
    Xcart::app()->request->redirect($brand_model->getAbsoluteUrl(true), [], 301);
} else {
    Xcart::app()->request->redirect('brand:list', [], 301);
}
