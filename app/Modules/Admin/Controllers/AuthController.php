<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 19/05/16 07:48
 */

namespace Modules\Admin\Controllers;

use Modules\Admin\Forms\LoginForm;
use Modules\Admin\Forms\ResetPasswordForm;
use Modules\Sites\Helpers\StorageHelper;
use Xcart\App\Main\Xcart;

class AuthController extends BackendController
{
    public function login()
    {
        /** @var \Modules\User\Models\UserModel $user */
        $user = Xcart::app()->getUser();

        if (!$user->getIsGuest()) {
            $this->redirect('admin:index');
        }

        $form = new LoginForm();

        if ($this->getRequest()->getIsPost() && $form->populate($_POST)) {
            if ($form->isValid()) {
                $form->login();
                $this->redirect('admin:index');
            } else {
                StorageHelper::push($form->getErrors(), 'errors', 'loginForm');
            }
        }

        Xcart::app()->breadcrumbs->add('Login');

        echo $this->renderInSmarty('admin/auth/login.tpl', [
            'form' => $form,
        ]);
    }

    public function recoveryPassword()
    {
        $user = Xcart::app()->getUser();

        if (!$user->getIsGuest()) {
            $this->redirect('admin:index');
        }

        $form = new ResetPasswordForm();

        Xcart::app()->breadcrumbs->add('Recovery password');

        if ($this->getRequest()->getIsPost() && $form->populate($_POST)) {
            if ($form->isValid()) {
                $form->resetPassword();
                StorageHelper::push($form->getAttributes()['email'], 'sentTo', 'resetPasswordForm');
            } else {
                StorageHelper::push($form->getErrors(), 'errors', 'resetPasswordForm');
            }

            echo $this->renderInSmarty('admin/auth/recovery-password.tpl');
            return;
        }
        StorageHelper::push('foo', 'foo', 'resetPasswordForm');

        echo $this->renderInSmarty('admin/auth/recovery-password.tpl');
    }

    public function logout()
    {
        Xcart::app()->auth->logout(false);
        $this->redirect('admin:login');
    }
}