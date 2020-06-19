<?php

if (!defined('XCART_START')) {
    header('Location: ../');
    die('Access denied');
}

use Mindy\QueryBuilder\Q\QOr;
use Modules\Distributor\Models\DistributorCarrierModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\DistributorSiteModel;
use Modules\Shipping\Models\TrackingLinksCarrierModel;
use Modules\Sites\Models\CurrencyModel;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\External_Marketplaces\ExternalMarketPlace;
use Xcart\External_Marketplaces\DisabledMarketPlace;

require $xcart_dir . '/include/countries.php';
require $xcart_dir . '/include/states.php';
if ($config["General"]["use_counties"] === "Y")
    include $xcart_dir . '/include/counties.php';

$location[] = array(func_get_langvar_by_name("lbl_manufacturers"), "");

#
# NOTES.
# 1. Only administrator can activate manufacturer and set up its position in
# the manufacturers list.
# 2. Provider can view the entire list of manufacturers but edit or delete only
# manufacturers created by the same provider.
# 3. If some manufacturer have assigned products of at least one provider that
# is not owner of this manufacturer, owner will not be able to delete that
# manufacturer.
#
$provider_condition = ($single_mode || $current_area === 'A' ? '' : "AND provider='{$login}'");

$manufacturerid = (int)$manufacturerid;

if ($manufacturerid) {
    $distributorModel = DistributorModel::objects()->get(['manufacturerid' => $manufacturerid]);
    $smarty->assign("distributorModel", $distributorModel);
    if (($distributor_section == 8 || $REQUEST_METHOD === 'POST') && $role && $role->membership === 'Vendor Relations Specialist' && !$distributorModel->isUserPriveded($login)) {
        \Xcart\App\Main\Xcart::app()->request->redirect('error_message.php?access_denied&id=25');
    }
}

x_session_register('manufacturer_data_form');

$distributor_section = (int)$distributor_section;

if (($distributor_section === 19 || $distributor_section === 21) && !empty($manufacturerid)) {
    include $xcart_dir . '/provider/shipping_rates_new.php';
} elseif ($distributor_section === 22) {
    include $xcart_dir . '/admin/product_page_locked_fields.php';
}

/*
 Get the number of products that assigned to the manufacturer
 with different $provider (this need for checking permissions)
#*/
function func_manufacturer_is_used($manufacturerid, $provider)
{
    global $sql_tbl;
    return func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[products] WHERE manufacturerid='$manufacturerid' AND provider!='$provider'");
}

if ($REQUEST_METHOD === 'POST' && $mode === 'add_new_line' && $manufacturerid) {

    $max_distributor_field_code = func_query_first_cell("SELECT MAX(distributor_field_code) FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid'");

    $max_distributor_field_code++;

    db_query("INSERT INTO $sql_tbl[distributor_contacts] (distributor_field_code, manufacturerid) VALUES ('$max_distributor_field_code', '$manufacturerid')");

    $top_message["content"] = 'Added';
    $top_message['type'] = 'I';
    func_header_location("manufacturers.php?manufacturerid=" . $manufacturerid . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));
}

if ($REQUEST_METHOD === 'POST' && $mode === 'excluded_marketplace' && $manufacturerid) {
    global $xcart_dir;
    DisabledMarketPlace::deleteAllDisabledMarketPlace($manufacturerid, 'D');
    if (!empty($excluded_marketplaces))
        foreach ($excluded_marketplaces as $iExcludedMarketplace) {
            $oMarketPlace = new Xcart\External_Marketplaces\DisabledMarketPlace();
            $oMarketPlace->fill(['marketplace_id' => $iExcludedMarketplace, 'resource_id' => $manufacturerid, 'resource_type' => 'D']);
            $oMarketPlace->addDisabledMarketPlace();
        }
}

if ($REQUEST_METHOD === 'POST' && $mode === 'add_distributor_return_address' && $manufacturerid) {
    db_query("INSERT INTO $sql_tbl[distributor_return_address] (manufacturerid) VALUES ('$manufacturerid')");
    $top_message["content"] = 'Added';
    $top_message['type'] = 'I';
    func_header_location("manufacturers.php?manufacturerid=" . $manufacturerid . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));
}

