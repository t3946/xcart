<?php
require "./auth.php";
require $xcart_dir . "/include/security.php";

use Xcart\App\QueryBuilder\Q\QAnd;
use Xcart\App\QueryBuilder\Q\QOr;
use Xcart\App\QueryBuilder\QueryBuilder;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Helpers\OrderReconciliationHelper;
use Modules\Order\Models\OrderGroupInvoiceModel;
use Modules\Order\Models\OrderGroupMemoModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\ReconciliationModel;
use Xcart\Connection;

set_time_limit(0);
ini_set('memory_limit', '512M');

x_session_register('search_data', []);

$all_tabs = ['unreconciled', 'reconciled', 'dropped', 'expense_report', 'import', 'calculation', 'accounts_payable', 'receivables', 'rules', 'inventory'];


function func_find_reconciliations_orders($reconciliations_to_check, $orders_to_check)
{
    $found_invoices_and_memos = [];

    foreach ($reconciliations_to_check as $k => $v) {

        $r_id = $v->id;
        $amount_csv = (float) $v->amount_csv;
        $amount_csv_abs = (float) abs($amount_csv);

        $aManufacturersToCheck = [];

        foreach ($v->distributors as $oManufacturer) {

            $aManufacturersToCheck[] = $oManufacturer->manufacturerid;

            if ($oManufacturer->parent_manufacturer_id < 0) {
                foreach ($oManufacturer->childs as $mModel) {
                    $aManufacturersToCheck[] = $mModel->manufacturerid;
                }
            } else {
                foreach ($oManufacturer->parents as $mModel) {
                    $aManufacturersToCheck[] = $mModel->manufacturerid;
                }
            }
        }
        /** @var \Xcart\App\Orm\Manager $orders_models */
        $orders_models = clone $orders_to_check;
        $orders_models = $orders_models->filter(['manufacturerid__in' => $aManufacturersToCheck])->order(['orderid']);
        $order_one_invoice = clone $orders_models;

        if ($oManufacturer->d_bulk_or_individual_order_payments === 'distributor_may_charge_for_several_orders_at_once') {

            $cnt = $order_one_invoice->filter($i_filter = [
                'invoices__status' => 'U',
                'invoices__reconciliation_id' => 0,
                'invoices__invoice_total' => abs($v->amount_csv)
            ]
            )->count();
            if (($cnt === 1) && $_order = $order_one_invoice->get($i_filter)) {
                /** @var OrderGroupInvoiceModel $invoice */
                $invoice = $_order->invoices->filter(['status' => 'U', 'reconciliation_id' => 0, 'invoice_total' => abs($v->amount_csv)])->get();
                $found_invoices_and_memos[] = (string) $invoice;
                $invoice->reconciliation_id = $r_id;
                $invoice->save();
                continue;
            }

            $SUM_invoice_total_OF_found_invoices = 0;

            foreach (OrderGroupInvoiceModel::objects()->filter(['reconciliation_id' => $r_id]) as $rc) {
                $SUM_invoice_total_OF_found_invoices += $rc->invoice_total;
                $found_invoices_and_memos[] = (string) $rc;
            }
            foreach (OrderGroupMemoModel::objects()->filter(['reconciliation_id' => $r_id]) as $rc) {
                $SUM_invoice_total_OF_found_invoices -= $rc->ref_to_us_total;
                $found_invoices_and_memos[] = (string) $rc;
            }

            foreach ($orders_models as $kk => $vv) {

                /** @var OrderGroupInvoiceModel $invoice_info */
                if ($vv->order->date < $v->date_csv) {
                    foreach ($vv->invoices->filter(['status' => 'U', 'reconciliation_id' => 0]) as $invoice_info) {
                        $price_to_search = (float) $invoice_info->invoice_total;

                        $sum_total = round($SUM_invoice_total_OF_found_invoices + $price_to_search, 2);
                        $count_reconciled_invoices_for_current_manufacturerid_with_such_reconciliation_id = ($invoice_info->reconciliation_id == $r_id);
                        if (
                            $amount_csv < 0
                            && $price_to_search <= $amount_csv_abs
                            && $sum_total <= $amount_csv_abs
                            && !in_array((string)$invoice_info, $found_invoices_and_memos, true)
                            && !$count_reconciled_invoices_for_current_manufacturerid_with_such_reconciliation_id
                        ) {
                            $SUM_invoice_total_OF_found_invoices += $price_to_search;
                            $found_invoices_and_memos[] = (string)$invoice_info;
                            $invoice_info->reconciliation_id = $r_id;
                            $invoice_info->save();
                        }
                    }

                    /** @var OrderGroupMemoModel $memo_info */
                    foreach ($vv->memos->filter(['status' => 'U', 'reconciliation_id' => 0]) as $memo_info) {
                        $price_to_search = (float) $memo_info->ref_to_us_total;
                        $sum_total = round($SUM_invoice_total_OF_found_invoices - $price_to_search, 2);
                        $count_reconciled_memos_for_current_manufacturerid_with_such_reconciliation_id = ($memo_info->reconciliation_id == $r_id);
                        if (
                            $amount_csv < 0
                            && $sum_total <= $amount_csv_abs
                            && !in_array((string) $memo_info, $found_invoices_and_memos, true)
                            && !$count_reconciled_memos_for_current_manufacturerid_with_such_reconciliation_id
                        ) {
                            $found_invoices_and_memos[] = (string) $memo_info;
                            $memo_info->reconciliation_id = $r_id;
                            $memo_info->save();
                        }
                    }
                }
            }
        } elseif ($oManufacturer->d_bulk_or_individual_order_payments === 'distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping') {

            foreach ($orders_models->all() as $kk => $vv) {

                if ($vv->order->date < $v->date_csv) {
                    /** @var OrderGroupInvoiceModel $invoice_info */
                    /** @var OrderGroupMemoModel $memo_info */

                    $SUM_invoice_total = 0;
                    foreach ($vv->invoices->filter(['status' => 'U']) as $invoice_info) {
                        $SUM_invoice_total += (float) $invoice_info->invoice_total;
                    }

                    if ($SUM_invoice_total > 0) {
                        $price_to_search = $SUM_invoice_total;
                        if ($amount_csv < 0 && $price_to_search === $amount_csv_abs) {
                            foreach ($vv->invoices->filter(['status' => 'U']) as $invoice_info) {
                                if (!in_array((string) $vv, $found_invoices_and_memos, true)) {
                                    $found_invoices_and_memos[] = (string)$invoice_info;
                                    $invoice_info->reconciliation_id = $r_id;
                                    $invoice_info->save();
                                }
                            }
                        }
                    }

                    $SUM_ref_to_us_total = 0;
                    foreach ($vv->memos->filter(['status' => 'U']) as $memo_info) {
                        $SUM_ref_to_us_total += (float) $memo_info->ref_to_us_total;
                    }

                    if ($SUM_ref_to_us_total > 0) {
                        $price_to_search = $SUM_ref_to_us_total;
                        if ($amount_csv > 0 && $price_to_search === $amount_csv) {
                            foreach ($vv->memos->filter(['status' => 'U']) as $memo_info) {
                                if (!in_array((string) $memo_info, $found_invoices_and_memos, true)) {
                                    $found_invoices_and_memos[] = (string) $memo_info;
                                    $memo_info->reconciliation_id = $r_id;
                                    $memo_info->save();
                                }
                            }
                        }
                    }
                }
            }
        } elseif ($oManufacturer->d_bulk_or_individual_order_payments === 'distributor_charges_for_each_order_separately') {

            foreach ($orders_models->all() as $kk => $vv) {

                $i_cnt = $vv->invoices->filter(['reconciliation_id__gt' => 0])->count();
                $m_cnt = $vv->memos->filter(['reconciliation_id__gt' => 0])->count();

                if ($i_cnt || $m_cnt) {
                    continue;
                }

                if ($vv->order->date < $v->date_csv) {

                    foreach ($vv->invoices->filter(['status' => 'U']) as $invoice) {
                        $price_to_search = (float) $invoice->invoice_total;

                        if ($amount_csv < 0 && $price_to_search === $amount_csv_abs && !in_array((string) $invoice, $found_invoices_and_memos, true)) {
                            $is_such_invoice_in_db = OrderGroupInvoiceModel::objects()->filter(['reconciliation_id' => $r_id])->count() > 0;
                            if (!$is_such_invoice_in_db) {
                                $found_invoices_and_memos[] = (string) $invoice;
                                $invoice->reconciliation_id = $r_id;
                                $invoice->save();
                            }
                        }
                    }

                    foreach ($vv->memos->filter(['status' => 'U']) as $memo) {
                        $price_to_search = (float) $memo->ref_to_us_total;

                        if ($amount_csv > 0 && $price_to_search === $amount_csv && !in_array((string) $memo, $found_invoices_and_memos, true)) {
                            $is_such_memo_in_db = OrderGroupMemoModel::objects()->filter(['reconciliation_id' => $r_id])->count() > 0;
                            if (!$is_such_memo_in_db) {
                                $found_invoices_and_memos[] = (string) $memo;
                                $memo->reconciliation_id = $r_id;
                                $memo->save();
                            }
                        }
                    }
                }
            }
        }
    }
}

