<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 07/08/16 16:17
 */

namespace Modules\Admin\Forms;

use Xcart\App\Orm\ModelInterface;
use Xcart\App\QueryBuilder\Q\QOr;
use Modules\User\Models\UserModel;
use Modules\User\UserModule;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\PasswordField;
use Xcart\App\Form\Form;
use Xcart\App\Main\Xcart;

class LoginForm extends Form
{
    public function getFields()
    {
        return [
            'login' => [
                'class' => CharField::class,
                'label' => 'Login or Email',
                'required' => true
            ],
            'password' => [
                'class' => PasswordField::class,
                'label' => 'Password',
                'required' => true
            ]
        ];
    }

    public function afterValidate($owner, $isValid)
    {
        if ($isValid) {
            $attributes = $this->getAttributes();

            $login = $attributes['login'];
            $password = $attributes['password'];

            $hasher = UserModule::getPasswordHasher();

            $user = $this->getUser($login);
            if ($user) {
                if (!$hasher::verify($password, $user->password)) {
                    $this->addError('password', 'Incorrect password');
                }
            } else {
                $this->addError('login', 'User not found');
            }
        }
    }

    public function login()
    {
        $data = $this->getAttributes();
        $user = $this->getUser($data['login']);
        if ($user) {
            Xcart::app()->auth->login($user, Xcart::app()->request->post->get('is_remember') === 'Y');
        }

        $session = Xcart::app()->request->session;
        $session_key = Xcart::app()->request->session->getSessionKey();
        $session_id = $session->getId();
        Xcart::app()->request->cookie->add($session_key, $session_id);
    }

    public function getUser($login): ?ModelInterface
    {
        return UserModel::objects()->filter([ new QOr(['login' => $login, 'email' => $login]), 'status' => 'Y'])->get();
    }
}