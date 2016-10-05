<?php
require "./auth.php";
require $xcart_dir."/include/security.php";

$location[] = array("Operators activity report", "");

if ($REQUEST_METHOD=="POST") {

}

$smarty->assign("main", "operators_activity_reports");


if ($REQUEST_METHOD == "POST") {

    if (!empty($posted_data)) {

        if (!empty($posted_data["start_date"]) && !empty($posted_data["end_date"])){
            $start_date_arr = explode("/", $posted_data["start_date"]);
            $posted_data["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);

            $end_date_arr = explode("/", $posted_data["end_date"]);
            $posted_data["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);
        }

        $data = $posted_data;
    }

    if (!empty($data["date_period"])) {
        if ($data["date_period"] == "C") {
            # ...orders within specified period
            $start_date = $data["start_date"] - $config["Appearance"]["timezone_offset"];
            $end_date = $data["end_date"] - $config["Appearance"]["timezone_offset"];
        }
        else {
            # ...orders within this month
            $end_date = time() + $config["Appearance"]["timezone_offset"];
            if ($data["date_period"] == "M") {
                $start_date = mktime(0,0,0,date("n",$end_date),1,date("Y",$end_date));
            }
            elseif ($data["date_period"] == "D") {
                $start_date = mktime(0,0,0,date("n",$end_date),date("j",$end_date),date("Y",$end_date));
            }
            elseif ($data["date_period"] == "W") {
                $first_weekday = $end_date - (date("w",$end_date) * 86400);
                $start_date = mktime(0,0,0,date("n",$first_weekday),date("j",$first_weekday),date("Y",$first_weekday));
            }

            $data["end_date"] = $end_date = time();
            $data["start_date"] = $start_date; // already with timezone offset
            $start_date -= $config["Appearance"]["timezone_offset"];
        }

        $search_condition .= " AND o.date>='".($start_date)."'";
        $search_condition .= " AND o.date<='".($end_date)."'";
    }


    if (!empty($posted_data["operators"])) {
        $login_condition .= " AND OL.login IN ('".implode("','", $posted_data["operators"])."')";
    }

    $all_actions = func_query("Select CONCAT (o.order_prefix, o.orderid) as order_number, otrs_ticket, FROM_UNIXTIME(o.date) as orderdate, group_concat(xo1.name) as orderstatus, xc.firstname, OL.*, FROM_UNIXTIME(OL.date) actiondate
        FROM $sql_tbl[orders] o
        LEFT JOIN $sql_tbl[order_logs] OL ON OL.orderid = o.orderid $login_condition
       INNER JOIN $sql_tbl[order_groups] xo ON xo.orderid = o.orderid
       INNER JOIN $sql_tbl[order_statuses] xo1 ON xo.cb_status = xo1.code AND xo1.type = 'CB'
       INNER JOIN $sql_tbl[customers] xc ON OL.login = xc.login
      where OL.id is not NULL  $search_condition AND usertype='A' AND status = 'Y'
      group by o.orderid, OL.id
      order by o.date, OL.date ASC
      ");

    $firstlevelGroup = array();
    $secondLevelGroup = array();
    $LevelGroup3 = array();
    if (isset($all_actions) && is_array($all_actions) && count($all_actions) != 0) {
        foreach ($all_actions as $actionrow) {
            $firstlevelGroup[$actionrow["firstname"]]['actioncnt']++;
            if (isset($firstlevelGroup[$actionrow["firstname"]]['orders']) && is_array($firstlevelGroup[$actionrow["firstname"]]['orders'])) {
                if (!in_array($actionrow["orderid"], $firstlevelGroup[$actionrow["firstname"]]['orders'])) $firstlevelGroup[$actionrow["firstname"]]['orders'][] = $actionrow["orderid"];
            } else $firstlevelGroup[$actionrow["firstname"]]['orders'][] = $actionrow["orderid"];

            $secondLevelGroup[$actionrow["firstname"]]["orders"][$actionrow["orderid"]]["actioncount"]++;
            $secondLevelGroup[$actionrow["firstname"]]["orders"][$actionrow["orderid"]]["orderdate"] = $actionrow["orderdate"];
            $secondLevelGroup[$actionrow["firstname"]]["orders"][$actionrow["orderid"]]["orderstatus"] = $actionrow["orderstatus"];
            $secondLevelGroup[$actionrow["firstname"]]["orders"][$actionrow["orderid"]]["ordernumberwithprefix"] = $actionrow["order_number"];
            $secondLevelGroup[$actionrow["firstname"]]["orders"][$actionrow["orderid"]]["otrsticket"] = $actionrow["otrs_ticket"];
            $LevelGroup3[$actionrow["firstname"]][$actionrow["orderid"]][] = array("action_date" => $actionrow["actiondate"], "action_type" => $actionrow["type"], "log" => $actionrow["log"]);
        }


        $firstlevelGroupData = array();
        foreach ($firstlevelGroup as $login => $dataFirstLevel) {
            $firstlevelGroupData[] = array("login" => $login, "orderscount" => count($dataFirstLevel["orders"]), "actioncount" => $dataFirstLevel["actioncnt"]);
        }

        $secondlevelGroupData = array();
        foreach ($secondLevelGroup as $login => $dataS) {
            foreach ($dataS["orders"] as $orderid => $dataSecond)
                $secondlevelGroupData[$login][] = array("ordernumber" => $orderid, "orderdate" => $dataSecond["orderdate"], "orderstatus" => $dataSecond["orderstatus"], "actioncount" => $dataSecond["actioncount"], "ordernumberwithprefix" => $dataSecond["ordernumberwithprefix"], "otrsticket" => $dataSecond["otrsticket"]);
        }
    }

    $type_names = array (
        "C" => "Customer",
        "S" => "Customer service",
        "X" => "System",
        "P" => "Payment",
        "PP" => "PayPal Payment"
    );

    $smarty->assign("type_names", $type_names);
    $smarty->assign("data", $data);
    $smarty->assign("mode", "report");
    $smarty->assign("firstlevelGroup", $firstlevelGroupData);
    $smarty->assign("secondlevelGroup", $secondlevelGroupData);
    $smarty->assign("LevelGroup3", $LevelGroup3);
    $smarty->assign("all_actions", $all_actions);


}

$operators = func_query_hash("SELECT usertype, login, status, activity, firstname FROM $sql_tbl[customers] WHERE usertype='A' AND status = 'Y' ORDER BY firstname", "login",true);
$smarty->assign("operators", $operators);


# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";

func_display("admin/home.tpl",$smarty);
