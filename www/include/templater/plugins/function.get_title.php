<?php
/* vim: set ts=4 sw=4 sts=4 et: */
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazlyev <rrf@x-cart.com>                  |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLYEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
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
| The Initial Developer of the Original Code is Ruslan R. Fazlyev             |
| Portions created by Ruslan R. Fazlyev are Copyright (C) 2001-2010           |
| Ruslan R. Fazlyev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

/**
 * Templater plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     get_title
 * Input:
 *           page_type
 *           page_id
 * -------------------------------------------------------------
 *
 * @category   X-Cart
 * @package    X-Cart
 * @subpackage Lib
 * @author     Ruslan R. Fazlyev <rrf@x-cart.com>
 * @copyright  Copyright (c) 2001-2010 Ruslan R. Fazlyev <rrf@x-cart.com>
 * @license    http://www.x-cart.com/license.php X-Cart license agreement
 * @version    $Id: function.get_title.php,v 1.19 2010/08/04 15:12:58 igoryan Exp $
 * @link       http://www.x-cart.com/
 * @see        ____file_see____
 */

if (!defined('XCART_START')) { header("Location: ../../../"); die("Access denied"); }

function smarty_function_get_title($params, &$smarty)
{
    global $active_modules, $sql_tbl, $config, $current_area;

    if (!isset($params['page_type']))
        $params['page_type'] = '';

    if (!isset($params['page_id']))
        $params['page_id'] = 0;

    // Get by page type & page id
    $title = false;
    switch ($params['page_type']) {
        case 'P':
            // Product page
            x_load('product');
            $title = func_get_product_title(intval($params['page_id']));
            break;

        case 'C':
            // Category page
            x_load('category');
            $title = func_get_category_title(intval($params['page_id']));
            break;

        case 'M':
            // Manufacturer page
            if (empty($active_modules['Manufacturers']))
                break;

            $title = func_query_first_cell("SELECT title_tag FROM $sql_tbl[manufacturers] WHERE manufacturerid = '".intval($params['page_id'])."'");
            break;

        case 'E':
            // Static page (embedded)
            $title = func_query_first_cell("SELECT title_tag FROM $sql_tbl[pages] WHERE pageid = '".intval($params['page_id'])."'");
            break;
    }

    if (is_string($title)) {
        $title = str_replace(array("\n", "\r"), array('', ''), trim($title));
    }

    // Get default
    if (!is_string($title) || empty($title)) {
        $title = str_replace(array("\n", "\r"), array('', ''), trim($config['SEO']['site_title']));
    }

    if (empty($title) && $current_area == 'C' && isset($smarty->_tpl_vars['location']) && is_array($smarty->_tpl_vars['location'])) {

        // Title based on bread crumbs
        $location = $smarty->_tpl_vars['location'];
        $tmp = array();
        if ($config['SEO']['page_title_format'] != 'A')
            $location = array_reverse($location);

        foreach ($location as $v) {
            $tmp[] = $v[0];
        }

        $title = str_replace(array("\n", "\r"), array('', ''), trim(implode(' :: ', $tmp)));
    }

    // truncate
    $title = str_replace("&nbsp;", " ", $title);
    if (strlen($title) > $config['SEO']['page_title_limit'] && $config['SEO']['page_title_limit'] > 0) {
        $title = func_truncate($title, $config['SEO']['page_title_limit']);
    }

    // escape
    if (X_USE_NEW_HTMLSPECIALCHARS) {
        $charset = $smarty->_tpl_vars['default_charset'] ? $smarty->_tpl_vars['default_charset'] : 'ISO-8859-1';
        $title = @htmlspecialchars($title, ENT_QUOTES, $charset);

    } else {
        $title = htmlspecialchars($title, ENT_QUOTES);
    }

    // correct the page title with enabled webmaster mode
    if ($smarty->webmaster_mode && !empty($title)) {
        $title = strip_tags(str_replace( array("&lt;", "&gt;"), array("<", ">"), $title ));
    }

    return '<title>' . $title . '</title>';
}
?>
