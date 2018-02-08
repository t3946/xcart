<?php

namespace Xcart\App\Components;

use Xcart\App\Helpers\ClassNames;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Main\Xcart;

class Flash
{
    use SmartProperties, ClassNames;

    const SESSION_KEY = 'FLASH';
    const DEFAULT_TIME = 5000;

    CONST TYPE_SUCCESS = 'success';
    CONST TYPE_ERROR = 'error';
    CONST TYPE_INFO = 'info';

    public function success($message, $time = null)
    {
        $this->add($message, self::TYPE_SUCCESS, $time);
    }

    public function error($message, $time = null)
    {
        $this->add($message, self::TYPE_ERROR, $time);
    }

    public function info($message, $time = null)
    {
        $this->add($message, self::TYPE_INFO, $time);
    }
    
    /**
     * @param $message
     * @param string $type "success"|"error"|"info"
     * @param (int) $time time in miliseconds
     */
    public function add($message, $type = self::TYPE_SUCCESS, $time = null)
    {
        $messages = $this->getMessages();
        $messages[] = [
            'message' => $message,
            'type' => $type,
            'time' => $time ?: self::DEFAULT_TIME,
        ];

        $this->setMessages($messages);
    }

    public function addWithCode($code, $message, $type = self::TYPE_SUCCESS, $time=null)
    {
        $messages = $this->getMessages();
        $messages[$code] = [
            'message' => $message,
            'type' => $type,
            'time' => $time ?: self::DEFAULT_TIME,
        ];

        $this->setMessages($messages);
    }

    public function getMessages()
    {
        return array_merge(Xcart::app()->request->session->get(self::SESSION_KEY, []), []);
    }

    public function setMessages($messages = [])
    {
        Xcart::app()->request->session->add(self::SESSION_KEY, $messages);
    }

    public function clearMessages()
    {
        Xcart::app()->request->session->remove(self::SESSION_KEY);
    }

    public function read()
    {
        $messages = $this->getMessages();
        $this->clearMessages();
        return $messages;
    }
}