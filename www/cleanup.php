<?php
define("CIDEV_CRON_START", "CRON");
require __DIR__ . DIRECTORY_SEPARATOR . "./top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "./init.php";

if (empty($_GET['only_cdn'])) {

    $smarty->clear_all_cache();
    $smarty->clear_compiled_tpl();

    \Xcart\App\Main\Xcart::app()->cache->cleanUp(true, ['html']);
    \Xcart\App\Main\Xcart::app()->template->getRenderer()->clearAllCompiles();
}

if (!empty($_GET['cdn']) || !empty($_GET['only_cdn'])) {
    \Modules\Amazon\Helpers\AmazonAWSHelper::invalidateCDN();
}
?>
The compiled templates cache ("templates_c" directory) has been cleaned up.