$manufacturerid_info = func_query_hash("SELECT code, manufacturerid, d_bulk_or_individual_order_payments, d_search_keyphrase_for_reconciliation, manufacturer FROM $sql_tbl[manufacturers]", 'manufacturerid', false);

$config_reconciliation_search_keyphrases = func_query("SELECT * FROM $sql_tbl[reconciliation_search_keyphrases] ORDER BY code");

if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)) {
    foreach ($config_reconciliation_search_keyphrases as $k => $v) {
        $config_reconciliation_search_keyphrases[$k]["total_amount"] = 0;
        $config_reconciliation_search_keyphrases[$k]["total_amount_with_abs"] = 0;
        $config_reconciliation_search_keyphrases[$k]["found_records"] = array();
    }
}

if ($tab == "rules") {

    $search_keyphrase_list = array();
    $tmp_counter = 0;

    foreach ($manufacturerid_info as $k => $v) {
        if (!empty($v["d_search_keyphrase_for_reconciliation"])) {

            $d_search_keyphrase_for_reconciliation_arr = explode("<OR>", $v["d_search_keyphrase_for_reconciliation"]);
            foreach ($d_search_keyphrase_for_reconciliation_arr as $kk_s_r => $vv_s_r) {
                $vv_s_r = trim($vv_s_r);

                $search_keyphrase_list[$tmp_counter]["search_keyphrase"] = $vv_s_r;
                $search_keyphrase_list[$tmp_counter]["manufacturerid"] = $k;
                $search_keyphrase_list[$tmp_counter]["manufacturer"] = $v["manufacturer"];
                $tmp_counter++;
            }
        }
    }

    if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)) {
        foreach ($config_reconciliation_search_keyphrases as $k => $v) {
            if (!empty($v["search_keyphrase"])) {
                $v_search_keyphrase_UPPER_arr = explode("<OR>", $v["search_keyphrase"]);
                foreach ($v_search_keyphrase_UPPER_arr as $v_search_keyphrase_UPPER) {
                    $v_search_keyphrase_UPPER = trim($v_search_keyphrase_UPPER);
                    $search_keyphrase_list[$tmp_counter]["search_keyphrase"] = $v_search_keyphrase_UPPER;
                    $search_keyphrase_list[$tmp_counter]["id"] = $v["id"];
                    $search_keyphrase_list[$tmp_counter]["code"] = $v["code"];
                    $tmp_counter++;
                }
            }
        }
    }

    if (!empty($search_keyphrase_list)) {

        $search_keyphrase_list = my_array_sort($search_keyphrase_list, "search_keyphrase");
        $search_keyphrase_list = array_values($search_keyphrase_list);
    }

}

