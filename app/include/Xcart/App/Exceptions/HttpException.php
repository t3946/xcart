<?php

namespace Xcart\App\Exceptions;

use Exception;
use Xcart\App\Helpers\Http;

class HttpException extends Exception
{
    /**
     * @var int status code (404, 403 ...)
     */
    public $status;

    public function __construct($status, $message = null, $code = 0, Exception $previous = null)
    {
        $this->status = $status;
        if (!$message) {
            $message = Http::getMessage($status);
        }

        parent::__construct($message, $code, $previous);
    }
}