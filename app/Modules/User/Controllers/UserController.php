<?php


namespace Modules\User\Controllers;

use Modules\User\Forms\RegistrationForm;
use Modules\User\Forms\LoginForm;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class UserController extends FrontendController
{
    public function register()
    {
        if (Xcart::app()->request->getIsPost()) {
            $form = (new RegistrationForm)->populate($_POST);

            if ($form->isValid()) {
                /**
                 * @var UserModel $user
                 */
                $user = $form->getInstance();
                $this->jsonResponse($user->getAttributes());

                $user->register();
            }
            else {
                $this->jsonResponse($form->getErrors());
            }
        }
    }

    public function login()
    {
        if (Xcart::app()->request->getIsPost()) {
            $form = (new LoginForm)->populate($_POST);

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
                    $this->jsonResponse($form->getErrors());
                    return;
                }

                $this->jsonResponse($user->getAttributes());

//                $user->register();
            }
            else {
                $this->jsonResponse($form->getErrors());
            }
        }
    }

    public function info()
    {
        $user = Xcart::app()->getUser(true);

        if ($user->getIsGuest()) {
            dd("НЕ Авторизован");
        }

        dd($user);
    }

    public function logout()
    {
        Xcart::app()->auth->logout(false);
        //TODO: 'account:account-login'
        $this->redirect('user:account-login');
    }
}