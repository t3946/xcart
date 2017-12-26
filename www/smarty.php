<?php


if ( !defined('XCART_START') ) { header("Location: index.php"); die("Access denied"); }

umask(0);

#
# Define SMARTY_DIR to avoid problems with PHP 4.2.3 & SunOS
#
$ds = DIRECTORY_SEPARATOR;

define('SMARTY_DIR', "{$xcart_dir}{$ds}..{$ds}app{$ds}include{$ds}smarty{$ds}");

ini_set('include_path',
	$xcart_dir . "/../app/include/templater"
	. PATH_SEPARATOR . SMARTY_DIR
	. PATH_SEPARATOR . ini_get('include_path'));

#
# Smarty object for processing html templates
#
$smarty = Templater::getInstance();

#
# Store all compiled templates to the single directory
#
$smarty->use_sub_dirs = false;
$smarty->request_use_auto_globals = false;

$smarty->template_dir = $xcart_dir."/skin1_kolin";
$smarty->compile_dir = $var_dirs["templates_c"];
$smarty->config_dir = $xcart_dir."/skin1_kolin";
$smarty->cache_dir = $var_dirs["cache"];
$smarty->secure_dir = $xcart_dir."/skin1_kolin";
$smarty->debug_tpl = "file:debug_templates.tpl";

if (defined('LOCAL_SF_ID')) {
    $cidev_tmp_storefrontid = LOCAL_SF_ID;
}

if (AREA_TYPE == 'C') {
    $CDN_domain = func_query_first_cell("SELECT value FROM xcart_storefronts_config WHERE name='CDN_domain' AND storefrontid='$cidev_tmp_storefrontid'");
    $Enable_CDN = func_query_first_cell("SELECT value FROM xcart_storefronts_config WHERE name='Enable_CDN' AND storefrontid='$cidev_tmp_storefrontid'");

    if (!empty($CDN_domain)) {
        $CDN_domain = '//' . $CDN_domain;
    }
}

if ($HTTPS){
	$smarty->assign("HTTPS_used", "Y");
}

$smarty_skin_dir = "/skin1_kolin";
$smarty->assign("smarty_skin_dir", $smarty_skin_dir);

$urlPath = (!empty($CDN_domain) && $Enable_CDN == "Y") ? $CDN_domain : $xcart_web_dir;

$smarty->assign("ImagesDir", $urlPath . "{$smarty_skin_dir}/images");
$smarty->assign("SkinDir", $urlPath . $smarty_skin_dir);

$smarty->assign("template_dir", $smarty->template_dir);

#
# Smarty object for processing mail templates
#
$mail_smarty = $smarty;

if (defined('SMARTY_AUTO_RECOMPILE') && SMARTY_AUTO_RECOMPILE)
{
    $smarty->compile_check = true;
}