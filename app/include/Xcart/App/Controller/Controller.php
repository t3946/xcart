<?php
namespace Xcart\App\Controller;

use ReflectionMethod;
use Xcart\App\Exceptions\HttpException;
use Xcart\App\Exceptions\InvalidConfigException;
use Xcart\App\Helpers\ClassNames;
use Xcart\App\Main\Xcart;
use Xcart\App\Request\HttpRequest;
use Xcart\App\Request\RequestManager;

/**
 * Class Controller
 * @package Xcart\App\Controller
 *
 * @method beforeAction($action, $params)
 * @method afterAction($action, $params, $out)
 */
class Controller
{
    use ClassNames;
    /**
     * @var HttpRequest
     */
    protected $_request;
    /**
     * @var string|null Default action
     */
    public $defaultAction;

    public function __construct($request)
    {
        $this->_request = $request;

        $this->init();
    }

    public function init() { }

    /**
     * For global caching keys
     */
    public function getAdvancedCacheData() {
        return [];
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public function run($action = null, $params = [])
    {
        if (!$action) {
            $action = $this->defaultAction;
        }

        if (method_exists($this, 'beforeAction')) {
            $this->beforeAction($action, $params);
        }

        if (($eaa = method_exists($this, 'afterAction')) || $this->checkMiddleware())
        {
            ob_start();
            if (method_exists($this, $action)) {
                $this->runAction($action, $params);
            } else {
                ob_end_clean();
                $class = get_class();
                throw new InvalidConfigException("There is no action {$action} in controller {$class}");
            }

            $echo = ob_get_clean();

            if ($eaa && $aresult = $this->afterAction($action, $params, $echo)) {
                echo $aresult;
            }

            echo $echo;
        }
        else {
            $this->runAction($action, $params);
        }
    }

    public function runAction($action, $params = [])
    {
        $method = new ReflectionMethod($this, $action);
        $ps = [];

        if ($method->getNumberOfParameters() > 0)
        {
            foreach ($method->getParameters() as $param) {
                $name = $param->getName();

                if (isset($params[$name])) {
                    if ($param->isArray()) {
                        $ps[] = is_array($params[$name]) ? $params[$name] : [$params[$name]];
                    }
                    elseif (!is_array($params[$name])) {
                        $ps[] = $params[$name];
                    }
                    else {
                        return false;
                    }
                }
                elseif ($param->isDefaultValueAvailable()) {
                    $ps[] = $param->getDefaultValue();
                }
                else {
                    $class = get_class();
                    throw new InvalidConfigException("Param {$name} for action {$action} in controller {$class} must be defined. Please, check your routes.");
                }
            }
        }

        $ps = ($ps ?: $params);

        $method->invokeArgs($this, $ps);
    }

    /**
     * @param string $template Path to template
     * @param array $params
     * @return string
     */
    public function render($template, $params = [])
    {
        $data = ['controller' => $this];
        return Xcart::app()->template->render($template, array_replace($data, $params));
    }

    public function redirect($url, $data = [], $status = 302, $query = [])
    {
        $this->_request->redirect($url, $data, $status, $query);
    }

    public function refresh()
    {
        $this->_request->refresh();
    }

    public function error($code = 404, $message = null)
    {
        throw new HttpException($code, $message);
    }

    public function jsonResponse($data = [])
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    private function checkMiddleware()
    {
        $app = Xcart::app();

        return $app->hasComponent('middleware') && ($app->middleware->isProcessView() || $app->middleware->isProcessResponse());
    }
}