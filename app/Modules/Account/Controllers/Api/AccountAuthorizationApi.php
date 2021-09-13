<?php

namespace Modules\Account\Controllers\Api;

use Modules\User\Models\FingerprintModel;
use Modules\Account\Models\UserListModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Modules\Account\Forms\LoginForm;
use Modules\Account\Forms\RegistrationForm;
use Modules\Account\Models\ProductListsModel;


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
            $model = new ProductListsModel(['name' => 'Shipping list', 'user_id' =>  $user->user_id]);
            $model->save();
            UserListModel::objects()->create(['user_id' =>$user->user_id, 'product_list_id' => $model->product_list_id]);
            $user->authenticate();
            $this->jsonResponse($user->toArray());
        } else {
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
                $this->jsonResponse(["errors" => $form->getErrors()]);
                return;
            }

            if (!$user->checkPassword($attributes['password'])) {
                $form->addError('password', 'Password is incorrect');
                $this->jsonResponse(["errors" => $form->getErrors()]);
                return;
            }

            // tsv is enabled
            if ($user->getAttribute("tsv_count") > 0) {
                // unknown fingerprint
                if ($attributes['fingerprint'] && !$user->checkFingerprint($attributes['fingerprint'])) {
                    if (!$attributes['otp']) {
                        $this->jsonResponse([]);
                        return;
                    }

                    if (!$user->checkTSVCode($attributes['otp'])) {
                        $this->jsonResponse(["errors" => ['otp' => ['OTP is incorrect']]]);
                        return;
                    }

                    //remember fingerprint
                    if ($attributes['rememberBrowser']) {
                        (new FingerprintModel([
                            'user_id' => $user->getAttribute('user_id'),
                            'fingerprint' => $attributes['fingerprint'],
                        ]))->save();
                    }
                }
            }

            $user->authenticate($attributes['remember_me']);
            $this->jsonResponse(["user" => $user->toArray()]);
        } else {
            $this->jsonResponse(["errors" => $form->getErrors()]);
        }
    }

    /**
     * Check user exist by login
     */
    public function checkLogin()
    {
        $json = json_decode(file_get_contents('php://input'), true);
        $login = $json['LoginForm']['login'];
        $user = UserModel::objects()->filter(['email' => $login])->get();
        $this->jsonResponse($user ? [] : ['errors' => ['login' => ['User not found']]]);
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