if ($REQUEST_METHOD === 'POST' && $mode === 'delete_line' && !empty($manufacturerid) && !empty($delete_line_number)) {
    db_query("DELETE FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid' AND distributor_field_code='$delete_line_number'");
    $top_message["content"] = 'Deleted';
    $top_message['type'] = 'I';
    func_header_location("manufacturers.php?manufacturerid=" . $manufacturerid . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));
}

if ($REQUEST_METHOD === 'POST' && $mode === 'delete_distributor_return_address' && !empty($manufacturerid) && !empty($delete_distributor_return_address_number)) {
    db_query("DELETE FROM $sql_tbl[distributor_return_address] WHERE manufacturerid='$manufacturerid' AND id='$delete_distributor_return_address_number'");
    $top_message["content"] = 'Deleted';
    $top_message['type'] = 'I';
    func_header_location("manufacturers.php?manufacturerid=" . $manufacturerid . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));
}

if ($REQUEST_METHOD === 'POST' && $mode === 'copy_distributor' && $manufacturerid) {
    $bErrorClone = false;

    $classManufacturer = new Xcart\Manufacturers();
    $classCategories = new Xcart\Categories();

    $storefont_info = func_get_storefront_info($storefront_to_copy_manufacturer, "ID");

    if (!empty($root_categoryid_for_cloned_products) && is_numeric($root_categoryid_for_cloned_products)) {
        $aCloneCategory = $classCategories->getCategoryByIdAndStoreFront($root_categoryid_for_cloned_products, $storefront_to_copy_manufacturer);
        if (empty($aCloneCategory)) {
            $top_message["type"] = "E";
            $top_message["content"] = func_get_langvar_by_name("lb_root_categoryid_for_cloned_products_not_exists");
            $bErrorClone = true;
        }
    }

    if (!$bErrorClone) {
        $aCloneParams = [
            'manufacturerid' => $manufacturerid,
            'd_main_sf' => $storefront_to_copy_manufacturer,
            'update_approximation_shipping_rates' => 'Y',
            'd_search_keyphrase_for_reconciliation' => '',
            'root_categoryid_for_cloned_products' => $root_categoryid_for_cloned_products,
            'parent_manufacturer_id' => $manufacturerid,
            'provider' => $login,
            'sf_prefix' => rtrim($storefont_info['prefix'], '-'),
        ];

        $aOriginalManufacturer = $classManufacturer->getMainufacturersInfo(array($manufacturerid));
        $aOriginalManufacturer = reset($aOriginalManufacturer);

        $res = $classManufacturer->cloneManufacturer($aOriginalManufacturer, $aCloneParams);
        if (!$res) {
            $sErrorMessage = '';
            foreach ($classManufacturer->message as $eMessage) {
                $sErrorMessage .= func_get_langvar_by_name($eMessage);
            }
            $top_message['type'] = 'E';
            $top_message['content'] = $sErrorMessage;
            $bErrorClone = true;

        } else {
            $top_message['type'] = "I";
            $top_message['content'] = func_get_langvar_by_name('lb_copy_manufacturer_done');
        }
    }
    unset($classCategories);
    unset($classManufacturer);
}

if ($REQUEST_METHOD === 'POST' && $mode === 'copy_products' && $manufacturerid) {
    $classManufacturer = new Xcart\Manufacturers();
    if (!empty($product_to_copy_manufacturer)) {
        $countAddedProducts = $classManufacturer->addProductsToQueue($manufacturerid, $product_to_copy_manufacturer);
        $top_message['type'] = 'I';
        $top_message['content'] = "{$countAddedProducts} products added to clone queue... Processing takes some time ...";
    } else {
        $top_message["type"] = 'E';
        $top_message["content"] = 'Target distributor not selected';
    }
    unset($classManufacturer);
}

if ($REQUEST_METHOD === 'POST' && $mode === 'update_root_category' && $manufacturerid) {

    $aUpdateParam = array(
        "root_categoryid_for_cloned_products" => $root_categoryid_for_cloned_products
    );
    if (func_array2update("manufacturers", $aUpdateParam, "manufacturerid=" . $manufacturerid))
        $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_upd");
}

