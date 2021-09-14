<?php
namespace Modules\User\Components;

use Modules\User\Helpers\BotsHelper;
use Modules\User\Models\SessionDataModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Request\Session;

class XcartSession extends Session
{
    public $autoStart = false; //@NOTE: Do not turn on. Initialization on first access
    public $autoGc = false;
    public $registerGlobals = true;
    public $fullUnpackGlobals = false;
    private $session_key;
    private $data = [];
    private $unpacked = [];
    /**
     * @var \Modules\User\Models\SessionDataModel
     */
    private $model = null;

    public function add($key, $value)
    {
        $this->open();

        $this->data[ $key ] = $value;

        if ($this->registerGlobals) {
            $GLOBALS[ $key ] = $value;
            $this->unpacked[ $key ] = $key;
        }
    }

    public function has($key)
    {
        $this->open();

        return array_key_exists($key, isset($this->data) ? $this->data : []);
    }

    public function get($key, $default = null)
    {
        $value = $this->has($key) ? $this->data[ $key ] : $default;

        if ($this->registerGlobals && isset($GLOBALS[ $key ]) && isset($this->unpacked[ $key ])) {
            return $GLOBALS[ $key ];
        }
        else if ($this->registerGlobals) {
            $GLOBALS[ $key ] = $value;
            $this->unpacked[ $key ] = $key;
        }

        return $value;
    }

    public function all()
    {
        $this->open();
        return $this->data;
    }

    public function close()
    {
        $this->save();

        if ($this->autoGc && !Cli::isCli()) {
            $this->gc();
        }
    }

    public function save()
    {
        if ($this->getId()) {
            if ($this->registerGlobals) {
                $this->collectFromGlobals();
            }
            $this->model->data = $this->data;
            $this->model->save();
        }
    }

    public function collectGlobals($vars = null)
    {
        $this->open();

        if (!empty($vars)) {
            foreach ($vars as $key) {
                if (isset($GLOBALS[ $key ])) {
                    $this->data[ $key ] = $GLOBALS[ $key ];
                }
            }
        }
        else {
            $this->collectFromGlobals();
        }
    }

    private function collectFromGlobals()
    {
        if (is_array($this->data)) {
            foreach ($this->data as $key => $value) {
                if (isset($GLOBALS[ $key ]) && isset($this->unpacked[ $key ] )) {
                    $this->data[ $key ] = $GLOBALS[ $key ];
                }
            }
        }
    }

    private function unpackToGlobals()
    {
        if (is_array($this->data)) {
            $this->unpacked = [];

            foreach ($this->data as $key => $value) {
                $GLOBALS[ $key ] = $value;
                $this->unpacked[ $key ] = $key;
            }
        }
    }

    public function open($ssid = null)
    {
        if ($this->getIsActive() && !$ssid ) {
            return $this;
        }

        if ($this->getIsActive() && $this->getId() == $ssid) {
            return $this;
        }

        $this->start($ssid);

        return $this;
    }

    /**
     * Обновить срок хранения сессии
    */
    public function updateSessionTime() {
        $session_time = Xcart::app()->getModule('User')->EXP_TIME_S;
        $this->model->expiry = time() + $session_time;
        $this->request->cookie->add($this->getSessionKey(), $this->getId(), $this->model->expiry);
    }

    public function start($id = null)
    {
        if ($id || $this->startValidate()) {
            if ($id || $id = $this->getSessionId()) {
                if ($this->model = SessionDataModel::objects()->get(['sessid' => $id])) {
                    $this->data = $this->model->data;

                    if ($this->registerGlobals && $this->fullUnpackGlobals) {
                        $this->unpackToGlobals();
                    }
                }
            }

            //создать новую сессию
            if (!$this->model) {
                $this->model = new SessionDataModel();

                if (APP_LOCAL === true || !\defined('IS_ROBOT')) {
                    $this->model->save();
                }

                $this->updateSessionTime();

                $this->data = [];
                $this->unpacked = [];
            }

            //если срок хранения сессии вышел более чем на треть -- обновить его
            $exp_time = Xcart::app()->getModule('User')->EXP_TIME_S;
            $start_time = $this->model->expiry - $exp_time;
            $passed_time = time() - $start_time;
            $one_third_exp_time = $exp_time / 3;

            if ($passed_time >= $one_third_exp_time) {
                $this->updateSessionTime();
            }

            //де-аутентифицировать пользователя если сессия устарела
            $two_hours_s = 2 * 60 * 60;
            if (
                $passed_time > $exp_time
                || ($this->get('remember_me') === false && $passed_time > $two_hours_s)
            ) {
                Xcart::app()->request->session->remove((new Auth)->authSessionName);
            }

            if ($this->registerGlobals) {
                $GLOBALS['XCARTSESSID'] = $id;
                $GLOBALS[$this->getSessionKey()] = $id;
                $GLOBALS['XCART_SESSION_NAME'] = $this->getSessionKey();
                $GLOBALS['XCART_SESSION_EXPIRY'] = $this->model->expiry;
                defined('XCART_SESSION_START') ?: define('XCART_SESSION_START', 1);
            }
        }
    }

    private function getSessionId()
    {
        $key = $this->getSessionKey();

        return $this->request->post->get($key) ?:
                $this->request->get->get($key) ?:
                    $this->request->cookie->get($key);
    }

    public function getSessionKey()
    {
        $key = 'xid';

        if (!$this->session_key) {
            /** @var \Modules\Sites\SitesModule $module */
            if ($module = Xcart::app()->getModule('Sites')) {
                $key .= $module->getSite()->pk;
            }

            $this->session_key = $key;
        }

        return $this->session_key;
    }

    public function getId()
    {
        return ($this->model) ? $this->model->sessid : null;
    }

    public function getIsActive()
    {
        return $this->getId() ? true : false;
    }

    public function getStorage()
    {
        $this->open();
        return $this->model;
    }

    private function genSessId()
    {
        do {
            $ssid = md5(uniqid(rand()));
        }
        while (SessionDataModel::objects()->filter(['sessid' => $ssid])->count());

        return $ssid;
    }

    public function regenerateID($deleteOldSession = false)
    {
        if ($this->getIsActive() && !headers_sent()) {
            if ($deleteOldSession) {
                $this->model->delete();
            }

            $this->request->cookie->remove($this->getSessionKey());
            $this->start();
        }
    }

    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->data[ $key ]);

            if ($this->registerGlobals) {
                unset($GLOBALS[ $key ]);
                unset($this->unpacked[ $key ]);
            }
        }
    }

    public function clear()
    {
        $this->data = [];
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    public function count()
    {
        return count($this->data);
    }

    public function gc($limit = 1)
    {
        SessionDataModel::objects()->filter(['expiry__lt' => time()])->limit($limit)->delete();
    }

    public function startValidate()
    {
        $start = !isset($_COOKIE['418']); // inner use for html invalidator
        $start = $start ?:!BotsHelper::IsBot();

        return $start;
    }
}