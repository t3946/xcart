<?php

namespace Modules\Account\Controllers;

use Modules\Account\Forms\LoginForm;
use Modules\Account\Forms\RegistrationForm;
use Modules\Sites\Helpers\StorageHelper;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class AccountController extends FrontendController
{
    public function actionIndex()
    {
        $user = Xcart::app()->auth->getUser(true);

        if (!$user->getIsGuest()) {
            StorageHelper::push($user->toArray(), null, 'user');
        }

        $this->display('account/base.tpl');
    }

    public function register()
    {
        if (Xcart::app()->request->getIsPost()) {
            $form = (new RegistrationForm)->populate($_POST);

            if ($form->isValid()) {
                /**
                 * @var UserModel $user
                 */
                $user = $form->getInstance();
                $user->register();
                $user = UserModel::objects()->filter(['email' =>  $user->email])->get();
                $user->authenticate();
            }
            else {
                $this->jsonResponse($form->getErrors(), 400);
            }
        } else {
            $this->actionIndex();
        }
    }

    public function login()
    {
        if (Xcart::app()->request->getIsPost()) {
            $json = json_decode(file_get_contents('php://input'), true);
            $form = (new LoginForm)->populate($json);

            if ($form->isValid()) {
                $attributes = $form->getAttributes();
                /**
                 * @var UserModel $user
                 */
                $user = UserModel::objects()->filter(['email' => $attributes['login']])->get();

                if ($user) {
                    $user->login($attributes['password']);
                }
                else {
                    $form->addError('login', 'User with that email or phone not found');
                }

                if ($form->hasErrors()) {
                    $this->jsonResponse($form->getErrors(), 401);
                    return;
                }

                $this->jsonResponse($user->toArray());
            }
            else {
                $this->jsonResponse($form->getErrors());
            }
        } else {
            $this->actionIndex();
        }
    }

    public function logout()
    {
        Xcart::app()->auth->logout(false);
    }
}