if ($REQUEST_METHOD === 'POST' || ($mode === 'delete_image' && $manufacturerid)) {  //TODO check this shit


    if ($mode === "details" && ($image_perms = func_check_image_storage_perms($file_upload_data, "M")) !== true) {
        # Check permissions
        $top_message = array(
            "content" => $image_perms['content'],
            "type" => "E"
        );

    } elseif ($mode === 'details') {
        #
        # Modify manufacturer details
        #
        if ($current_area === 'P') {
            $orderby = 10;
        }

        $orderby = (int)$orderby;

        if (isset($manufacturerid) && $manufacturerid) {

            $current_manufacturer_info = $distributorModel->getAttributes();

            if (!empty($products_quantity_behavior) && $distributor_section === 20) {

                if ($display_quantity_of != "") {
                    $display_quantity_of = abs((int)$display_quantity_of);
                }

                $current_products_quantity_behavior = $distributorModel->products_quantity_behavior;
                $current_display_quantity_of = $distributorModel->display_quantity_of;

                if ($products_quantity_behavior != $current_products_quantity_behavior && $products_quantity_behavior === 'R') {
                    // use real quantity on storefront
                    db_query("UPDATE $sql_tbl[products] SET avail = r_avail WHERE manufacturerid='$manufacturerid' AND r_avail>0");
                    db_query("UPDATE $sql_tbl[variants] v LEFT JOIN $sql_tbl[products] p ON p.productid = v.productid SET v.avail = p.r_avail WHERE p.manufacturerid='$manufacturerid' AND p.r_avail>0");
                }
                if (
                    $products_quantity_behavior === 'D' && $display_quantity_of > 0
                    &&
                    ($products_quantity_behavior != $current_products_quantity_behavior || $current_display_quantity_of != $display_quantity_of)
                ) {
                    db_query("UPDATE $sql_tbl[products] SET avail = '$display_quantity_of' WHERE manufacturerid='$manufacturerid' AND r_avail>0");
                    db_query("UPDATE $sql_tbl[variants] v LEFT JOIN $sql_tbl[products] p ON p.productid = v.productid SET v.avail = '$display_quantity_of' WHERE p.manufacturerid='$manufacturerid' AND p.r_avail>0");
                }

                db_query("UPDATE $sql_tbl[products] SET avail='0' WHERE r_avail='0' AND manufacturerid='$manufacturerid'");
            }

            $manufacturer = trim($manufacturer);

            if (empty($manufacturer)) {
                $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_empty");
                $top_message['type'] = 'E';
                func_header_location("manufacturers.php?manufacturerid=" . $manufacturerid . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));

            } elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE manufacturer = '$manufacturer' AND manufacturerid != '$manufacturerid'")) {
                $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_exist");
                $top_message['type'] = 'E';
                func_header_location("manufacturers.php?manufacturerid=" . $manufacturerid . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));
            } elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE code = '$code' AND manufacturerid != '$manufacturerid'")) {
                $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_code_exist");
                $top_message['type'] = 'E';
                func_header_location("manufacturers.php?manufacturerid=" . $manufacturerid . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));
            }

            #
            # Update the manufacturer details
            #
            if (!empty($provider_condition))
                #
                # Check the permissions to update manufacturer details
                #
                $do_not_touch = (func_manufacturer_is_used($manufacturerid, $login) > 0);
            else
                $do_not_touch = false;

            $query_data = \Xcart\App\Main\Xcart::app()->request->post->all();
            $query_data['d_availability_must_be_checked'] = $query_data['d_availability_must_be_checked'] ?? 'N';

            $query_data_lng = [
                'manufacturerid' => $manufacturerid,
                'code' => $shop_language,
                'descr' => $descr
            ];
            if (!$do_not_touch) {
                $query_data_lng['manufacturer'] = $manufacturer;
                if (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE manufacturer = '$manufacturer'") == 0)
                    $query_data['manufacturer'] = $manufacturer;
            }

            if ($shop_language != $config['default_admin_language']) {
                func_unset($query_data, "manufacturer", "descr");
            }

            if ($login_type === 'P') {
                $selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
                if (!empty($selected_manufacturers)) {
                    $selected_manufacturers = unserialize($selected_manufacturers);
                }
                $selected_manufacturers[] = $manufacturerid;
                db_query("UPDATE $sql_tbl[customers] SET manufacturerids = '" . addslashes(serialize($selected_manufacturers)) . "' WHERE  login='$login' AND usertype='$login_type'");
            }

            if ($query_data['submit_to_operator'] === 'through_distributor_website') {
                $query_data['allow_dispatch_off_working_hours'] = 'N';
            }

            func_array2insert("manufacturers_lng", $query_data_lng, true);


            $return_address_ids = func_query("SELECT id FROM $sql_tbl[distributor_return_address] WHERE manufacturerid='$manufacturerid'");

            if (!empty($return_address_ids) && is_array($return_address_ids)) {
                foreach ($return_address_ids as $k_a => $v_a) {

                    $tmp_warehouse_name = "warehouse_name_" . $v_a["id"];
                    $tmp_full_name = "full_name_" . $v_a["id"];
                    $tmp_company = "company_" . $v_a["id"];
                    $tmp_address = "address_" . $v_a["id"];
                    $tmp_address_2 = "address_2_" . $v_a["id"];
                    $tmp_city = "city_" . $v_a["id"];
                    $tmp_country = "country_" . $v_a["id"];
                    $tmp_state = "state_" . $v_a["id"];
                    $tmp_zipcode = "zipcode_" . $v_a["id"];
                    $tmp_phone = "phone_" . $v_a["id"];
                    $tmp_ext = "ext_" . $v_a["id"];

                    db_query("UPDATE $sql_tbl[distributor_return_address] SET warehouse_name='" . $$tmp_warehouse_name . "', full_name='" . $$tmp_full_name . "', company='" . $$tmp_company . "', address='" . $$tmp_address . "', address_2='" . $$tmp_address_2 . "', city='" . $$tmp_city . "', country='" . $$tmp_country . "', state='" . $$tmp_state . "', zipcode='" . $$tmp_zipcode . "', phone='" . $$tmp_phone . "', ext='" . $$tmp_ext . "' WHERE id='$v_a[id]'");
                }
            }
            if ($distributor_section == 18) {

                $current_fields_in_supplier_product_feeds = func_query_first("SELECT * FROM $sql_tbl[supplier_product_feeds] WHERE manufacturerid='$manufacturerid'");

                db_query("DELETE FROM $sql_tbl[supplier_product_feeds] WHERE manufacturerid='$manufacturerid'");
                db_query("INSERT INTO $sql_tbl[supplier_product_feeds] (manufacturerid, storefrontid, enabled_feed, ftp_host, ftp_login, ftp_password, ftp_folder, feed_procedure_id, default_productid, product_management_team_email, comments, last_import_date, last_imported_updated_products_count, is_launched, import_new_products, import_new_and_update_existing_products, updation_frequency, last_products_count_in_file, default_parent_categoryid) VALUES ('$manufacturerid', '" . trim($spf_storefrontid) . "', '" . trim($spf_enabled_feed) . "', '" . trim($spf_ftp_host) . "', '" . trim($spf_ftp_login) . "', '" . trim($spf_ftp_password) . "', '" . trim($spf_ftp_folder) . "', '" . trim($spf_feed_procedure_id) . "', '" . trim($spf_default_productid) . "', '" . trim($spf_product_management_team_email) . "', '" . trim($spf_comments) . "', '$current_fields_in_supplier_product_feeds[last_import_date]', '$current_fields_in_supplier_product_feeds[last_imported_updated_products_count]', '$current_fields_in_supplier_product_feeds[is_launched]', '$spf_import_new_products', '$spf_import_new_and_update_existing_products', '" . trim($spf_updation_frequency) . "', '$current_fields_in_supplier_product_feeds[last_products_count_in_file]', '" . trim($spf_default_parent_categoryid) . "')");
            }
###
##
#


            $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_upd");

        } else {
            #
            # Add new manufacturer
            #
            $fillerror = true;

            $manufacturer = trim($manufacturer);

            if (empty($manufacturer)) {
                $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_empty");
                $top_message['type'] = 'E';

            } elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE manufacturer = '$manufacturer'")) {
                $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_exist");
                $top_message['type'] = 'E';

            } elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[manufacturers] WHERE code = '$code'")) {
                $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_code_exist");
                $top_message['type'] = 'E';
            } else {
                $fillerror = false;
            }

            if (!$fillerror) {

                if ($orderby <= 0)
                    $orderby = func_query_first_cell("SELECT MAX(orderby) FROM $sql_tbl[manufacturers]") + 10;

                if ($login_type == 'P') {
                    $avail = 'Y';
                }

                $query_data = array(
                    "manufacturer" => $manufacturer,
                    "avail" => $avail,
                    "orderby" => $orderby,
                    "provider" => $login,
                    "descr" => $descr,
# START: random:20341 [2010 Jul 29 14:46] 
                    "code" => trim($code),
# END: random:20341 [2010 Jul 29 14:46] 
                    "url" => trim($url),
                    "d_main_sf" => trim($d_main_sf),
                );
# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 

                if (!empty($b_address) && !empty($b_city) && !empty($b_country) && !empty($b_state) && !empty($b_zipcode)) {
                    $query_data['m_address'] = $b_address;
                    $query_data['m_address_2'] = $b_address_2;
                    $query_data['m_city'] = $b_city;
                    $query_data['m_country'] = $b_country;
                    $query_data['m_state'] = $b_state;
                    $query_data['m_zipcode'] = $b_zipcode;
                }
                $query_data['email'] = $email;
                $query_data['mess_body'] = $mess_body;
                $query_data['manufact_text_displayed'] = $manufact_text_displayed;
                $query_data['cart_manufact_text_displayed'] = $cart_manufact_text_displayed;


# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
                $manufacturerid = func_array2insert("manufacturers", $query_data);

                if (!empty($operators)) {
                    $customers = func_query_hash("SELECT login, manufacturerids FROM $sql_tbl[customers] WHERE login IN ('" . implode("','", $operators) . "')", 'login', false, true);

                    foreach ($operators as $op) {
                        if (empty($customers[$op])) {
                            continue;
                        }

                        $customers[$op] = unserialize($customers[$op]);
                        $customers[$op][] = $manufacturerid;

                        db_query("UPDATE $sql_tbl[customers] SET manufacturerids='" . serialize($customers[$op]) . "' WHERE login='$op'");
                    }
                }

# START: random:1073746882_1073747063 [2008 Dec 24 16:25] 
                if ($login_type == 'P') {
                    $selected_manufacturers = func_query_first_cell("SELECT manufacturerids FROM $sql_tbl[customers] WHERE login='$login' AND usertype='$login_type'");
                    if (!empty($selected_manufacturers)) {
                        $selected_manufacturers = unserialize($selected_manufacturers);
                    }
                    $selected_manufacturers[] = $manufacturerid;
                    db_query("UPDATE $sql_tbl[customers] SET manufacturerids = '" . addslashes(serialize($selected_manufacturers)) . "' WHERE  login='$login' AND usertype='$login_type'");
                }


# END: random:1073746882_1073747063 [2008 Dec 24 16:25] 
                $query_data = array(
                    "manufacturerid" => $manufacturerid,
                    "code" => $shop_language,
                    "manufacturer" => $manufacturer,
                    "descr" => $descr
                );
                func_array2insert("manufacturers_lng", $query_data);

                $top_message["content"] = func_get_langvar_by_name("msg_adm_err_manufacturer_add");
            } else {
                $manufacturer_data_form = $_POST;
                $data_names = array(
                    'b_address' => 'm_address',
                    'b_address_2' => 'm_address_2',
                    'b_city' => 'm_city',
                    'b_country' => 'm_country',
                    'b_state' => 'm_state',
                    'b_county' => 'm_county',
                    'b_zipcode' => 'm_zipcode'
                );
                $form_names = array_keys($data_names);
                foreach ($manufacturer_data_form as $k => $v) {
                    if (in_array($k, $form_names)) {
                        unset($manufacturer_data_form[$k]);
                        $manufacturer_data_form[$data_names[$k]] = $v;
                    }
                    if (!is_array($v)) {
                        $manufacturer_data_form[$k] = stripslashes($v);
                    }
                }
                func_header_location('manufacturers.php?mode=add');
            }
            x_session_unregister('manufacturer_data_form');
        }

        if (func_check_image_posted($file_upload_data, "M") && $manufacturerid > 0) {
            func_save_image($file_upload_data, "M", $manufacturerid);
        }

    } elseif ($mode == "delete" and !empty($to_delete) && is_array($to_delete)) {
        #
        # Delete selected manufacturers
        #
        $ids = func_query_column("SELECT manufacturerid FROM $sql_tbl[manufacturers] WHERE manufacturerid IN ('" . implode("','", array_keys($to_delete)) . "') " . $provider_condition);
        if (!empty($ids)) {
            db_query("DELETE FROM $sql_tbl[manufacturers] WHERE manufacturerid IN ('" . implode("','", $ids) . "')");
            db_query("DELETE FROM $sql_tbl[supplier_product_feeds] WHERE manufacturerid IN ('" . implode("','", $ids) . "')");
            db_query("DELETE FROM $sql_tbl[manufacturers_lng] WHERE manufacturerid IN ('" . implode("','", $ids) . "')");
            db_query("UPDATE $sql_tbl[products] SET manufacturerid = 0 WHERE manufacturerid IN ('" . implode("','", $ids) . "')");
            func_delete_image($ids, "M");
            $top_message["content"] = func_get_langvar_by_name("msg_adm_manufacturer_del");
        }
    } elseif ($mode == "delete_image" && $manufacturerid) {
        #
        # Delete image of selected manufacturer
        #
        func_delete_image($manufacturerid, "M");
    } elseif ($mode == "update" and empty($provider_condition)) {
        #
        # Update manufacturers list
        #
        if (is_array($records)) {
            foreach ($records as $k => $v) {
                $v["avail"] = (empty($v["avail"]) ? "N" : "Y");
                $v["orderby"] = intval($v["orderby"]);
                db_query("UPDATE $sql_tbl[manufacturers] SET avail='$v[avail]', orderby='$v[orderby]' WHERE manufacturerid='$k' $provider_condition");
            }
            $top_message["content"] = func_get_langvar_by_name("msg_adm_manufacturers_upd");
        }
    } elseif ($mode == "export_emails") {

        $distributor_contacts = func_query("SELECT * FROM $sql_tbl[distributor_contacts] WHERE email!='' AND contact_name!=''");

        $distributors_list = "";

        if (!empty($distributor_contacts)) {
            foreach ($distributor_contacts as $k => $v) {
                $distributors_list .= $v["contact_name"] . " <" . $v["email"] . ">\r\n";
            }
        }

        $fh = fopen($xcart_dir . "/files/distributor_contacts.txt", "w");
        fwrite($fh, $distributors_list);
        fclose($fh);

        func_header_location("manufacturers.php?word=num");
    }

    $page_str = (!empty($page) ? "&page=$page" : "");

    func_header_location("manufacturers.php?manufacturerid=$manufacturerid" . $page_str . '&word=' . $word . ($distributor_section ? "&distributor_section=" . $distributor_section : ""));
}


