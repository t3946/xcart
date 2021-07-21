<?php

namespace Modules\User\Models\UserAccount;

use Modules\User\Helpers\PasswordHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property string password
 * @property mixed login
 */
class UserModel extends Model
{
    public static function tableName()
    {
        return 'xcart_users';
    }

    public static function getFields()
    {
        return [
            'user_id' => [
                'class' => AutoField::class,
            ],
            'name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'avatar_id' => [
                'class' => AutoField::class,
                'null' => true,
                'default' => '',
            ],
            'rate_us' => [
                'class' => CharField::class,
                'null' => true,
                'default' => '',
            ],
            'experience_comment' => [
                'class' => CharField::class,
                'null' => true,
                'default' => '',
            ],
            'email' => [
                'class' => CharField::class,
                'null' => false,
                'unique' => true,
            ],
            'phone' => [
                'class' => CharField::class,
                'null' => false,
                'unique' => true,
            ],
            'password' => [
                'class' => CharField::class,
                'null' => true,
                'unique' => false,
            ],
        ];
    }

    public function register()
    {
        $this->password = PasswordHelper::hash($this->password);
        $this->save();
    }

    public function login(string $password): bool
    {
        $hash = $this->getAttribute('password');

        // проверка подлинности не пройдена
        if (!PasswordHelper::verify($password, $hash)) {
            return false;
        }

        //сохранить сессию
        Xcart::app()->auth->login($this);

        //сохранить сессионную куку
        $session = Xcart::app()->request->session;
        $session_key = Xcart::app()->request->session->getSessionKey();
        $session_id = $session->getId();

        if (!$session_id) {
            Xcart::app()->request->session->start();
        }

        Xcart::app()->request->cookie->add($session_key, $session_id);

        return true;
    }

    public function logout(): bool
    {

    }

    public function getAttributes(): array
    {
        $attributes = parent::getAttributes();
        unset($attributes['password']);
        return $attributes;
    }

    public function getIsGuest()
    {
        return $this->isNewRecord;
    }
}
