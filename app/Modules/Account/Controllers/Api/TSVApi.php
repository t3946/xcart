<?php

namespace Modules\Account\Controllers\Api;

use Modules\User\Models\UserAccount\UserModel;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Modules\Account\Models\AuthenticatorsModel;
use Modules\Account\Models\TSVRecoveryModel;
use Xcart\App\QueryBuilder\Q\QOr;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TSVApi extends Controller
{
    /**
     * generate new tsv secret and qr cod
    */
    public function generate()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $secret = (new GoogleAuthenticator())->generateSecret();
        $site = Xcart::app()->getModule('Sites')->getSite();
        $issuer = $site->getCompanyName();
        $url = GoogleQrUrl::generate($data['accountName'], $secret, $issuer);
        $this->jsonResponse([
            "secret" => $secret,
            "url" => $url,
        ]);
    }

    public function checkCode()
    {

        Xcart::app()->logger->debug("checkCode");

        $data = json_decode(file_get_contents('php://input'), true);
        $g = new GoogleAuthenticator();
        $check_result = false;

        if ($data['userId']) {
            $authenticators = AuthenticatorsModel::objects()->all(["user_id" => $data['userId']]);

            foreach ($authenticators as $i => $authenticator) {
                if ($g->checkCode($authenticator->secret, $data['code'])) {
                    $check_result = true;
                    break;
                }
            }
        } else if ($data['secret']) {
            $check_result = $g->checkCode($data['secret'], $data['code']);
        }

        $this->jsonResponse(["checkResult" => $check_result]);
    }

    public function recovery()
    {
        $login = $_POST['login'];
        $user = UserModel::objects()->get(new QOr(['phone' => $login, 'email' => $login]));
        $file = $_FILES['file'];

        $uploaded_file = new UploadedFile(
            $file['tmp_name'],
            $file['name'],
            $file['type'],
            (int)$file['size'],
            (int)$file['error'],
        );

        $model = new TSVRecoveryModel([
            'user_id' => $user->user_id,
            'document_path' => $uploaded_file,
        ]);

        $model->save();
        http_response_code(200);
    }
}