/*if (is_file($xcart_dir . "/files/distributor_contacts.txt")) {
    $distributor_contacts_file_name = "distributor_contacts.txt";
    $smarty->assign('distributor_contacts_file_name', $distributor_contacts_file_name);
    $smarty->assign('distributor_contacts_file', $xcart_dir . "/files/" . $distributor_contacts_file_name);
}*/

#
# Process the GET request
#

if ($mode === "add" or !empty($manufacturerid)) {
#
# Get the manufacturer data and display manufacturer details page
#
    /*if ($mode === 'add') {
        $active_operators = func_query("SELECT login, b_firstname, b_lastname FROM $sql_tbl[customers] WHERE usertype='P' AND status='Y' AND activity='Y' ORDER BY login");

        $smarty->assign('operators', $active_operators);
    }*/

    $location[count($location) - 1][1] = 'manufacturers.php?word=num';

    if (!empty($manufacturerid)) {

        /** @var DistributorModel $distributor_model */
        $distributor_model = DistributorModel::objects()->get(['manufacturerid' => $manufacturerid]);

        $manufacturer_data = func_query_first("SELECT $sql_tbl[manufacturers].*, IF($sql_tbl[images_M].id IS NULL, '', 'Y') as is_image, IFNULL($sql_tbl[manufacturers_lng].manufacturer, $sql_tbl[manufacturers].manufacturer) as manufacturer, IFNULL($sql_tbl[manufacturers_lng].descr, $sql_tbl[manufacturers].descr) as descr FROM $sql_tbl[manufacturers] LEFT JOIN $sql_tbl[manufacturers_lng] ON $sql_tbl[manufacturers_lng].manufacturerid = $sql_tbl[manufacturers].manufacturerid AND $sql_tbl[manufacturers_lng].code = '$shop_language' LEFT JOIN $sql_tbl[images_M] ON $sql_tbl[images_M].id = $sql_tbl[manufacturers].manufacturerid WHERE $sql_tbl[manufacturers].manufacturerid = '$manufacturerid'");

        if (!$distributor_model) {
            $top_message['content'] = func_get_langvar_by_name('msg_adm_err_manufacturer_not_exists');
            $top_message['type'] = 'E';
            func_header_location('manufacturers.php');
        } else {
            $location[] = array($manufacturer_data['manufacturer'], '');

            $distributor_field_codes = func_query("SELECT distributor_field_code FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid' ORDER BY distributor_field_code");
            if (!empty($distributor_field_codes) && is_array($distributor_field_codes)) {
                foreach ($distributor_field_codes as $k => $v) {
                    $distributor_contacts_values = func_query_first("SELECT * FROM $sql_tbl[distributor_contacts] WHERE manufacturerid = '$manufacturerid' AND distributor_field_code='$v[distributor_field_code]'");
                    if (!empty($distributor_contacts_values) && is_array($distributor_contacts_values)) {
                        $manufacturer_data["distributor_contacts"][$v["distributor_field_code"]] = $distributor_contacts_values;
                    }
                }
            }

            $distributor_return_addresses = func_query("SELECT * FROM $sql_tbl[distributor_return_address] WHERE manufacturerid='$manufacturerid' ORDER BY warehouse_name");
            $manufacturer_data["distributor_return_addresses"] = $distributor_return_addresses;


            $manufacturer_data["good_time_to_send_email_to_distributor"] = $distributor_model->isGoodTimeToSendEmail() ? 'Y' : 'N';

            $manufacturer_data["distributor_phone"] = func_query_first_cell("SELECT phone FROM $sql_tbl[distributor_contacts] WHERE manufacturerid='$manufacturerid' AND phone!='' ORDER BY distributor_field_code asc LIMIT 1");

            $phone_normalized = preg_replace("/[^0-9]/S", "", $manufacturer_data["distributor_phone"]);
            if (strlen($phone_normalized) == "10") {
                $manufacturer_data['distributor_phone_phone_normalized'] = '+1' . $phone_normalized;
            }

            $smarty->assign('manufacturer', $manufacturer_data);
            $smarty->assign('image', func_image_properties('M', $manufacturerid));
        }

    } else {


        if (!empty($manufacturer_data_form))
            $smarty->assign('manufacturer', $manufacturer_data_form);


        x_session_unregister('manufacturer_data_form');
        $location[] = [func_get_langvar_by_name('lbl_add_manufacturer'), ''];
    }

    $smarty->assign('mode', 'manufacturer_info');
} else {
#
# Get and display the manufacturers list
#
    $qs = DistributorModel::objects();

    if ($word) {
        if (in_array($word, range('a', 'z'), true)) {
            $qs->filter(['manufacturer__startswith' => $word]);
        } elseif ($word === 'num') {
            $qs->filter(['manufacturer__raw' => "REGEXP '^[0-9]+.*'"]);
        }
        $smarty->assign('word', $word);
        $word = 'word=' . $word;
    }

    if ($search) {
        $qs->filter(new QOr(['manufacturer__contains' => $search, 'code__contains' => $search]));
    }

    if ($search_site = \Xcart\App\Main\Xcart::app()->request->get->get('search_site')) {
        $qs->filter(['sites__storefrontid__in' => $search_site]);
    }

    if ($search_vrs = \Xcart\App\Main\Xcart::app()->request->get->get('search_vrs')) {
        $qs->filter(['provider__in' => $search_vrs]);
    }

    $qs->order(['orderby', 'manufacturer']);

    $objects_per_page = $config['Manufacturers']['manufacturers_per_page'];
    $pager = new Pagination($qs->getQuerySet(), ['pageSize' => $objects_per_page], new QuerySetDataSource());

    $smarty->assign('manufacturers', $pager->paginate());

    $smarty->assign('pager', $pager->render());

    $smarty->assign('words', range('a', 'z'));

}

