<?php
namespace Xcart\App\Request;

class XcartSession extends Session
{

    public function add($key, $value)
    {
        $GLOBALS['XCART_SESSION_VARS'][$key] = $value;
    }

    public function has($key)
    {
        return array_key_exists($key, isset($GLOBALS['XCART_SESSION_VARS']) ? $GLOBALS['XCART_SESSION_VARS'] : []);
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $GLOBALS['XCART_SESSION_VARS'][$key] : $default;
    }

    public function all()
    {
        return $GLOBALS['XCART_SESSION_VARS'];
    }

    public function close()
    {
        return;

        if ($this->getId())
        {
            x_session_save();

            if (defined('x_session_save_to_db__do_not_use') && x_session_save_to_db__do_not_use == 'Y') {
                define('x_session_save_to_db__do_not_use', '');
            }
            else {
                x_session_save_to_db();
            }
        }
    }

//    public function open()
//    {
//        if ($this->getIsActive()) {
//            return;
//        }
//
//        $this->registerGlobalSessId();
//
//        parent::open();
//    }
//
//    private function registerGlobalSessId()
//    {
//        $GLOBALS['XCARTSESSID'] = false;
//
//        $model = StorefrontModel::objects()->filter(['domain' => $_SERVER['HTTP_HOST']])->limit(1)->get();
//        if ($model) {
//            $key = 'xid'.$model->storefrontid;
//        }
//        else {
//            $key = 'xid0';
//        }
//
//        if (!empty($_COOKIE[$key])) {
//            $id = $_COOKIE[$key];
//        }
//
//        $GLOBALS['XCARTSESSID'] = $id;
//
//        x_session_start($id);
//    }

    public function remove($key)
    {
        if ($this->has($key)) {
            unset($GLOBALS['XCART_SESSION_VARS'][$key]);
        }
    }

    public function clear()
    {
        foreach (array_keys($GLOBALS['XCART_SESSION_VARS']) as $key) {
            unset($GLOBALS['XCART_SESSION_VARS'][$key]);
        }
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $GLOBALS['XCART_SESSION_VARS']);
    }

    public function getIsActive()
    {
        //@TODO: переписать
        return isset($GLOBALS['XCARTSESSID']);
    }

    public function count()
    {
        return count($GLOBALS['XCART_SESSION_VARS']);
    }

    public function getId()
    {
        return $GLOBALS['XCARTSESSID'];
    }
}