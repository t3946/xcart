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
        return array_key_exists($key, $GLOBALS['XCART_SESSION_VARS']);
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $GLOBALS['XCART_SESSION_VARS'][$key] : $default;
    }

    public function all()
    {
        return $GLOBALS['XCART_SESSION_VARS'];
    }


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


    public function count()
    {
        return count($GLOBALS['XCART_SESSION_VARS']);
    }

    public function getId()
    {
        return $GLOBALS['XCARTSESSID'];
    }
}