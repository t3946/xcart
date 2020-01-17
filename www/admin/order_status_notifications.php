<?php

use Xcart\App\Main\Xcart;

define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array("update", "email_body");

require './auth.php';
require $xcart_dir . '/include/security.php';

$location[] = array(func_get_langvar_by_name('lbl_order_status_notifications'), 'order_status_notifications.php');

include $xcart_dir . '/include/order_status_notifications.php';

Xcart::app()->request->redirect('/admin/configuration.php?option=Order_Status_Notifications');

