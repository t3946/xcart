<?php

use Xcart\App\Main\Xcart;

require_once '../app/include/vendors/autoload.php';

$config = include '../app/config/settings.php';

Xcart::init($config);

if (Xcart::app()->request->get->has('productid')
    && $model = \Modules\Goods\Models\ProductModel::objects()->get(['productid' => (int) Xcart::app()->request->get->get('productid')]))
{
    Xcart::app()->request->redirect($model->getAbsoluteUrl(true), [], 301);
} else {
    Xcart::app()->request->redirect('/', [], 301);
}
