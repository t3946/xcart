<?php

use Xcart\App\Main\Xcart;

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

$user = Xcart::app()->auth->getUser();

if (!$user || $user->getIsGuest()) {
    Xcart::app()->request->redirect('admin:login');
}

/** @var \Modules\User\Models\RoleModel $role */
if (($uModel = Xcart\App\Main\Xcart::app()->user) && $role = $uModel->role) {
    if (!$role->canRequest(\Xcart\App\Main\Xcart::app()->request)) {
        \Xcart\App\Main\Xcart::app()->request->redirect('/admin/error_message.php?access_denied&id=25');
    }
}
