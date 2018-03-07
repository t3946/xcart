<?php

require "./auth.php";

\Xcart\App\Main\Xcart::app()->request->cookie->add('no_cache', 1, \Modules\Core\Helpers\Cache::CACHE_HOUR);