$search_data = $search_data ?: [];
$search_data = \is_array($search_data) ? $search_data : [];

if ($REQUEST_METHOD == "POST") {


    if (!empty($data_orders_selectbox)) {

        foreach ($all_tabs as $t) {
            $search_data["reconciliation_tab_" . $t]["data_orders_selectbox"] = $data_orders_selectbox;
        }

        if (!empty($date_csv_End)) {
            $date_End = $date_csv_End;
            $end_date_arr = explode("/", $date_csv_End);
            $end_date = mktime(23, 59, 59, $end_date_arr[0], $end_date_arr[1], $end_date_arr[2]);

            $date_Start_time = $end_date - $data_orders_selectbox * 31 * 60 * 60 * 24;
            $date_Start = date("m/d/Y", $date_Start_time);
        }
    }


    if (!empty($date_csv_Start)) {
        $posted_data["date_csv"]["start_date_str"] = $date_csv_Start;
        $start_date_arr = explode("/", $date_csv_Start);
        $posted_data["date_csv"]["start_date"] = mktime(0, 0, 0, $start_date_arr[0], $start_date_arr[1], $start_date_arr[2]);

        foreach ($all_tabs as $t) {
            $search_data["reconciliation_tab_" . $t]["date_csv"]["start_date"] = $posted_data["date_csv"]["start_date"];
            $search_data["reconciliation_tab_" . $t]["date_csv"]["start_date_str"] = $posted_data["date_csv"]["start_date_str"];
        }
    }

    if (!empty($date_csv_End)) {
        $posted_data["date_csv"]["end_date_str"] = $date_csv_End;
        $end_date_arr = explode("/", $date_csv_End);
        $posted_data["date_csv"]["end_date"] = mktime(23, 59, 59, $end_date_arr[0], $end_date_arr[1], $end_date_arr[2]);

        foreach ($all_tabs as $t) {
            $search_data["reconciliation_tab_" . $t]["date_csv"]["end_date"] = $posted_data["date_csv"]["end_date"];
            $search_data["reconciliation_tab_" . $t]["date_csv"]["end_date_str"] = $posted_data["date_csv"]["end_date_str"];
        }
    }/* else {
        foreach ($all_tabs as $t) {
            unset(
                $search_data["reconciliation_tab_" . $t]["date_csv"]["end_date"],
                $search_data["reconciliation_tab_" . $t]["date_csv"]["end_date_str"]
            );
        }
    }*/

    if (!empty($date_Start)) {
        $posted_data["date"]["start_date_str"] = $date_Start;
        $start_date_arr = explode("/", $date_Start);
        $posted_data["date"]["start_date"] = mktime(0, 0, 0, $start_date_arr[0], $start_date_arr[1], $start_date_arr[2]);

        foreach ($all_tabs as $t) {
            $search_data["reconciliation_tab_" . $t]["date"]["start_date"] = $posted_data["date"]["start_date"];
            $search_data["reconciliation_tab_" . $t]["date"]["start_date_str"] = $posted_data["date"]["start_date_str"];
        }
    }

    if (!empty($date_End)) {
        $posted_data["date"]["end_date_str"] = $date_End;
        $end_date_arr = explode("/", $date_End);
        $posted_data["date"]["end_date"] = mktime(23, 59, 59, $end_date_arr[0], $end_date_arr[1], $end_date_arr[2]);

        foreach ($all_tabs as $t) {
            $search_data["reconciliation_tab_" . $t]["date"]["end_date"] = $posted_data["date"]["end_date"];
            $search_data["reconciliation_tab_" . $t]["date"]["end_date_str"] = $posted_data["date"]["end_date_str"];
        }
    }

    if ($tab == "unreconciled" || $tab == "reconciled" || $tab == "calculation") {
        $search_data["reconciliation_tab_unreconciled"]["manufacturers"] = $posted_data["manufacturers"];
        $search_data["reconciliation_tab_reconciled"]["manufacturers"] = $posted_data["manufacturers"];
        $search_data["reconciliation_tab_calculation"]["manufacturers"] = $posted_data["manufacturers"];

        $search_data["reconciliation_tab_unreconciled"]["select_distributors"] = $posted_data["select_distributors"];
        $search_data["reconciliation_tab_reconciled"]["select_distributors"] = $posted_data["select_distributors"];
        $search_data["reconciliation_tab_calculation"]["select_distributors"] = $posted_data["select_distributors"];
    }

    if ($tab == "unreconciled") {

        if (!isset($posted_data["show_unreconciled_invoices_and_memos"]) || empty($posted_data["show_unreconciled_invoices_and_memos"])) {
            $posted_data["show_unreconciled_invoices_and_memos"] = "N";
        }
        $search_data["reconciliation_tab_unreconciled"]["show_unreconciled_invoices_and_memos"] = $posted_data["show_unreconciled_invoices_and_memos"];
    }

    x_session_save("search_data");
    if ($mode == "search") {

    } elseif ($mode == "import" && $_FILES["userfile"]["error"] == "0") {

        $cur_time = time();

        $userfile = $xcart_dir . "/files/reconciliation_feeds/" . $cur_time . ".csv";

        if (move_uploaded_file($_FILES["userfile"]['tmp_name'], $userfile)) {

            if ($delimiter == 'tab')
                $delimiter = "\t";

            $handle = @func_fopen($userfile, "r", true);
            if ($handle) {
                $line_number = 0;
                $count_added_rows = 0;
                $amount_csv_total = 0;
                $min_date_in_file = 0;
                $max_date_in_file = 0;

                $PayPal_file = false;
                $transaction_type = "";


                while (($buffer = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {

                    $line_number++;

                    $tmp_net_field = trim($buffer[9]);
                    $tmp_net_field = strtoupper($tmp_net_field);
                    if ($tmp_net_field == "NET") {
                        $PayPal_file = true;
                        $transaction_type = "P";
                    }

                    if ($line_number == "1" && $PayPal_file)
                        continue;

                    if ($PayPal_file) {
                        $buffer[9] = trim($buffer[9]);
                        $buffer[8] = trim($buffer[9]);

                        $buffer[9] = str_replace(",", "", $buffer[9]);
                        $buffer[8] = str_replace(",", "", $buffer[8]);


                    } else {
                        $buffer[1] = trim($buffer[1]);

// https://basecamp.com/2070980/projects/1577907/messages/30895704
                        $buffer[1] = str_replace(",", ".", $buffer[1]);
                    }

                    if (!($buffer[7] < 0 && $buffer[8] <= 0) && $PayPal_file)
                        continue;

                    if ($PayPal_file) {
                        $description_csv = addslashes(trim($buffer[3]));
                        $amount_csv = $buffer[9];
                    } else {
                        $description_csv = addslashes(trim($buffer[4]));
                        $amount_csv = $buffer[1];
                    }

                    $date_csv_tmp = trim($buffer[0]);
                    $date_csv_arr = explode("/", $date_csv_tmp);
                    $date_csv = mktime(0, 0, 0, $date_csv_arr[0], $date_csv_arr[1], $date_csv_arr[2]);

                    if (empty($min_date_in_file)) {
                        $min_date_in_file = $date_csv;
                    } elseif ($date_csv < $min_date_in_file && !empty($date_csv)) {
                        $min_date_in_file = $date_csv;
                    }

                    if ($date_csv > $max_date_in_file) {
                        $max_date_in_file = $date_csv;
                    }

                    $amount_csv_total += $amount_csv;

                    $insert_to_db = false;

                    $is_such_description_csv = func_query_first("SELECT * FROM $sql_tbl[reconciliations] WHERE description_csv='$description_csv' AND date_csv='$date_csv' AND amount_csv='$amount_csv'");

                    if (!empty($is_such_description_csv) && is_array($is_such_description_csv)) {
                        if ($is_such_description_csv["file_upload_date"] == $cur_time) {
                            $insert_to_db = true;
                        }
                    } else {
                        $insert_to_db = true;
                    }

                    if ($insert_to_db) {

                        $action = "";
                        if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)) {
                            foreach ($config_reconciliation_search_keyphrases as $k => $v) {
                                if (!empty($description_csv) && !empty($v["search_keyphrase"])) {

                                    $description_csv_UPPER = strtoupper($description_csv);
                                    $v_search_keyphrase_UPPER = strtoupper($v["search_keyphrase"]);

                                    $v_search_keyphrase_UPPER_arr = explode("<OR>", $v_search_keyphrase_UPPER);
                                    foreach ($v_search_keyphrase_UPPER_arr as $v_search_keyphrase_UPPER) {
                                        $v_search_keyphrase_UPPER = trim($v_search_keyphrase_UPPER);
                                        if (strpos($description_csv_UPPER, $v_search_keyphrase_UPPER) !== false) {
                                            $action = "D"; // Drop
                                        }
                                    }
                                }
                            }
                        }

                        $manufacturerid = '0';
                        foreach ($manufacturerid_info as $k => $v) {
                            if (!empty($description_csv) && !empty($v["d_search_keyphrase_for_reconciliation"])) {

                                $d_search_keyphrase_for_reconciliation_arr = explode("<OR>", $v["d_search_keyphrase_for_reconciliation"]);
                                foreach ($d_search_keyphrase_for_reconciliation_arr as $kk_s_r => $vv_s_r) {
                                    $vv_s_r = trim($vv_s_r);
//		                                                if (strpos($description_csv, $v["d_search_keyphrase_for_reconciliation"]) !== false){

                                    $description_csv_UPPER = strtoupper($description_csv);
                                    $vv_s_r_UPPER = strtoupper($vv_s_r);

                                    if (strpos($description_csv_UPPER, $vv_s_r_UPPER) !== false) {
                                        $manufacturerid = $k;
                                        break;
                                    }

                                }
                            }
                        }

                        db_query("INSERT INTO $sql_tbl[reconciliations] (description_csv, date_csv, amount_csv, file_upload_date, action, manufacturerid, transaction_type) VALUES ('$description_csv', '$date_csv', '$amount_csv', '$cur_time', '$action', '$manufacturerid', '$transaction_type')");

                        if ($pre_rec = ReconciliationModel::objects()->limit(1)->get([
                            'amount_csv' => $amount_csv,
                            'action' => ReconciliationModel::RECONCILIATION_STATUS_PRE_RECONCILED
                        ]))
                        {
                            $pre_rec->setAttributes([
                                'action' => ReconciliationModel::RECONCILIATION_STATUS_RECONCILED,
                                'description_csv' => $description_csv,
                                'date_csv' => $date_csv,
                            ]);
                            if ($pre_rec->save()) {
                                $pre_rec->invoices->update(['status' => 'R']);
                                $pre_rec->memos->update(['status' => 'R']);
                            }
                        }
                        
                        $count_added_rows++;
                    }
                }
                fclose($handle);

                db_query("INSERT INTO $sql_tbl[reconciliation_upload_info] (date, orig_file_name, local_file, login, min_date_in_file, max_date_in_file, count_lines, checksum, count_added_rows) VALUES ('$cur_time', '" . $_FILES["userfile"]["name"] . "', '$userfile', '$login', '$min_date_in_file', '$max_date_in_file', '$line_number', '$amount_csv_total', '$count_added_rows')");

                $top_message["content"] = "File uploaded.";
                $top_message["type"] = "I";
            } else {
                fclose($handle);
                @unlink($userfile);

                $top_message["content"] = func_get_langvar_by_name("msg_err_file_wrong");
                $top_message["type"] = "E";
            }
        }
    } elseif ($mode == "find_orders") {

        $_filter = [
            'date_csv__gte' => $search_data["reconciliation_tab_calculation"]["date_csv"]["start_date"] ?: time(),
            'date_csv__lte' => $search_data["reconciliation_tab_calculation"]["date_csv"]["end_date"] ?: time(),
            'action' => ''
        ];

        OrderReconciliationHelper::checkReconcileRules($_filter);

        $_filter_m = ['distributors__manufacturerid__isnull' => false];


            if (empty($search_data["reconciliation_tab_" . $tab]["manufacturers"])) {
                $top_message["content"] = "Select distributor(s)";
                $top_message["type"] = "E";
                func_header_location("reconciliation.php?tab=" . $tab);
            }
            $_filter_m = ['distributors__manufacturerid__in' => $search_data["reconciliation_tab_" . $tab]["manufacturers"]];


        $reconcileModels = ReconciliationModel::objects()->filter(array_merge($_filter, $_filter_m))->order(['id'])->all();

        $orderReconcile = OrderGroupModel::objects()->filter([
            'order__date__gte' => $search_data['reconciliation_tab_calculation']['date']['start_date'],
            'order__date__lte' => $search_data['reconciliation_tab_calculation']['date']['end_date'],
            new QOr(['invoices__status' => 'U', 'memos__status' => 'U'])
        ])->group(['order_group_id']);

        if ($reconcileModels) {
            func_find_reconciliations_orders($reconcileModels, $orderReconcile);
        }

    } elseif ($mode == "update") {

        // Untie selected transaction-order connections
        if (!empty($clear_invoices_memos) && is_array($clear_invoices_memos)) {
            foreach ($clear_invoices_memos as $k => $v) {
                if ($v == "Y") {

                    $invoice_memo_arr = explode("_", $k);

                    $invoice_OR_memo = $invoice_memo_arr[0];
                    $reconciliation_id = $invoice_memo_arr[1];
                    $number = $invoice_memo_arr[2];
                    $manufacturerid = $invoice_memo_arr[3];
                    $orderid = $invoice_memo_arr[4];

                    if ($invoice_OR_memo == "I") {
                        db_query("UPDATE $sql_tbl[order_group_invoices] SET reconciliation_id='0' WHERE reconciliation_id='$reconciliation_id' AND invoice_number='$number' AND manufacturerid='$manufacturerid' AND orderid='$orderid'");
                    } else {
                        db_query("UPDATE $sql_tbl[order_group_memos] SET reconciliation_id='0' WHERE reconciliation_id='$reconciliation_id' AND memo_number='$number' AND manufacturerid='$manufacturerid' AND orderid='$orderid'");
                    }
                }
            }
        }


        if (isset($add_order_manually) && \is_array($add_order_manually)) {

            $order_not_added_arr = [];

            foreach ($add_order_manually as $r_id => $v_arr) {
                if ($v_arr && is_array($v_arr) && $r_model = ReconciliationModel::objects()->get(['id' => $r_id])) {

                    foreach ($v_arr as $v) {

                        $orderid = trim($v["orderid"]);
                        if (strpos($orderid, "-") !== false) {
                            $orderid_arr = explode("-", $orderid);
                            $orderid = $orderid_arr["1"];
                        }

                        /** @var OrderModel $order_model */
                        $order_model = OrderModel::objects()->get(['orderid' => $orderid]);

                        if (!$order_model) {continue;}

                        $order_added = false;

                        $f_invoice = [
                            'status' => 'U',
//                            'part_of_total_transaction_in_amount_of__in' => [0, abs($r_model->amount_csv)],
                            'orderid' => $orderid
                        ];
                        /*
                         * Hack for Amazon distributor
                         */
                        if (($amz_dx = $r_model->distributors->valuesList(['manufacturerid'], true)) && !in_array(578, $amz_dx)) {
                            $f_invoice['manufacturerid__in'] = $amz_dx;
                        }
                        foreach (OrderGroupInvoiceModel::objects()->filter($f_invoice) as $groupInvoice) {
                            $groupInvoice->setAttributes([
                                'reconciliation_id' => $r_id,
                            ]);
                            if ($groupInvoice->save()) {
                                $order_added = true;
                            }
                        }

                        $f_memo = [
                            'status' => 'U',
//                            'ref_to_us_part_of_transaction__in' => [0, abs($r_model->amount_csv)],
                            'orderid' => $orderid
                        ];
                        foreach (OrderGroupMemoModel::objects()->filter($f_memo) as $_memos) {
                            $_memos->setAttributes([
                                'reconciliation_id' => $r_id,
                            ]);
                            if ($_memos->save()) {
                                $order_added = true;
                            }
                        }

                        if (!$order_added) {
                            $order_not_added_arr[] = "Order # <a href='{$order_model->getAdminUrl()}' target='_blank' style='color: blue;'>{$order_model->getOrderNumber()}</a> hasn't been added.";
                        }

                    }
                }
            }
        }

        if (!empty($action) && is_array($action)) {
            foreach ($action as $k => $v) {
                db_query("UPDATE $sql_tbl[reconciliations] SET action='$v' WHERE id='$k'");

                $status = "U";

                $part_of_total_transaction_in_amount_of = "";
                $ref_to_us_part_of_transaction = "";

                if ($v == "R") {
                    $status = "R";

                    $amount_csv = func_query_first_cell("SELECT amount_csv FROM $sql_tbl[reconciliations] WHERE id='$k'");
                    $amount_csv_abs = abs($amount_csv);
                    $part_of_total_transaction_in_amount_of = ", part_of_total_transaction_in_amount_of='$amount_csv_abs'";
                    $ref_to_us_part_of_transaction = ", ref_to_us_part_of_transaction='$amount_csv_abs'";
                }

                db_query("UPDATE $sql_tbl[order_group_invoices] SET status='$status' $part_of_total_transaction_in_amount_of WHERE reconciliation_id='$k'");
                db_query("UPDATE $sql_tbl[order_group_memos] SET status='$status' $ref_to_us_part_of_transaction WHERE reconciliation_id='$k'");
            }
        }

        if (!empty($order_not_added_arr)) {
            $top_message["content"] = implode("<br />", $order_not_added_arr);
        } else {
            $top_message["content"] = "Done.";
        }

        $top_message["type"] = "I";
    } elseif ($mode == "unreconcile") {

        foreach ($action as $k => $v) {
            if ($v == "UR") {
                db_query("UPDATE $sql_tbl[reconciliations] SET action='' WHERE id='$k'");

                db_query("UPDATE $sql_tbl[order_group_invoices] SET status='U' WHERE reconciliation_id='$k'");
                db_query("UPDATE $sql_tbl[order_group_memos] SET status='U' WHERE reconciliation_id='$k'");
            }
        }

        $top_message["content"] = "Done.";
        $top_message["type"] = "I";
    } elseif ($mode == "undrop") {

        foreach ($action as $k => $v) {
            if ($v == "UD") {
                db_query("UPDATE $sql_tbl[reconciliations] SET action='' WHERE id='$k'");

                db_query("UPDATE $sql_tbl[order_group_invoices] SET status='U' WHERE reconciliation_id='$k'");
                db_query("UPDATE $sql_tbl[order_group_memos] SET status='U' WHERE reconciliation_id='$k'");
            }
        }

        $top_message["content"] = "Done.";
        $top_message["type"] = "I";
    }

    func_header_location("reconciliation.php?tab=" . $tab);
}

