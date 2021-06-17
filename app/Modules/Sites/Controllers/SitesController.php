<?php

namespace Modules\Sites\Controllers;

use Modules\Admin\Controllers\BackendController;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

class SitesController extends BackendController
{
    public function logout()
    {
        $login_antibot_on = false;
        $login_attempt = "";
        x_session_register("identifiers", []);
        x_session_register("payment_cc_fields");
        $payment_cc_fields = array();
        func_unset($identifiers,$current_type);

        if (!empty($active_modules['Simple_Mode'])) {
            if ($current_type == 'A') func_unset($identifiers,'P');
            if ($current_type == 'P') func_unset($identifiers,'A');
        }

        #
        # Insert entry into login_history
        #
        $utype = func_query_first_cell("SELECT usertype FROM $sql_tbl[customers] WHERE login = '$login'");
        if (!empty($active_modules['Simple_Mode']) && $utype == 'A')
            $utype = 'P';
        db_query("REPLACE INTO $sql_tbl[login_history] (login, date_time, usertype, action, status, ip) VALUES ('$login','".time()."','$utype','logout','success','$REMOTE_ADDR')");

        $old_login_type = $current_type;
        $login = "";
        $login_type = "";
        $cart = "";
        $access_status = "";
        $merchant_password = "";
        $logout_user = true;
        if ($current_type == 'A' || $current_type == 'P')
            func_ge_erase();

        x_session_unregister("hide_security_warning");
        x_session_register("login_redirect");
        $login_redirect = 1;
    }

    /**
     * переключает сайт и возвращает ссылку на логотип нового сайта
    */
    public function setSite(int $site_id)
    {
        Xcart::app()->request->session->add('current_storefront', $site_id);
        //site logo
        $site_code = strtolower(Xcart::app()->getModule('Sites')->getSelectedSite()->code);
        $this->jsonResponse(["logoUrl" => Paths::get('dist') . "/images/logos/sites/$site_code/logo.svg"]);
    }
}
