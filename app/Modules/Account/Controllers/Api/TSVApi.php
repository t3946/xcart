<?php

namespace Modules\Account\Controllers\Api;

use Modules\Sites\Helpers\StorageHelper;
use Modules\User\Models\UserAccount\UserModel;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class TSVApi extends FrontendController
{
    public function confirmCode()
    {
        /**
         * @var $user UserModel
         */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            return;
        }

        $g = new GoogleAuthenticator();
        $data = json_decode(file_get_contents('php://input'), true);
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
            $this->jsonResponse(["user" => $user->toArray()]);
        } else {
            $this->jsonResponse(["errors" => [
                "code" => "Code is invalid",
            ]]);
        }
    }

    public function disable() {
        /**
         * @var $user UserModel
         */
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            return;
        }

        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();
        $account_name = $user->email;
        $site = Xcart::app()->getModule('Sites')->getSite();
        $issuer = $site->getCompanyName();
        $url = GoogleQrUrl::generate($account_name, $secret, $issuer);
        $user->setAttribute('tsv_secret', $secret);
        $user->setAttribute('tsv_count', 0);
        $user->save();

        $this->jsonResponse([
            "user" => $user->toArray(),
            "tsv" => [
                "url" => $url,
                "secret" => $secret,
            ],
        ]);
    }
}