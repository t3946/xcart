<?php

namespace Modules\User\Models\UserAccount;

use Modules\Account\Models\AddressesModel;
use Modules\User\Helpers\PasswordHelper;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

/**
 * @property string password
 * @property mixed login
 * @property string user_id
 * @property string name
 * @property string email
 * @property string phone
 * @property string location
 * @property string public_name
 * @property string phone_country_code
 * @property string tsv_secret
 * @property string tsv_count
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
            'location' => [
                'class' => CharField::class,
                'null' => true,
                'default' => '',
            ],
            'public_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => '',
            ],
            'avatar_image' => [
                'class' => ImageField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 'www',
                'uploadTo' => 'images/user_avatar/',
                'maxSize' => 100 * 1024,
                'types' => ['png', 'jpeg', 'jpg'],
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
            'phone_country_code' => [
                'class' => CharField::class,
                'required' => true,
            ],
            'password' => [
                'class' => CharField::class,
                'null' => true,
                'unique' => false,
            ],
            'addresses' => [
                'class' => HasManyField::class,
                'modelClass' => AddressesModel::class,
                'link' => ['user_id' => 'user_id']
            ],
            'cart_number' => [
                'class' => CharField::class,
                'null' => true,
                'unique' => false,
            ],
            'tsv_secret' => [
                'class' => CharField::class,
            ],
            'tsv_count' => [
                'class' => IntField::class,
            ],
        ];
    }

    public function register()
    {
        $this->password = PasswordHelper::hash($this->password);
        $g = new GoogleAuthenticator();
        $this->tsv_secret =$g->generateSecret();
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

        $expiry = time() + Xcart::app()->getModule('User')->EXP_TIME_S;
        Xcart::app()->request->cookie->add($session_key, $session_id, $expiry);
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
     * Получить массив данных о пользователе (нужно для передачи на frontend)
     */
    public function toArray(): array
    {
        $avatar_image = $this->avatar_image->getValue();
        $site = Xcart::app()->getModule('Sites')->getSite();
        $issuer = $site->getCompanyName();

        return [
            'id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar_image' => $avatar_image ? '/' . $avatar_image : '',
            'location' => $this->location,
            'public_name' => $this->public_name,
            'phone_country_code' => $this->phone_country_code,
            'tsv' => [
                'url' => GoogleQrUrl::generate($this->email, $this->tsv_secret, $issuer),
                'secret' => $this->tsv_secret,
                'count' => (int)$this->tsv_count,
            ]
        ];
    }
}
