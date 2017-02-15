<?php

namespace Xcart\Images;

use Xcart\Data;

class Splash extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['brandid'];
        $this->sPrimaryTable = 'images_splash';
        parent::__construct($aParams);
    }
}