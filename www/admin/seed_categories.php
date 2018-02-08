<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2011 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2011           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: seed_categories.php,v 1.0 2011/11/22 18:26:23 kate Exp $
#

require './auth.php';
require $xcart_dir . '/include/security.php';

$location[] = array(func_get_langvar_by_name('lbl_seed_categories_management'), 'seed_categories.php');

function func_check_scat_fields($catid, $keyphrase) {
    global $sql_tbl, $current_storefront;

    $cat_exists = 0;
    $result['reason'] = '';

    if (!empty($catid) || is_numeric($catid) && $catid == 0) {
        
        $cat_exists = func_query_first('SELECT categoryid, storefrontid  FROM ' . $sql_tbl['categories']
            . ' WHERE categoryid = "' . intval($catid) . '"');
        
        if (empty($cat_exists)) {
            $result['reason'] = 'unknown_cat';
        } elseif ($cat_exists['storefrontid'] != $current_storefront) {
            $result['reason'] = 'wrong_storefront';
        }
    }

    if (empty($catid) && $catid != 0 && empty($keyphrase)) {
        $result['reason'] = 'empty_fields';
    }
    
    $result['passed'] = (!empty($catid) && !empty($cat_exists) && $cat_exists['storefrontid'] == $current_storefront)
        || (!empty($keyphrase) && empty($catid) && $catid !== 0);

    return $result;
}

if ($REQUEST_METHOD == 'POST') {
    
    if ($mode == 'update') {
        
        $error = false;
        
        if (!empty($update) && is_array($update)) {

            $err_catids = array();

            foreach ($update as $scatid => $scat) {

                $check_fields_result = func_check_scat_fields($scat['catid'], $scat['keyphrase']);

                if (
                    !$check_fields_result['passed'] 
                    && ($check_fields_result['reason'] == 'unknown_cat' 
                    || $check_fields_result['reason'] == 'wrong_storefront')
                ) {
                    $err_catids[] = $scat['catid'];
                }
                
                if (!empty($scat['title']) && $check_fields_result['passed']) {
                    $scat['orderby'] = intval($scat['orderby']);
                    $scat['is_bold'] = (!empty($scat['is_bold'])) ? 'Y' : 'N';
                    if (empty($scat['catid'])) {
                        $scat['catid'] = null;
                    }

                    $cols = array();
                    foreach ($scat as $k => $v) {
                        if ($v != null) {
                            $cols[] = '`' . $k . '`' . '=' . '"' . $v . '"';
                        } else {
                            $cols[] = '`' . $k . '`' . '= NULL';
                        }
                    }

                    db_query('UPDATE ' . $sql_tbl['seed_categories'] 
                        . ' SET ' . implode(',', $cols) . ' WHERE scatid = "' . $scatid . '"');

                } else {
                    $error = true;
                }
            }
        } else {
            $error = true;
        }

        if ($error) {
            $top_message = array(
                'content'   => func_get_langvar_by_name('txt_seed_cats_upd_err'),
                'type'      => 'I',
            );

            if ($check_fields_result['reason'] == 'unknown_cat') {
                $msg = 'txt_following_cats_not_exist';
            } else {
                $msg = 'txt_following_cats_wrong_sf';
            }

            if (is_array($err_catids)) {
                $err_catids = array_unique($err_catids);
                if (!empty($err_catids)) {
                    $top_message['content'] .= '. ' . func_get_langvar_by_name($msg, array(
                        'CATIDS'    => implode(', ', $err_catids)
                    ));
                }
            }

        } else {
            $top_message = array(
                'content'   => func_get_langvar_by_name('txt_seed_cats_upd_succ'),
                'type'      => 'I',
            );
        }
    }

    if ($mode == 'delete') {
        if (!empty($delete)) {
            if (is_array($delete)) {
                $scatids = array_keys($delete);
                db_query('DELETE FROM ' . $sql_tbl['seed_categories'] 
                    . ' WHERE scatid IN ("' . implode('", "', $scatids) . '")');
            }
            
            $top_message = array(
                'content'   => func_get_langvar_by_name('txt_seed_cats_del_succ'),
                'type'      => 'I',
            );
        } else {
            $top_message = array(
                'content'   => func_get_langvar_by_name('lbl_no_items_have_been_selected'),
                'type'      => 'E',
            );
        }
    }

    if ($mode == 'add') {
        if (!empty($new_scat) && is_array($new_scat)) {
            
            $check_fields_result = func_check_scat_fields($new_scat['catid'], $new_scat['keyphrase']);

            if ($check_fields_result['passed'] && !empty($new_scat['title'])) {
                $new_scat['orderby'] = intval($new_scat['orderby']);
                $new_scat['is_bold'] = (!empty($new_scat['is_bold'])) ? 'Y' : 'N';
                if (empty($new_scat['catid'])) {
                    unset($new_scat['catid']);
                }
                $new_scat['sfid'] = $current_storefront;
                
                func_array2insert('seed_categories', $new_scat);
                
                $top_message = array(
                    'content'   => func_get_langvar_by_name('txt_seed_cats_add_succ'),
                    'type'      => 'I',
                );
            } else {
                if ($check_fields_result['reason'] == 'unknown_cat') {
                    $top_message = array(
                        'content'   => func_get_langvar_by_name('txt_seed_cats_add_unknown_category', array(
                            'CATID' => $new_scat['catid']
                        )),
                        'type'      => 'E',
                    );
                } elseif ($check_fields_result['reason'] == 'empty_fields') {
                    $top_message = array(
                        'content'   => func_get_langvar_by_name('txt_seed_cats_add_empty_fields'),
                        'type'      => 'E',
                    );
                } elseif ($check_fields_result['reason'] == 'wrong_storefront') {
                    $top_message = array(
                        'content'   => func_get_langvar_by_name('txt_seed_cats_add_wrong_sf', array(
                            'CATID' => $new_scat['catid']
                        )),
                        'type'      => 'E',
                    );
                } elseif (empty($new_scat['title'])) {
                    $top_message = array(
                        'content'   => func_get_langvar_by_name('txt_seed_cats_add_empty_title'),
                        'type'      => 'E',
                    );
                }
            }
        }
    }

    func_header_location('seed_categories.php');
}

if (!empty($active_modules['Multiple_Storefronts'])) {
    $where = ' WHERE sfid = "' . $current_storefront . '"';
}

$fields = array('scatid', 'catid', 'title', 'keyphrase', 'is_bold', 'orderby', 'avail', 'sfid');

$seed_categories = func_query_hash('SELECT ' . implode(', ', $fields) . ' FROM ' . $sql_tbl['seed_categories']
    . $where . ' ORDER BY orderby, title', 'scatid', false, false);
if (!empty($seed_categories)) {
    $smarty->assign('seed_categories', $seed_categories);
}

$smarty->assign('main', 'seed_categories');

# Assign the current location line
$smarty->assign('location', $location);

@include $xcart_dir . '/modules/gold_display.php';
func_display('admin/home.tpl', $smarty);
?>
