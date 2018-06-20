<?php

use Xcart\App\Main\Xcart;

require "./auth.php";

Xcart::app()->request->redirect('/', [], 301);