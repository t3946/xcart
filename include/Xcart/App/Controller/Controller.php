<?php
namespace Xcart\App\Controller;

use Xcart\App\Traits\RenderTrait;

class Controller
{
    use RenderTrait;

    public function __construct()
    {
        $this->init();
    }

    public function init() { }
}