<?php

use Xcart\App\Main\Xcart;

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

$user = Xcart::app()->auth->getUser();

if (!$user || $user->getIsGuest()) {
    Xcart::app()->request->redirect('admin:login');
}

/** @var \Modules\User\Models\RoleModel $role */
if (($uModel = Xcart::app()->user) && $role = $uModel->role) {
    if (!$role->canRequest(Xcart::app()->request)) {
        Xcart::app()->request->redirect('/admin/error_message.php?access_denied&id=25');
    }
}
