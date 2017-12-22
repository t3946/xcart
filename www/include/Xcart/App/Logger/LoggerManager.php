<?php

namespace Xcart\App\Logger;

use Exception;
use Monolog\Formatter\FormatterInterface;
use Xcart\App\Helpers\Accessors;
use Xcart\App\Helpers\Creator;
use Xcart\App\Traits\Configurator;

/**
 * Class LoggerManager
 * @package Xcart\App\Logger
 */
class LoggerManager
{
    use Accessors;

    public $defaultPatch;
    /**
     * @var array
     */
    public $formatters = [];
    /**
     * @var \Monolog\Formatter\NormalizerFormatter[]
     */
    private $_formatters = [];
    /**
     * @var array
     */
    public $handlers = [];
    /**
     * @var \Monolog\Handler\AbstractHandler[]
     */
    private $_handlers = [];
    /**
     * @var array
     */
    public $loggers = [];
    /**
     * @var \Monolog\Logger[]
     */
    private $_loggers = [];
    /**
     * @var array
     */
    private $defaultLogger = [
        'default' => [
            'class' => '\\Xcart\\App\\Logger\\Logger',
            'handlers' => ['default']
        ],
    ];
    /**
     * @var array
     */
    private $defaultHandler = [
        'default' => [
            'class' => '\\Xcart\\App\\Logger\\Handler\\RotatingFileHandler',
            'level' => 'DEBUG',
            'formatter' => 'default',
        ]
    ];
    /**
     * @var array
     */
    private $defaultFormatter = [
        'default' => [
            'class' => '\\Xcart\\App\\Logger\\Formatters\\LineFormatter',
            'allowInlineLineBreaks' => true,
            'includeStacktrace' => true
        ]
    ];

    /**
     * @throws \Exception
     */
    public function init()
    {
        $this->formatters = array_replace_recursive($this->defaultFormatter, $this->formatters);
        foreach ($this->formatters as $name => $data) {
            $this->_formatters[$name] = Creator::createObject($data);
        }

        $this->handlers = array_replace_recursive($this->defaultHandler, $this->handlers);
        foreach ($this->handlers as $name => $data) {
            $this->constructHandler($name, $data);
        }

        $this->loggers = array_merge($this->defaultLogger, $this->loggers);
        foreach ($this->loggers as $name => $data) {
            $this->constructLogger($name, $data);
        }
    }

    protected function constructHandler($name, $config)
    {
        $formatter = null;

        if (isset($config['formatter'])) {
            $formatter = $config['formatter'];
            unset($config['formatter']);
        }

        $this->_handlers[$name] = Creator::createObject($config);

        if ($formatter) {
            if (!isset($this->_formatters[$formatter])) {
                throw new Exception("Formatter $formatter not initialized");
            }

            $fmt = $this->_formatters[$formatter];
            $this->_handlers[$name]->setFormatter($fmt instanceof FormatterInterface ? $fmt : $fmt->formatter);
        }

        return $this->_handlers[$name];
    }

    protected function constructLogger($name, $config)
    {
        $handlers = null;

        if (isset($config['handlers'])) {
            $handlers = $config['handlers'];
            unset($config['handlers']);
        }
        $this->_loggers[$name] = Creator::createObject($config, $name);

        foreach ($handlers as $hname) {
            if (!isset($this->_handlers[$hname])) {
                throw new Exception("Handler $hname not initialized");
            }

            $this->_loggers[$name]->pushHandler($this->_handlers[$hname]->handler);
        }

        return $this->_loggers[$name];
    }

    /**
     * @param $loggerName
     * @return \Xcart\App\Logger\Logger\null
     */
    protected function getLogger($loggerName)
    {
        $log = null;
        foreach ($this->_loggers as $name => $logger) {
            if ($name == $loggerName) {
                $log = $logger;
                break;
            }

            if (strpos($loggerName, $name) === 0) {
                $log = $logger;
                break;
            }
        }

        if ($log === null) {
            $log = $this->getGeneratedLogger($loggerName);
        }

        return $log;
    }

    /**
     * @return \Monolog\Logger
     */
    protected function getDefaultLogger()
    {
        return $this->_loggers['default'];
    }

    /**
     * For fallback to old logging system
     *
     * @param $name
     * @return \Monolog\Logger
     * @throws Exception
     */
    protected function getGeneratedLogger($name)
    {
        if ($this->defaultPatch) {

            $config_handler = $this->defaultHandler['default'];
            $config_handler['alias'] = $this->defaultPatch . '.' . $name;

            $handler = $this->constructHandler('gen' . $name, $config_handler);

            $config_logger = $this->defaultLogger['default'];
            $config_logger['handlers'] = ['gen' . $name];

            $logger = $this->constructLogger($name, $config_logger);

            return $logger;
        }

        return $this->getDefaultLogger();
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function error($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addError($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function warning($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addWarning($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function notice($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addNotice($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function critical($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addCritical($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function debug($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addDebug($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function alert($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addAlert($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function emergency($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addEmergency($message, $context);
    }

    /**
     * @param $message
     * @param array $context
     * @param string $logger
     * @return bool
     */
    public function info($message, array $context = [], $logger = 'default')
    {
        return $this->getLogger($logger)->addInfo($message, $context);
    }

    private $_profile = [];


    /**
     * @param $message
     * @param $method
     * @param string $logger
     * @return bool
     */
    public function beginProfile($token, $method, $logger = 'default')
    {
        $this->_profile[$token] = [
            'time' => microtime(true),
            'method' => $method,
            'logger' => $logger
        ];
    }

    /**
     * @param $message
     * @param $method
     * @param string $logger
     * @return bool
     */
    public function endProfile($token, $method, $logger = 'default')
    {
        $profile = $this->_profile[$token];
        $timeDiff = microtime(true) - $profile['time'];
        return $this->getLogger($logger)->addProfile(strtr("{time} {method} {token}", [
            '{token}' => $token,
            '{time}' => sprintf("%f", $timeDiff) . ' (' . sprintf("%.4f", $timeDiff) . ' seconds)',
            '{method}' => $profile['method']
        ]), ['method' => $method], $logger);
    }
}