if (empty($tab)) $tab = "unreconciled";

if ($tab === "import") {

    $reconciliation_upload_info = func_query("SELECT * FROM $sql_tbl[reconciliation_upload_info] ORDER BY date DESC");
    if (!empty($reconciliation_upload_info) && is_array($reconciliation_upload_info)) {
        foreach ($reconciliation_upload_info as $k => $v) {
            $reconciliation_upload_info[$k]["firstname"] = func_query_first_cell("SELECT firstname FROM $sql_tbl[customers] WHERE login='$v[login]'");
        }
    }
}

if ($tab === 'unreconciled' || $tab === 'reconciled' || $tab === 'dropped' || $tab === 'expense_report' || $tab === 'receivables') {

    if (empty($search_data["reconciliation_tab_" . $tab]["date_csv"])) {
        $search_data["reconciliation_tab_" . $tab]["date_csv"]["end_date"] = time();
        $search_data["reconciliation_tab_" . $tab]["date_csv"]["start_date"] = time() - 30 * 60 * 60 * 24;
    }

    if (empty($search_data["reconciliation_tab_" . $tab]["date"])) {
        $search_data["reconciliation_tab_" . $tab]["date"]["end_date"] = time();
        $search_data["reconciliation_tab_" . $tab]["date"]["start_date"] = time() - 30 * 60 * 60 * 24;
    }

    if ($tab === "accounts_payable" || $tab === "receivables") {

        $order_search_condition = "$sql_tbl[orders].date>='" . ($search_data["reconciliation_tab_" . $tab]["date"]["start_date"]) . "'";
        $order_search_condition .= " AND $sql_tbl[orders].date<='" . ($search_data["reconciliation_tab_" . $tab]["date"]["end_date"]) . "'";

        if ($tab == "receivables") {
            $order_search_condition .= " AND xcart_order_groups.cb_status IN ('O', 'IO') AND xcart_order_groups.dc_status IN ('S','G','L','C') AND xcart_order_groups.po_status IN ('PN', 'P1', 'P2')";

            $order_search_fields = "$sql_tbl[orders].po_number, $sql_tbl[orders].firstname, $sql_tbl[orders].details, ";
        } else {
            $order_search_condition .= " AND $sql_tbl[order_groups].bd_status='X'";
        }


        $orders = func_query(<<<SQL
SELECT $sql_tbl[order_groups].orderid, $sql_tbl[order_groups].manufacturerid, $order_search_fields $sql_tbl[orders].date, $sql_tbl[orders].order_prefix, $sql_tbl[order_groups].bd_status, $sql_tbl[order_groups].manufacturerid, $sql_tbl[order_groups].total_gross, $sql_tbl[orders].b_company, $sql_tbl[orders].b_firstname FROM $sql_tbl[order_groups] LEFT JOIN $sql_tbl[orders] ON $sql_tbl[orders].orderid=$sql_tbl[order_groups].orderid WHERE $order_search_condition
SQL
);

        if (!empty($orders) && $tab == "receivables") {

            x_load('crypt');
            $total_gross_accounting_1_2 = 0;
            $total_gross_accounting_0 = 0;
            $total_gross = 0;


            foreach ($orders as $k => $v) {

                $accounting = func_make_accounting($v["orderid"], $v["manufacturerid"]);

                $v["accounting"] = $accounting;

                $details = text_decrypt($v["details"]);
                $tmp = explode("\n", $details);

                if ($tmp) {
                    $po_fields = array("po_number" => "PO Number", "company_name" => "Company name", "name_of_purchaser" => "Name of purchaser", "position" => "Position", "po_fax" => "po fax");
                    $po_details = array();
                    foreach ($tmp as $line) {
                        if (empty($po_fields)) {
                            break;
                        }
                        foreach ($po_fields as $kk => $po_text) {
                            if (($a = strpos($line, $po_text)) !== false) {
                                $value = substr($line, $a + strlen($po_text) + 2);
                                $po_details[$kk] = $value;
                                unset($po_fields[$kk]);
                                break;
                            }
                        }
                    }
                }

                if ($orderModel = OrderModel::objects()->get(['orderid' => $v['orderid']])) {
                    $v['po_data'] = $orderModel->extra_model->purchase_order;
                }

                $v["details"] = $details;
                $v["po_details"] = $po_details;

                $current_total_gross_accounting_1_2 = $accounting[1]["gross"] + $accounting[2]["gross"];
                $current_total_gross_accounting_0 = $accounting[0]["gross"];
                $current_total_gross = $v["total_gross"];

                $total_gross_accounting_1_2 += $current_total_gross_accounting_1_2;
                $total_gross_accounting_0 += $current_total_gross_accounting_0;
                $total_gross += $current_total_gross;

                $v["current_total_gross_accounting_1_2"] = $current_total_gross_accounting_1_2;
                $v["current_total_gross_accounting_0"] = $current_total_gross_accounting_0;
                $v["current_total_gross"] = $current_total_gross;

                $orders[$k] = $v;
            }

            $smarty->assign("orders", $orders);

            $smarty->assign("total_gross_accounting_1_2", $total_gross_accounting_1_2);
            $smarty->assign("total_gross_accounting_0", $total_gross_accounting_0);
            $smarty->assign("total_gross", $total_gross);


        }

        if ($tab == "receivables") {
            $smarty->assign("aTotalReceivables", (new \Xcart\Reconciliation())->getReceivablesTotalReport());
        }

    } else {
        $_filter = [
            'date_csv__gte' => $search_data["reconciliation_tab_" . $tab]["date_csv"]["start_date"],
            'date_csv__lte' => ($search_data["reconciliation_tab_" . $tab]["date_csv"]["end_date"]) ?: time(),
            'action' => ''
        ];

        if ($tab === 'reconciled') {
            $_filter['action'] = 'R';
        } elseif ($tab === 'dropped') {
            $_filter['action'] = 'D';
        } elseif ($tab === 'unreconciled') {
            unset($_filter['action']);
            $_filter['action__in'] = ['P', ''];
        }

        if ($tab === 'reconciled' || $tab === 'unreconciled') {


                if (!empty($search_data["reconciliation_tab_" . $tab]["manufacturers"])) {
                    $tmp_manufacturers_str = implode("','", $search_data["reconciliation_tab_" . $tab]["manufacturers"]);
                    $tmp_manufacturers_str = "'" . $tmp_manufacturers_str . "'";
                    $_filter_m = ['distributors__manufacturerid__in' => $search_data["reconciliation_tab_" . $tab]["manufacturers"]];
                } else {
                    $tmp_manufacturers_str = "'0'";
                }



            if ($tab === 'unreconciled' && $search_data['reconciliation_tab_' . $tab]['show_unreconciled_invoices_and_memos'] === 'Y') {
                $qs = OrderGroupModel::objects()->getQuerySet();
                $_order_filter = [
                    'order__date__gte' => $search_data['reconciliation_tab_' . $tab]['date_csv']['start_date'],
                    'order__date__lte' => $search_data['reconciliation_tab_' . $tab]['date_csv']['end_date'] ?: time(),
                    'order__order_type__isnt' => OrderModel::ORDER_TYPE_FBA,
                    'amz_fullfilment_order_placed' => 'N',
                    new QOr([
                        'cb_status__in' => [
                            OrderStatusModel::ORDER_STATUS_UNPAID_PO,
                            OrderStatusModel::ORDER_STATUS_COMPLETED,
                            OrderStatusModel::ORDER_STATUS_PENDING_PARTIAL_REFUND,
                            OrderStatusModel::ORDER_STATUS_PARTIAL_REFUND,
                            OrderStatusModel::ORDER_STATUS_FULLY_REFUND,
                        ],
                        'order__order_type' => OrderModel::ORDER_TYPE_FB
                    ]),
                    new QOr([
                        new QAnd(['invoices__status__in' => ['U','A'], 'invoices__reconciliation_id' => 0]),
                        new QAnd(['memos__status__in' => ['U','A'], 'memos__invoices__reconciliation_id' => 0]),
                        'invoices__orderid__isnull' => true
                    ])
                ];

                    if ($filter_manufacturers = $search_data['reconciliation_tab_' . $tab]['manufacturers']) {
                        $_order_filter = array_merge($_order_filter, ['manufacturerid__in' => $filter_manufacturers]);
                    }

                $smarty->assign("unreconciled_orders", $qs->filter($_order_filter)->order(['-invoices__invoice_date', '-memos__memo_date', '-orderid'])->group(['orderid']));

            }
        }

    }

    if ($tab === 'unreconciled') {
        OrderReconciliationHelper::checkReconcileRules($_filter);
    }

    $recModels = ReconciliationModel::objects()->filter(array_merge($_filter, $_filter_m ?? []))->group(['id'])->order(['date_csv'])->all();

    foreach ($recModels as $k => $v) {

        $manufacturerid = 0;
        $aManufacturersForReconciliation = [];

        $reconciliations[$k] = $v->getAttributes();
        $reconciliations[$k]['model'] = $v;

        if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)) {
            foreach ($config_reconciliation_search_keyphrases as $kk => $vv) {
                if (!empty($v["description_csv"]) && !empty($vv["search_keyphrase"])) {

                    $v_description_csv_UPPER = strtoupper($v["description_csv"]);
                    $vv_search_keyphrase_UPPER = strtoupper($vv["search_keyphrase"]);

                    $flag_config_search_keyphrase_found = false;
                    $vv_search_keyphrase_UPPER_arr = explode("<OR>", $vv_search_keyphrase_UPPER);

                    foreach ($vv_search_keyphrase_UPPER_arr as $vv_search_keyphrase_UPPER) {
                        $vv_search_keyphrase_UPPER = trim($vv_search_keyphrase_UPPER);
                        if (strpos($v_description_csv_UPPER, $vv_search_keyphrase_UPPER) !== false) {
                            $flag_config_search_keyphrase_found = true;
                            break;
                        }
                    }

                    if ($flag_config_search_keyphrase_found) {

                        $reconciliations[$k]["description_csv"] = str_replace($vv_search_keyphrase_UPPER, "<B>" . $vv_search_keyphrase_UPPER . "</B>", $v_description_csv_UPPER);
                        $reconciliations[$k]["config_search_keyphrase_found"] = "Y";

                        if ($tab == "expense_report") {
                            $config_reconciliation_search_keyphrases[$kk]["total_amount"] += $reconciliations[$k]["amount_csv"];

                            if (!empty($reconciliations[$k]["amount_csv_abs"])) {
                                $config_reconciliation_search_keyphrases[$kk]["total_amount_with_abs"] += $reconciliations[$k]["amount_csv_abs"];
                            }

                            $config_reconciliation_search_keyphrases[$kk]["found_records"][] = $reconciliations[$k];
                        }

                        break;
                    }
                }
            }
        }

    }
}