if (!empty($page)) {
    $smarty->assign('page', $page);
}

$distributor_sections = [
    [
        'title' => 'General distributor information',
        'order_by' => '10',
        'distributor_section' => '1'
    ],
    [
        'title' => 'Quick links',
        'order_by' => '11',
        'distributor_section' => '15'
    ],
    [
        'title' => 'Front-end messages',
        'order_by' => '20',
        'distributor_section' => '2'
    ],
    [
        'title' => 'Distributor contacts',
        'order_by' => '30',
        'distributor_section' => '3'
    ],
    [
        'title' => 'Distributor pricing equations',
        'order_by' => '50',
        'distributor_section' => '5'
    ],
    [
        'title' => 'Distributor ships from',
        'order_by' => '60',
        'distributor_section' => '6'
    ],
    [
        'title' => 'Distributor shipping policy',
        'order_by' => '70',
        'distributor_section' => '7'
    ],
    [
        'title' => 'UPS shipping markups',
        'order_by' => '73',
        'distributor_section' => '19'
    ],
    [
        'title' => 'Flat rate shipping markups',
        'order_by' => '74',
        'distributor_section' => '21'
    ],
    [
        'title' => 'Requesting availability / shipping quote / cost to us',
        'order_by' => '75',
        'distributor_section' => '14'
    ],
    [
        'title' => 'Order submission',
        'order_by' => '80',
        'distributor_section' => '8'
    ],
    [
        'title' => 'Order tracking',
        'order_by' => '85',
        'distributor_section' => '12'
    ],
    [
        'title' => 'Tax policy',
        'order_by' => '90',
        'distributor_section' => '9'
    ],
    [
        'title' => 'Return policy',
        'order_by' => '100',
        'distributor_section' => '10'
    ],
    [
        'title' => 'Product page locked fields',
        'order_by' => '105',
        'distributor_section' => '22'
    ],
    [
        'title' => 'Distributor invoices',
        'order_by' => '110',
        'distributor_section' => '13'
    ],
    [
        'title' => 'Payment to distributor arrangement',
        'order_by' => '120',
        'distributor_section' => '11'
    ],
    [
        'title' => 'Product questions',
        'order_by' => '130',
        'distributor_section' => '16'
    ],
    [
        'title' => 'Distributor feeds info',
        'order_by' => '140',
        'distributor_section' => '17'
    ],
    [
        'title' => 'SF product page behavior',
        'order_by' => '160',
        'distributor_section' => '20'
    ],
    [
        'title' => 'Clone distributor to another storefront',
        'order_by' => '170',
        'distributor_section' => '30'
    ],
    [
        'title' => 'External marketplaces',
        'order_by' => '180',
        'distributor_section' => '40'
    ],
    [
        'title' => 'Product verification settings',
        'order_by' => '180',
        'distributor_section' => '31'
    ]
];

