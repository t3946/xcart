<?php

if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

$app = Xcart\App\Main\Xcart::app();
$user = $app->auth->getUser();

if (!$user || $user->getIsGuest()) {
    $app->request->redirect('admin:login');
}

if (($uModel = $app->user) && $role = $uModel->role) {
    if (!$role->canRequest($app->request)) {
        $app->request->redirect('/admin/error_message.php?access_denied&id=25');
    }
}
