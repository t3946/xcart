<?php
namespace Modules\User\Components;


use Firebase\JWT\JWT;
use Modules\User\Models\UserModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Interfaces\AuthInterface;
use Xcart\App\Main\Xcart;

class Auth implements AuthInterface
{
    use SmartProperties;

    /**
     * @var UserModel
     */
    protected $_user = null;

    /**
     * @var string
     */
    public $authCookieName = 'USER';

    /**
     * @var string
     */
//    public $authSessionName = 'USER_ID';
    public $authSessionName = 'admin_login';

    public $class = 'Modules\User\Models\UserModel';
    public $newUserClass = 'Modules\User\Models\UserAccount\UserModel';

    public function login($user, $remember_me = false)
    {
        $this->updateSession($user);
        Xcart::app()->request->session->add('remember_me', $remember_me);
        $this->setUser($user);
    }

    /**
     * @param bool $clearSession
     * @internal param bool $total Clear all session
     */
    public function logout($clearSession = true)
    {
        $this->removeSession($clearSession);
        $this->removeCookie();
        $this->_user = null;
    }

    /**
     * @param bool $new_user Если true, то работать будет с пользователем из xcart_users(из обновы с личным кабинетом)
     */
    public function getUser($new_user = false)
    {
        if (!$this->_user || $new_user) {
            $this->_user = $this->fetchUser($new_user);
        }
        return $this->_user;
    }

    public function setUser($user)
    {
        $this->_user = $user;
        $this->updateCookie($user);
        $this->updateSession($user);
    }

    public function fetchUser($new_user = false)
    {
        $user = null;

        if (!Cli::isCli()) {
            $user = $this->getSessionUser($new_user);

            if (!$user) {
                if ($user = $this->getCookieUser($new_user)) {
                    if (!$new_user) {
                        $this->updateSession($user);
                    }
                }
            }
        }

        if (!$user) {
            $class = $new_user ? $this->newUserClass : $this->class;
            $user = new $class();
        }

        return $user;
    }

    /**
     * Find user in database by id or login
     *
     * @param int|string $id
     * @param bool $new_user true если нужно работать с моделью новго пользователя
     * @return mixed
     */
    public function findUser($id, $new_user = false)
    {
        if ($new_user) {
            $class = \Modules\User\Models\UserAccount\UserModel::class;
            return $class::objects()->filter(['user_id' => $id])->limit(1)->get();
        } else {
            $class = $this->class;
            return $class::objects()->filter(['id' => $id])->limit(1)->get();
        }
    }

    public function getSessionUser($new_user = false)
    {
        if ($new_user) {
            return null;
        }

        $user_id = $this->getSession();

        if ($user_id) {
            return $this->findUser($user_id, $new_user);
        }

        return null;
    }

    public function getCookieUser($new_user = false)
    {
        $cookie = $this->getCookie($new_user);
        if ($cookie) {
            if ($new_user) {
                if ($cookie->timeout < time() * 1000) {
                    return null;
                }

                $user = $this->findUser($cookie->userId, $new_user);

                if ($cookie->accessToken !== $user->access_token) {
                    return null;
                }

                return $user;
            }
            $data = explode(':', $cookie);
            if (count($data) == 2) {
                $id = $data[0];
                $key = $data[1];

                $user = $this->findUser($id, $new_user);
                if ($user && password_verify($user->email . $user->password, $key)) {
                    return $user;
                }
            }
        }
        return null;
    }

    public function updateSession($user)
    {
        $this->setSession($user);
    }

    public function updateCookie( $user)
    {
        $login = $user->login ??$user->email;
        $id = $user->id ?? $user->user_id;

        $value = implode(':', [$id, password_hash($login . $user->password, PASSWORD_DEFAULT)]);
        $this->setCookie($value);
    }

    public function setSession($user)
    {
        $login = $user->login ??$user->email;
        $id = $user->id ?? $user->user_id;

        Xcart::app()->request->session->add($this->authSessionName, $id);
        Xcart::app()->request->session->add('login',  $login);
        Xcart::app()->request->session->setUserId((int)$id);
    }

    public function getSession()
    {
        return Xcart::app()->request->session->get($this->authSessionName);
    }

    public function removeSession($clearSession = true)
    {
        if ($clearSession) {
            Xcart::app()->request->session->destroy();
        } else {
            Xcart::app()->request->session->remove($this->authSessionName);
        }
    }
    
    public function setCookie($cookie)
    {
        Xcart::app()->request->cookie->add($this->authCookieName, $cookie, time() + $this->expire, '/');
    }
    
    public function getCookie($new_user = false)
    {
        if ($new_user && $jwt_cookie = Xcart::app()->request->cookie->get('session')) {
            return JWT::decode($jwt_cookie, 'h93h84fp83', array('HS256'));
        }
        return Xcart::app()->request->cookie->get($this->authCookieName);
    }

    public function removeCookie()
    {
        Xcart::app()->request->cookie->remove($this->authCookieName);
        Xcart::app()->request->cookie->remove(Xcart::app()->request->session->getSessionKey());
    }
}