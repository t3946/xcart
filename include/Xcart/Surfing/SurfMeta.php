<?php

namespace Surfing;

use Xcart\Data;

class SurfMeta extends Data
{
    public function __construct($aParams = [])
    {
        $this->aPrimaryKeys = ['id'];
        $this->sPrimaryTable = 'cidev_surf_meta';
        parent::__construct($aParams);
    }
}