<?php
namespace Xcart\App\Request;

use Xcart\App\Main\Xcart;

class XcartSession extends Session
{
    private $know_variables= [];


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
//        /** @var \Modules\Sites\SitesModule $module */
//        if ($module = Xcart::app()->getModule('Sites')) {
//            if ($model = $module->getSite()) {
//                $key = 'xid' . $model->storefrontid;
//            }
//        }
//        else {
//            $key = 'xid0';
//        }
//
//        if ($this->request->cookie->get($key)) {
//            $id = $this->request->cookie->get($key);
//            $GLOBALS['XCARTSESSID'] = $id;
//        }
//        else {
//
//        }
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