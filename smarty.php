<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2006 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2006           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: smarty.php,v 1.39 2006/04/10 12:33:52 max Exp $
#

if ( !defined('XCART_START') ) { header("Location: index.php"); die("Access denied"); }

umask(0);

#
# Define SMARTY_DIR to avoid problems with PHP 4.2.3 & SunOS
#
define('SMARTY_DIR', $xcart_dir.DIRECTORY_SEPARATOR."Smarty-2.6.12".DIRECTORY_SEPARATOR);

ini_set('include_path',
	$xcart_dir . "/include/templater"
	. PATH_SEPARATOR . SMARTY_DIR
	. PATH_SEPARATOR . ini_get('include_path'));

include_once($xcart_dir."/include/templater/templater.php");

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
	if ($cidev_tmp_storefrontid == 0){
		$CDN_domain = func_query_first_cell("SELECT value FROM xcart_config WHERE name='CDN_domain'");
		$Enable_CDN = func_query_first_cell("SELECT value FROM xcart_config WHERE name='Enable_CDN'");
	} else {
        $CDN_domain = func_query_first_cell("SELECT value FROM xcart_storefronts_config WHERE name='CDN_domain' AND storefrontid='$cidev_tmp_storefrontid'");
	    $Enable_CDN = func_query_first_cell("SELECT value FROM xcart_storefronts_config WHERE name='Enable_CDN' AND storefrontid='$cidev_tmp_storefrontid'");
	}
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