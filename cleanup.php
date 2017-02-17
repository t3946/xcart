<?php
require "./top.inc.php";
require "./init.php";

$smarty->clear_all_cache();
$smarty->clear_compiled_tpl();

\Xcart\App\Main\Xcart::app()->cache->cleanUp(true);
\Xcart\App\Main\Xcart::app()->template->getRenderer()->clearAllCompiles();
?>
The compiled templates cache ("templates_c" directory) has been cleaned up.
