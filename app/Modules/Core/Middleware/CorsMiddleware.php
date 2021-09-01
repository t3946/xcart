<?php

namespace Modules\Core\Middleware;

use Xcart\App\Cli\Cli;
use Xcart\App\Middleware\Middleware;

class CorsMiddleware extends Middleware
{
    /**
     * @var string
     */
    public $origin = '*';
    /**
     * @var array
     */
    public $methods = ['OPTIONS', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    /**
     * @var array
     */
    public $headers = ['X-Auth-Token', 'X-Requested-With', 'X-CSRFToken', 'Content-Type'];

    public function processRequest($request)
    {
        if (Cli::isCli() == false) {
            $origin = $_SERVER['HTTP_ORIGIN'] ?? $this->origin;
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Methods: ' . (is_string($this->methods) ? $this->methods : implode(', ', $this->methods)));
            header('Access-Control-Allow-Headers: ' . (is_string($this->headers) ? $this->headers : implode(', ', $this->headers)));

            header('X-XSS-Protection: 1; mode=block');
            header('X-Frame-Options: SAMEORIGIN');

            // respond to preflights
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                exit;
            }
        }
    }
}