if ($distributor_section === 7) {
    $smarty->assign('trackingLinksCarriers', TrackingLinksCarrierModel::objects()->order(['orderby']));
}

if ($distributor_section === 17) {
    $smarty->assign('supplier_feeds_info_I', $distributorModel->feeds->filter(['feed_type' => 'I']));
    $smarty->assign('supplier_feeds_info_P', $distributorModel->feeds->filter(['feed_type' => 'P']));
}

if ($distributor_section === 30) {
    $classManufacturer = new Xcart\Manufacturers();
    $aParentManufacturer = $classManufacturer->getChildrenManufacturers($manufacturerid);

    $smarty->assign('aParentManufacturer', $aParentManufacturer);
    $aChildManufacturers = $classManufacturer->getParentManufacturers($manufacturerid);
    $smarty->assign('aChildManufacturers', $aChildManufacturers);
}

if ($distributor_section === 40) {
    global $xcart_dir;
    $aMarketplaces = ExternalMarketPlace::getExternalMarketPlaces();
    $aExternalMarketplaces = [];
    if (!empty($aMarketplaces)) {
        foreach ($aMarketplaces as $oMarketPlace) {
            $aExternalMarketplaces['values'][] = $oMarketPlace->getMarketPlaceId();
            $aExternalMarketplaces['names'][] = $oMarketPlace->getMarketPlaceName();
        }
    }
    $aDisabledMarketPlaces = DisabledMarketPlace::getDisabledMarketPlaces($manufacturerid, 'D');
    $smarty->assign('aExternalMarketplaces', $aExternalMarketplaces);
    $smarty->assign('aDisabledMarketPlaces', array_values($aDisabledMarketPlaces));
}


$count_rows_in_cell = ceil(count($distributor_sections) / 2);

if (empty($distributor_section))
    $distributor_section = 1;

$smarty->assign('distributor_section', $distributor_section);
$smarty->assign('count_rows_in_cell', $count_rows_in_cell);
$smarty->assign('distributor_sections', $distributor_sections);

$ca_statuses = func_query("SELECT * FROM $sql_tbl[attention_tags_values] WHERE active='Y' AND status!='' ORDER BY orderby");
$smarty->assign('ca_statuses', $ca_statuses);

$smarty->assign('currencies', CurrencyModel::objects()->all());

