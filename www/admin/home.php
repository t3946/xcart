<?php

use Xcart\App\Main\Xcart;

require_once '../../app/include/vendors/autoload.php';

$config = include '../../app/config/settings.php';

Xcart::init($config);

Xcart::app()->request->redirect('admin:index', [], 301);	
