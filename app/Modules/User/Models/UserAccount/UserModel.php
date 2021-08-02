<?php

namespace Modules\User\Models\UserAccount;

use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\UserAddressModel;
use Modules\User\Helpers\PasswordHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

/**
 * @property string password
 * @property mixed login
 */
class UserModel extends Model
{
    const SALT = 'Alexander';

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
                'class' => IntField::class,
                'null' => true,
                'default' => null,
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
            'cart_number' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'addresses' => [
                'class' => HasManyField::class,
                'modelClass' => AddressesModel::class,
                'link' => ['user_id' => 'user_id']
            ]
        ];
    }

    public function register()
    {
        $this->password = PasswordHelper::hash($this->password);
        $this->save();
    }

    /**
     * авторизует пользователя в сессии и сохраняет сессионные куки
    */
    public function authenticate($remember_me = false): void
    {
        Xcart::app()->auth->login($this, $remember_me);

        //сохранить сессионную куку
        $session = Xcart::app()->request->session;
        $session->updateSessionTime();
        $session_key = Xcart::app()->request->session->getSessionKey();
        $session_id = $session->getId();

        if (!$session_id) {
            Xcart::app()->request->session->start();
        }

        Xcart::app()->request->cookie->add($session_key, $session_id);
    }

    public function login(string $password, bool $remember_me = false): bool
    {
        $hash = $this->getAttribute('password');

        // проверка подлинности не пройдена
        if (!PasswordHelper::verify($password, $hash)) {
            return false;
        }

        $this->authenticate($remember_me);

        return true;
    }

    public function getIsGuest()
    {
        return $this->isNewRecord;
    }

    /**
     * получить массив данных о пользователе (нужно для передачи на frontend)
     */
    public function toArray(): array
    {
        return [
            'id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
