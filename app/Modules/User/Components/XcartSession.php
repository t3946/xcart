<?php
namespace Modules\User\Components;

use Modules\User\Helpers\BotsHelper;
use Modules\User\Models\SessionDataModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Request\Session;

class XcartSession extends Session
{
    public $autoGc = true;
    public $registerGlobals = true;
    public $fullUnpackGlobals = false;
    private $session_key;
    private $data = [];
    /**
     * @var \Modules\User\Models\SessionDataModel
     */
    private $model = null;

    public function init()
    {
        Xcart::app()->event->on('app:end', [$this, 'close']);
        $this->open();
    }

    public function add($key, $value)
    {
        $this->data[ $key ] = $value;

        if ($this->registerGlobals) {
            $GLOBALS[ $key ] = $value;
        }
    }

    public function has($key)
    {
        return array_key_exists($key, isset($this->data) ? $this->data : []);
    }

    public function get($key, $default = null)
    {
        if ($this->registerGlobals && isset($GLOBALS[ $key ])) {
            return $GLOBALS[ $key ];
        }

        return $this->has($key) ? $this->data[ $key ] : $default;
    }

    public function all()
    {
        return $this->data;
    }

    public function close()
    {
        if ($this->getId()) {
            if ($this->registerGlobals) {
                $this->collectFromGlobals();
            }
            $this->model->data = $this->data;
            $this->model->save();
        }

        if ($this->autoGc) {
            $this->gc();
        }
    }

    public function collectGlobals($vars = null)
    {
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
                if (isset($GLOBALS[ $key ])) {
                    $this->data[ $key ] = $GLOBALS[ $key ];
                }
            }
        }
    }

    private function unpackToGlobals()
    {
        if (is_array($this->data)) {
            foreach ($this->data as $key => $value) {
                $GLOBALS[ $key ] = $value;
            }
        }
    }

    public function open($ssid = null)
    {
        if ($this->getIsActive() != $ssid) {
            return;
        }

        $this->registerSessionHandler();

        if ($this->autoStart || $ssid) {
            $this->start($ssid);
        }
    }

    public function start($id = null)
    {
        if (!BotsHelper::IsBot() || $id) {
            if ($id || $id = $this->getSessionId()) {
                if ($this->model = SessionDataModel::objects()->get(['pk' => $id])) {
                    $this->data = $this->model->data;

                    if ($this->registerGlobals && $this->fullUnpackGlobals) {
                        $this->unpackToGlobals();
                    }
                }
            }

            if (!$this->model) {
                $id = $this->genSessId();
                $this->model = SessionDataModel::objects()->getOrCreate(['sessid' => $id]);
                $this->data = [];
            }

            $this->request->cookie->add($this->getSessionKey(), $id, $this->model->expiry);

            if ($this->registerGlobals) {
                $GLOBALS['XCARTSESSID'] = $id;
                $GLOBALS['XCART_SESSION_EXPIRY'] = $this->model->expiry;
                define("XCART_SESSION_START", 1);
            }
        }
    }

    private function getSessionId()
    {
        $key = $this->getSessionKey();

        if ($id = $this->request->post->get($key)) {}
        else if ($id = $this->request->get->get($key)) {}
        else if ($id = $this->request->cookie->get($key)) {}
        
        return $id;
    }

    private function getSessionKey()
    {
        if (!$this->session_key) {
            $key = 'xid0';

            /** @var \Modules\Sites\SitesModule $module */
            if ($module = Xcart::app()->getModule('Sites')) {
                if ($model = $module->getSite()) {
                    $key = 'xid' . $model->storefrontid;
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

    public function gc()
    {
        SessionDataModel::objects()->filter(['expiry__lt' => time()])->limit(1)->delete();
    }
}