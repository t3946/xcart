<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\OneTimePasswordModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Firebase\JWT\JWT;

class ResetPasswordApi extends FrontendController
{
    public function sendEmail(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = UserModel::objects()->filter(['email' => $data['login']])->get();

        if (!$user) {
            $this->jsonResponse(['errors' => ['login' => ['User not found']]]);
            return;
        }

        $user_id = $user->getAttribute('user_id');

        /**
         * @var OneTimePasswordModel $otp
         */
        [$otp, $is_new] = OneTimePasswordModel::objects()->getOrCreate(['user_id' => $user_id]);

        if ($is_new && APP_LOCAL === false) {
            Xcart::app()->mail->raw(
                $user->email,
                'Password Assistance OTP',
                'Your reset password OTP is: ' . $otp->one_time_password,
                [
                    'from' => 'helpdesk@s3stores.com',
                ]
            );
        } elseif (!$otp->isNew()) {
            $otp->delete();
            [$otp] = OneTimePasswordModel::objects()->getOrCreate(['user_id' => $user_id]);
        }

        $this->jsonResponse(['one_time_password' => $otp->toArray()]);
    }

    public function verifyOneTimePassword(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = UserModel::objects()->get(['email' => $data['login']]);
        $user_id = $user->getAttribute('user_id');

        /**
         * @var OneTimePasswordModel $one_time_password
         */
        $one_time_password = OneTimePasswordModel::objects()->get(['user_id' => $user_id]);

        if ($one_time_password->isOutdated()) {
            $this->jsonResponse(['errors' => ['otp' => 'outdated']]);
            $one_time_password->delete();
            return;
        }

        if ($one_time_password->matchCode($data['one_time_password'])) {
            $jwt_key = Xcart::app()->globals['jwt_key'];
            $payload = [
                'user' => $user->toArray(),
                'action' => 'reset_password',
                'one_time_password' => $data['one_time_password'],
            ];
            $this->jsonResponse(['resetPasswordToken' => JWT::encode($payload, $jwt_key)]);
        } else {
            $error_text = $one_time_password->isOutdated() ? 'OTP is deprecated' : 'OTP is wrong';
            $this->jsonResponse([
                'errors' => ['one_time_password' => [$error_text]],
                'one_time_password' => $one_time_password->toArray(),
            ]);
        }
    }

    public function resetPassword(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $jwt_key = Xcart::app()->globals['jwt_key'];
        $jwt_decoded = JWT::decode($data['resetPasswordToken'], $jwt_key, array('HS256'));
        /**
         * @var $one_time_password OneTimePasswordModel
         */
        $one_time_password = OneTimePasswordModel::objects()->get(['one_time_password', $jwt_decoded->one_time_password]);

        if ($one_time_password->isOutdated()) {
            $this->jsonResponse(['errors' => ['otp' => 'outdated']]);
            $one_time_password->delete();
            return;
        }

        //session is invalid
        if (!$one_time_password && $one_time_password->isOutdated()) {
            $this->jsonResponse(['errors' => ['oneTimePassword' => ['Invalid Session please try again']]]);
            return;
        }

        /**
         * @var UserModel $user
         */
        $user = UserModel::objects()->get(['user_id' => $jwt_decoded->user->id]);
        $user->changePassword($data['password']);
        $this->jsonResponse(['user' => $user]);
    }
}
