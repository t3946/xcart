<?php
/**
 * Восстановление пароля в админке
 */

namespace Modules\Admin\Forms;

use Xcart\App\QueryBuilder\Q\QOr;
use Modules\User\Models\UserModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Form;
use Xcart\App\Main\Xcart;

class ResetPasswordForm extends Form
{
    public function getFields()
    {
        return [
            'email' => [
                'class' => CharField::className(),
                'label' => 'email',
                'required' => true
            ],
        ];
    }

    public function afterValidate($owner, $isValid)
    {
        if ($isValid) {
            $email = $this->getAttributes()['email'];
            $users = UserModel::objects()->filter([new QOr(['email' => $email])])->all();

            if (count($users) === 0) {
                $this->addError('email', "Users with email $email not found");
            }
        }
    }

    public function resetPassword()
    {
        global $mail_smarty;

        $email = $this->getAttributes()['email'];
        $users = UserModel::objects()->filter(['email' => $email, 'usertype' => 'A'])->all();
        $mail_smarty->assign("accounts", $users);

        foreach ($users as $key => $user) {
            $user["password"] = text_decrypt($user["password"]);

            if (is_null($user["password"]) || $user["password"] === false) {
                $user["password"] = func_get_langvar_by_name("err_data_corrupted");

                if (is_null($user["password"])) {
                    Xcart::app()->flash->error("Could not decrypt password for the user " . $user['login']);
                }
            }
        }



        $config = Xcart::app()->getModule('Sites')->getSite()->getGlobalConfig();
        $from = $config["support_department"];

        func_send_mail($email, "mail/password_recover_subj.tpl", "mail/password_recover.tpl", $from, false);
    }
}