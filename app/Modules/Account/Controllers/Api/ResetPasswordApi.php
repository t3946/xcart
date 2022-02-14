<?php

namespace Modules\Account\Controllers\Api;

use Aws\Sns\SnsClient;
use Modules\Account\Models\OneTimePasswordModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Firebase\JWT\JWT;

class ResetPasswordApi extends Controller
{
    private static function getOneTimePassword(int $user_id): array
    {
        /**
         * @var OneTimePasswordModel $otp
         */
        [$otp, $is_new] = OneTimePasswordModel::objects()->getOrCreate(['user_id' => $user_id]);

        //regenerate obsolete otp
        if (!$otp->isNew()) {
            $otp->delete();
            [$otp, $is_new] = OneTimePasswordModel::objects()->getOrCreate(['user_id' => $user_id]);
        }

        return [$otp, $is_new];
    }

    public function sendEmail($user): void
    {
        [$otp, $is_new] = self::getOneTimePassword($user->user_id);

        if ($is_new) {
            Xcart::app()->mail->raw(
                $user->email,
                'Password Assistance OTP',
                'Your reset password OTP is: ' . $otp->one_time_password,
                [
                    'from' => 'helpdesk@s3stores.com',
                ]
            );
        }

        $this->jsonResponse($otp->toArray());
    }

    private function sendSMS($user): void
    {
        [$otp, $is_new] = self::getOneTimePassword($user->user_id);

        if ($is_new || true) {
            $message = "Your reset password OTP: {$otp->one_time_password}. Don't pass this code third party.";

            $params = [
                'credentials' => Xcart::app()->globals['aws']['sns']['credentials'],
                'region' => 'us-east-1',
                'version' => 'latest'
            ];

            $sns = new SnsClient($params);

            $args = [
                "MessageAttributes" => [
                    'AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String',
                        'StringValue' => 'S3Stores'
                    ],
                    'AWS.SNS.SMS.SMSType' => ['DataType' => 'String', 'StringValue' => 'Transactional']
                ],
                "PhoneNumber" => $user->phone,
                "Message" => $message,
            ];

            $sns->publish($args);
            Xcart::app()->logger->debug([
                "message" => $message,
                "PhoneNumber" => $user->phone,
            ]);
        }

        $this->jsonResponse(["otp" => $otp->toArray(), "result" => $result]);
    }

    public function sendOneTimePassword(): void
    {
        $login = json_decode(file_get_contents('php://input'), true)['login'];
        $user = UserModel::getUserByLogin($login);

        if ($user === null) {
            $this->jsonResponse(['errors' => ['login' => ['User not found']]]);
            return;
        }

        if ($login === $user->email) {
            $this->sendEmail($user);
        } elseif ($login === $user->phone) {
            $this->sendSMS($user);
        }
    }

    public function verifyOneTimePassword(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = UserModel::getUserByLogin($data['login']);
        $user_id = $user->getAttribute('user_id');

        /**
         * @var OneTimePasswordModel $otp
         */
        $otp = OneTimePasswordModel::objects()->get(['user_id' => $user_id]);

        // old otp
        if ($otp === null || $otp->isOutdated()) {
            $this->jsonResponse(['errors' => ['otp' => 'outdated']]);

            if ($otp) {
                $otp->delete();
            }

            return;
        }

        // limit attempts exhausted
        if ($otp->isLimitExhausted()) {
            $this->jsonResponse(['errors' => ['otp' => 'Limit attempts exhausted']]);
            return;
        }

        //wrong otp
        $is_code_correct = $otp->matchCode($data['otp']);

        if ($is_code_correct) {
            $jwt_key = Xcart::app()->globals['jwt_key'];
            $jwt_payload = [
                'action' => 'reset_password',
                'user_id' => $user->user_id,
                'otp_code' => $data['otp'],
            ];
            $this->jsonResponse(['resetPasswordToken' => JWT::encode($jwt_payload, $jwt_key)]);
        } else {
            $this->jsonResponse([
                'errors' => ['otp' => ['OTP is wrong']],
                'otp' => $otp->toArray(),
            ]);
        }
    }

    public function resetPassword(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $jwt_key = Xcart::app()->globals['jwt_key'];
        $jwt_decoded = JWT::decode($data['resetPasswordToken'], $jwt_key, array('HS256'));
        $user = UserModel::objects()->get(["user_id" => $jwt_decoded->user_id]);
        /**
         * @var $one_time_password OneTimePasswordModel
         */
        $one_time_password = OneTimePasswordModel::objects()->get(
            [
                'user_id' => $user->user_id,
                'one_time_password' => $jwt_decoded->otp_code,
            ],
        );

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

        $user->password = $data['password'];
        $user->save();
        $this->jsonResponse([]);
    }
}
