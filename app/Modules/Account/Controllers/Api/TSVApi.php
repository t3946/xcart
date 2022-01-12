<?php

namespace Modules\Account\Controllers\Api;

use Modules\User\Models\UserAccount\UserModel;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class TSVApi extends FrontendController
{
    //create new QR-code and send url to it
    public function emit($account_name): array
    {
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $site = Xcart::app()->getModule('Sites')->getSite();
        $issuer = $site->getCompanyName();

        return $secret;
    }

    public function confirmCodeAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = UserModel::objects()->get(["user_id" => $data["userId"]]);
        $g = new GoogleAuthenticator();
        $code = $data["code"];
        $secret = $user->getAttribute('tsv_secret');
        $count = $user->getAttribute('tsv_count');

        if (!$secret) {
            $secret = $g->generateSecret();
            $user->setAttribute('tsv_secret', $secret);
            $count = 0;
            $user->setAttribute('tsv_count', $count);
            $user->save();
        }

        if ($g->checkCode($secret, $code)) {
            $user->setAttribute('tsv_count', $count + 1);
            $user->save();
            $attributes = $user->getAttributes();
            unset($attributes["password"]);
            $this->jsonResponse(["user" => $attributes]);
        } else {
            $this->jsonResponse(["errors" => [
                "code" => "Code is invalid",
            ]]);
        }
    }

    public function getAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = UserModel::objects()->get(["user_id" => $data["userId"]]);
        $secret = $user->tsv_secret;

        if (!$secret) {
            $g = new GoogleAuthenticator();
            $secret = $g->generateSecret();
            $user->setAttribute('tsv_secret', $secret);
            $user->save();
        }

        $site = Xcart::app()->getModule('Sites')->getSite();
        $issuer = $site->getCompanyName();
        $url = GoogleQrUrl::generate($user->email, $secret, $issuer);
        $this->jsonResponse(["url" => $url, "secret" => $secret]);
    }

    public function disableAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = UserModel::objects()->get(["user_id" => $data["userId"]]);
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $user->setAttribute('tsv_count', 0);
        $user->setAttribute('tsv_secret', $secret);
        $user->save();

        $attributes = $user->getAttributes();
        unset($attributes["password"]);

        $this->jsonResponse(["user" => $attributes]);
    }
}