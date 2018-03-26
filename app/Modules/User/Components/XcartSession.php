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

    public function start($id = null)
    {
        $isNew = false;

        if (!BotsHelper::IsBot() || $id) {
            if ($id || $id = $this->getSessionId()) {
                if ($this->model = SessionDataModel::objects()->get(['sessid' => $id])) {
                    $this->data = $this->model->data;

                    if ($this->registerGlobals && $this->fullUnpackGlobals) {
                        $this->unpackToGlobals();
                    }
                }
            }

            if (!$this->model) {
//                $id = $this->genSessId();
//                list($this->model, $isNew) = SessionDataModel::objects()->getOrCreate(['sessid' => $id]);

                $this->model = new SessionDataModel();
                $this->model->save();
                $isNew = true;
                $id = $this->getId();

                $this->data = [];
                $this->unpacked = [];
            }

            $sessionTime = Xcart::app()->getModule('User')->sessionTime;

            if ($isNew || ($this->model->expiry < (($sessionTime + time()) / 3)))
            {
                $this->model->expiry = time() + $sessionTime;
                $this->request->cookie->add($this->getSessionKey(), $id, $this->model->expiry);
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
                if ($model = $module->getSite()) {
                    $key .= $model->storefrontid;
                }
            }

            $this->session_key = $key;
        }

        return $this->session_key;
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

    public function gc($limit = 1)
    {
        SessionDataModel::objects()->filter(['expiry__lt' => time()])->limit($limit)->delete();
    }
}