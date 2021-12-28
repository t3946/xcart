<?php

namespace Xcart\Images;

use Xcart\Data;

/**
 * @deprecated deprecated class
 */
class Splash extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'images_splash';
        parent::__construct($aParams);
    }
}