$location[] = array("Reconciliation", "");

if ($tab == "expense_report") {

    $expense_report_sum_total_amount = 0;
    $expense_report_sum_total_amount_with_abs = 0;

    if (!empty($config_reconciliation_search_keyphrases) && is_array($config_reconciliation_search_keyphrases)) {
        foreach ($config_reconciliation_search_keyphrases as $k => $v) {
            $expense_report_sum_total_amount += $v["total_amount"];

            if (!empty($v["total_amount_with_abs"])) {
                $expense_report_sum_total_amount_with_abs += $v["total_amount_with_abs"];
            }
        }
    }

    $smarty->assign("expense_report_sum_total_amount", $expense_report_sum_total_amount);
    $smarty->assign("expense_report_sum_total_amount_with_abs", $expense_report_sum_total_amount_with_abs);
}

if ($tab == "inventory") {
    $order_by = 'reportdate';
    $order_direction = 'desc';

    $o_direction = ($order_direction == 'desc') ? '-' : '';

    $qb = QueryBuilder::getInstance(Connection::getInstance());
    $sql = $qb
        ->setTypeSelect()
        ->from('xcart_cidev_daily_fba_stats')
        ->order([$o_direction . $order_by])
        ->toSQL();

    $cidev_daily_fba_stats = Connection::getInstance()->fetchAllAssociative($sql);

    $smarty->assign("cidev_daily_fba_stats", $cidev_daily_fba_stats);
}

$smarty->assign("tab", $tab);
$smarty->assign("config_reconciliation_search_keyphrases", $config_reconciliation_search_keyphrases);

$smarty->assign("search_prefilled", $search_data["reconciliation_tab_" . $tab]);

$manufacturers = func_query_hash("SELECT manufacturerid, manufacturer, code FROM $sql_tbl[manufacturers] ORDER BY manufacturer, orderby", "manufacturerid", false);
$smarty->assign('manufacturers', $manufacturers);

$smarty->assign("search_keyphrase_list", $search_keyphrase_list);
$smarty->assign("reconciliations", $reconciliations);
$smarty->assign("reconciliation_upload_info", $reconciliation_upload_info);
$smarty->assign("main", "reconciliation");
$smarty->assign("location", $location);
$smarty->assign("upload_max_filesize", ini_get("upload_max_filesize"));

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);

