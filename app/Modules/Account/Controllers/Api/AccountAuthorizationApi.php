<?php
namespace Modules\Account\Controllers\Api;

use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Modules\Account\Forms\LoginForm;
use Modules\Account\Forms\RegistrationForm;

class AccountAuthorizationApi extends FrontendController
{
    public function register()
    {
        $json = json_decode(file_get_contents('php://input'), true);
        $form = (new RegistrationForm)->populate($json);

        $email = $form->getAttributes()["email"];
        $user = UserModel::objects()->filter(["email" => $email])->get();

        if ($user) {
            $this->jsonResponse(
                [
                    "errors" => ["email" => "This Email already registered"],
                ]
            );
            return;
        }

        if ($form->isValid()) {
            /**
             * @var UserModel $user
             */
            $user = $form->getInstance();
            $user->register();
            $user = UserModel::objects()->filter(['email' => $user->email])->get();
            $user->authenticate();
            $this->jsonResponse($user->toArray());
        }
        else {
            $this->jsonResponse(["errors" => $form->getErrors()]);
        }
    }

    public function login()
    {
        $json = json_decode(file_get_contents('php://input'), true);
        $form = (new LoginForm)->populate($json);

        if ($form->isValid()) {
            $attributes = $form->getAttributes();
            /**
             * @var UserModel $user
             */
            $user = UserModel::objects()->filter(['email' => $attributes['login']])->get();

            if (!$user) {
                $form->addError('login', 'User with that email or phone not found');
            }

            if (!$user->login($attributes['password'], $attributes['remember_me'])) {
                $form->addError('password', 'Password is incorrect');
            }

            if ($form->hasErrors()) {
                $this->jsonResponse(["errors" => $form->getErrors()]);
                return;
            }

            $this->jsonResponse($user->toArray());
        }
        else {
            $this->jsonResponse(["errors" => $form->getErrors()]);
        }
    }

    public function checkLogin()
    {
        $json = json_decode(file_get_contents('php://input'), true);
        $form = (new LoginForm)->populate($json);
        $form->isValid();

        if (!isset($form->getErrors()['login'])) {
            $email = $form->getAttributes()["login"];
            $user = UserModel::objects()->filter(["email" => $email])->get();

            if (!$user) {
                $form->addError('login', "User not found");
                $this->jsonResponse(["errors" => $form->getErrors()]);
                return;
            }

            $this->jsonResponse($user->toArray());
        }
        else {
            $this->jsonResponse(["errors" => $form->getErrors()]);
        }
    }

    public function logout()
    {
        Xcart::app()->auth->logout(false);

        $this->jsonResponse([]);
    }

    public function info()
    {
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            $this->jsonResponse("guest");
        } else {
            $this->jsonResponse($user->toArray());
        }
    }
}