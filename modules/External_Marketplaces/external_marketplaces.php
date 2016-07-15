<?php
if ( !defined('XCART_SESSION_START') ) { header("Location: ../"); die("Access denied"); }

if ($REQUEST_METHOD == 'POST'){

    if ($mode == "update"){

        if (!empty($pbx) && is_array($pbx)){
            foreach ($pbx as $id => $v){
                if ($v["delete"] == "Y"){
                    db_query("DELETE FROM  $sql_tbl[pbx_options] WHERE id='$id'");
                } else {
                    db_query("UPDATE $sql_tbl[pbx_options] SET extension='$v[extension]', anveo_account='$v[anveo_account]', anveo_password='$v[anveo_password]' WHERE id='$id'");
                }
            }
        }

        db_query("UPDATE $sql_tbl[config] SET value='$SIP_phone_settings_template' WHERE name='SIP_phone_settings_template'");
    }
    elseif ($mode == "add"){
        db_query("INSERT INTO $sql_tbl[pbx_options] (extension) VALUES ('')");
    }

    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";
    func_header_location("configuration.php?option=PBX_options");
}

$aExternalMarketplaces = func_query("SELECT * FROM $sql_tbl[products_external_marketplaces] ORDER BY marketplace_name");


$smarty->assign("pbx_options", $pbx_options);