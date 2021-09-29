<?php

namespace Modules\User\Models\UserAccount;

use Modules\Account\Models\AddressesModel;
use Modules\Account\Models\CreditCardsModel;
use Modules\Account\Models\ProductListsModel;
use Modules\Account\Models\TransactionsModel;
use Modules\Account\Models\UserListModel;
use Modules\User\Helpers\PasswordHelper;
use Modules\User\Models\FingerprintModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Xcart\App\Orm\ModelInterface;
use Xcart\App\QueryBuilder\Q\QOr;

/**
 * @property string password
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

    /**
     * get user by login
     * @param $login string email or phone
     * @return ModelInterface
    */
    public static function getUserByLogin(string $login):? ModelInterface {
        return self::objects()->get(new QOr(['email' => $login, 'phone' => $login]));
    }

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
            'cards' => [
                'class' => HasManyField::class,
                'modelClass' => CreditCardsModel::class,
                'link' => ['user_id' => 'user_id']
            ],
            'transactions' => [
                'class' => HasManyField::class,
                'modelClass' => TransactionsModel::class,
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
            'lists' => [
                'class' => ManyToManyField::class,
                'modelClass' => ProductListsModel::class,
                'through' => UserListModel::class
            ]
        ];
    }

    public function register()
    {
        $this->password = PasswordHelper::hash($this->password);
        $g = new GoogleAuthenticator();
        $this->tsv_secret = $g->generateSecret();
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

    public function checkPassword(string $password): bool
    {
        $hash = $this->getAttribute('password');

        return PasswordHelper::verify($password, $hash);
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

    /**
     * Match qr-code secret with otp
     * @param string $otp one time password
     * @return boolean
     */
    public function checkTSVCode(string $otp): bool
    {
        $g = new GoogleAuthenticator();
        $secret = $this->getAttribute('tsv_secret');
        return $g->checkCode($secret, $otp);
    }

    public function checkFingerprint(string $fingerprint): bool
    {
        $count = FingerprintModel::objects()
            ->all([
                'user_id' => $this->getAttribute('user_id'),
                'fingerprint' => $fingerprint,
            ]);

        return count($count) > 0;
    }

    public function changePassword($new_password): void
    {
        $this->password = PasswordHelper::hash($new_password);
        $this->save();
    }
}