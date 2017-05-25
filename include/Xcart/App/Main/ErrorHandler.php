<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 04/08/16 11:47
 */

namespace Xcart\App\Main;

use ErrorException;
use Exception;
use Xcart\App\Exceptions\HttpException;
use Xcart\App\Helpers\Http;
use Xcart\App\Helpers\Paths;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Helpers\Text;
use Xcart\App\Template\Renderer;

class ErrorHandler
{
    use SmartProperties, Renderer;

    public $errorTemplate = 'error.tpl';
    public $exceptionTemplate = 'exception.tpl';

    public $debug = false;
    public $errHandler = true;
    public $excHandler = true;

    public function init()
    {
        $this->setHandlers();
    }

    public function setHandlers()
    {
//        ini_set('display_errors', false);

        if ($this->errHandler) {
            set_error_handler([$this, 'handleError']);
        }
        if ($this->excHandler) {
            set_exception_handler([$this, 'handleException']);
        }
    }

    public function unsetHandlers()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function handleError($code, $message, $file, $line)
    {
        throw new ErrorException($message, $code, $code, $file, $line);
    }

    public function handleException($exception)
    {
        $this->unsetHandlers();

        $code = 500;
        if ($exception instanceof HttpException) {
            $code = $exception->status;
        }

        if (!headers_sent()) {
            header("HTTP/1.0 {$code} " . Http::getMessage($code));
        }

        try {
            $this->renderException($exception, $code);
        } catch (Exception $e) {
            if ($this->debug) {
                debug_print_backtrace();
            } else {
                echo 'Internal server error';
            }
        }
    }

    public function renderException($exception, $code)
    {
        $template = $this->errorTemplate;
        if ($this->debug) {
            $template = $this->exceptionTemplate;
        }

        $trace = [];
        $traceRaw = $exception->getTrace();
        $closestLines = 5;
        $basePath = realpath(Paths::get('base'));

        foreach ($traceRaw as $traceItem) {
            if (!empty($traceItem['line'])){
                $line = $traceItem['line'];
                $startLine = $line - $closestLines;
                $endLine = $line + $closestLines;

                if ($startLine < 0) {
                    $endLine += abs($startLine);
                    $startLine = 0;
                }

                $fileName = $traceItem['file'];

                $lines = @file($fileName);
                $itemLines = array_slice(is_array($lines) ? $lines : [], $startLine, $endLine - $startLine, true);

                $fileName = Text::removePrefix($basePath, $fileName);

                $trace[] = [
                    'fileName' => $fileName,
                    'trace' => $traceItem,
                    'startLine' => $startLine,
                    'endLine' => $endLine,
                    'itemLines' => $itemLines
                ];
            }
            else {
                $trace[] = [
                    'trace' => $traceItem,
                ];
            }
        }

        echo self::renderTemplate($template, [
            'exception' => $exception,
            'code' => $code,
            'trace' => $trace
        ]);
    }
}