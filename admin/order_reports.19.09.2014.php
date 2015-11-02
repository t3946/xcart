<?php /* ADDED: random:20341 [2010 Jul 29 14:46][Custom development (Accounting features for X-Cart orders management)] */ ?>
<?php
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2010 Ruslan R. Fazliev <rrf@rrf.ru>                      |
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
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2010           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: order_reports.php, v 1.0.0 2010/04/12 17:38:59 random Exp $
#

define("NUMBER_VARS", "posted_data[total_min],posted_data[total_max],posted_data[price_min],posted_data[price_max]");
define("ORDER_REPORTS", 1);
require "./auth.php";
require $xcart_dir."/include/security.php";

set_time_limit(86400);
ini_set("memory_limit", "500M");

x_session_register("search_data");

$smarty->assign("show_order_details", "Y");


if ($REQUEST_METHOD == "POST") {
	#
	# Update the session $search_data variable from $posted_data
	#
	if (!empty($posted_data)) {

/*
		if (!empty($StartMonth)) {
			$posted_data["start_date"] = mktime(0,0,0,$StartMonth,$StartDay,$StartYear);
			$posted_data["end_date"] = mktime(23,59,59,$EndMonth,$EndDay,$EndYear);
		}
*/
#
##
###
		if (!empty($posted_data["start_date"]) && !empty($posted_data["end_date"])){
			$start_date_arr = explode("/", $posted_data["start_date"]);
			$posted_data["start_date"] = mktime(0,0,0,$start_date_arr[0],$start_date_arr[1],$start_date_arr[2]);

                        $end_date_arr = explode("/", $posted_data["end_date"]);
                        $posted_data["end_date"] = mktime(23,59,59,$end_date_arr[0],$end_date_arr[1],$end_date_arr[2]);
		}
###
##
#

		$posted_data["report_mode"] = $mode;

		$search_data["order_reports"] = $posted_data;

	}

	func_header_location("order_reports.php?mode=report");
}

if ($mode == "report") {

	x_load('order');

	if (is_array($search_data["order_reports"])) {
		$data = $search_data["order_reports"];
	}	

	$search_condition = "";

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

#
##
###
	$search_condition .= " AND o.cb_status != 'A'";
###
##
#
	$orders = func_query("SELECT o.* FROM $sql_tbl[orders] AS o WHERE 1 $search_condition ORDER BY o.date");
	$manufacturers = array();

	if (!empty($orders)) {
		$data["total_accounting"] = array();
		$data["total"] = array();
		$data["total_margin"] = 0;
		for ($i=0;$i<=5;$i++) {
			$data["total_accounting"][$i] = array();
			foreach ($price_details_names as $dn) {
				$data["total_accounting"][$i][$dn] = 0;
				$data["totals"][$dn] = 0;
			}
		}

		foreach ($orders as $k => $v) {
			$orders[$k]["shipping_groups"] = func_get_shipping_groups($v["orderid"]);
			foreach ($orders[$k]["shipping_groups"] as $mid => $group) {

//func_print_r($group, $data);
//die();

				if (
		                    (!empty($data['manufacturers']) && !in_array($mid, $data['manufacturers'])) 
		                    || ($data['profit_margin_range'] == "margin_less_100" && $group['profit_margin'] == 100) 
//                                  || (empty($data['include_margin_100']) && $group['profit_margin'] == 100)
				    || ($group["acc_paymentid"] == "0")
//                		    || !in_array($group['cb_status'], array('P','R','O','H','A')) 
                		    || (!in_array($group['cb_status'], array('P','O','H','A')) && $data["cb_status"] != "R")
                		    || (!in_array($group['cb_status'], array('P','R','O','H','A')) && $data["cb_status"] == "R")
				    || ($data['profit_margin_range'] == "margin_less_1" && ($group['profit_margin'] > $data['profit_margin_range_less_1'] || $group['profit_margin'] == 100))
				    || ($data['profit_margin_range'] == "margin_1_2" && ($group['profit_margin'] < $data['profit_margin_range_1'] || $group['profit_margin'] > $data['profit_margin_range_2'] || $group['profit_margin'] == 100) )
		                ) {
					unset($orders[$k]["shipping_groups"][$mid]);
				} else {

					$manufacturers[$mid] = $group["code"];
			                $accounting_enabled = in_array($group['cb_status'], array('P','R','O','H','A'));
					foreach ($price_details_names as $dn) {
						if ($accounting_enabled) {
							for ($i=0;$i<=5;$i++) {
								$data["total_accounting"][$i][$dn] += $group["accounting"][$i][$dn];
							}
						}
						$data["total"][$dn] += $group["total"][$dn];
					}
				}
			}

			if ($accounting_enabled) {
				$data["total_margin"] = @price_format($data["total_accounting"][5]["net"]/$data["total_accounting"][0]["net"]*100);
			}

#
##
###
                        $data["real_net"] = $data["total_accounting"][0]["net"] + $data["total_accounting"][4]["net"] - $data["total_accounting"][3]["net"];
			if ($data["real_net"] > 0){
	                        $data["real_pm"] = (($data["total_accounting"][0]["net"] + $data["total_accounting"][4]["net"] - $data["total_accounting"][3]["net"] - $data["total_accounting"][1]["net"] - $data["total_accounting"][2]["net"])/($data["real_net"]))*100;
			}
###
##
#

			if (empty($orders[$k]["shipping_groups"])) {
				unset($orders[$k]);
				continue;
			}
			$orders[$k]["s_countryname"] = func_get_country($v["s_country"]);
			$orders[$k]["date"] += $config["Appearance"]["timezone_offset"];
		}

		$smarty->assign("manufacturers", $manufacturers);
		$smarty->assign("orders", $orders);
		$smarty->assign("mode", $mode);
		$smarty->assign("data", $data);

		$all_processors = func_query_hash("SELECT paymentid, payment_method, acc_per_trans, acc_percent FROM $sql_tbl[payment_methods] WHERE acc_proc='Y' ORDER BY orderby","paymentid", false);
		$smarty->assign("all_processors", $all_processors);

		if ($data["report_mode"] == "generate_time_to_dispatch") {

			$min_time_to_dispatch = 0;
			$max_time_to_dispatch = 0;
			$count_found_orders = 0;
			$total_time_to_dispatch_in_hours = 0;

			if (!empty($orders) && is_array($orders)){
				foreach ($orders as $k => $v){
					if (!empty($v["shipping_groups"]) && is_array($v["shipping_groups"])){
						foreach ($v["shipping_groups"] as $kk => $vv){
							$current_time_to_dispatch = $vv["time_to_dispatch"];

							if (!empty($current_time_to_dispatch)){

								if (empty($min_time_to_dispatch)){
									$min_time_to_dispatch = $current_time_to_dispatch;
								}

								if ($min_time_to_dispatch > $current_time_to_dispatch){
									$min_time_to_dispatch = $current_time_to_dispatch;
								}

								if ($current_time_to_dispatch > $max_time_to_dispatch){
									$max_time_to_dispatch = $current_time_to_dispatch;
								}

								$count_found_orders++;

								$current_time_to_dispatch_in_hours = ceil($current_time_to_dispatch / (60*60));
								
								$total_time_to_dispatch_in_hours += $current_time_to_dispatch_in_hours;

								if (!empty($DATA_dispatch[$current_time_to_dispatch_in_hours])){
									$DATA_dispatch[$current_time_to_dispatch_in_hours]++;
								} else {
									$DATA_dispatch[$current_time_to_dispatch_in_hours] = 1;
								}
							}
						}
					}
				}

###
				$total_sigma = 0;
				$sigma = 0;

				if (!empty($count_found_orders)){
	                                foreach ($orders as $k => $v){
        	                                if (!empty($v["shipping_groups"]) && is_array($v["shipping_groups"])){
                	                                foreach ($v["shipping_groups"] as $kk => $vv){
                        	                                $current_time_to_dispatch = $vv["time_to_dispatch"];
	
        	                                                if (!empty($current_time_to_dispatch)){
	
        	                                                        $current_time_to_dispatch_in_hours = ceil($current_time_to_dispatch / (60*60));
									$current_sigma = ($current_time_to_dispatch_in_hours - $total_time_to_dispatch_in_hours/$count_found_orders);
									$current_sigma *= $current_sigma;
									$total_sigma += $current_sigma;
                                        	                }
                                                	}
	                                        }
        	                        }

					$average = $total_time_to_dispatch_in_hours/$count_found_orders;

					$sigma = $total_sigma/$count_found_orders;
					$sigma = sqrt($sigma);
					$sigma = ceil($sigma);
				}
//func_print_r($sigma);
//die();
###


			}

//func_print_r($DATA_dispatch);
//die();

//func_print_r($min_time_to_dispatch, $max_time_to_dispatch, $count_found_orders);

//func_print_r($orders);
			if (!empty($min_time_to_dispatch) && !empty($max_time_to_dispatch)){

				$min_time_to_dispatch_in_hours = ceil($min_time_to_dispatch / (60*60)) - 1;
				$max_time_to_dispatch_in_hours = ceil($max_time_to_dispatch / (60*60));


######################################################################################

			// Массив $DATA["x"] содержит подписи по оси "X"

				$DATA=Array();
//				for ($i=$min_time_to_dispatch_in_hours;$i<=$max_time_to_dispatch_in_hours;$i++) {
				for ($i=0;$i<=$max_time_to_dispatch_in_hours;$i++) {

				    $orders_count = $DATA_dispatch[$i];
				    if (empty($orders_count)) $orders_count = 0;

				    $DATA[0][]=$orders_count;

//				    $DATA[1][]= ($max_time_to_dispatch_in_hours-$min_time_to_dispatch_in_hours)/$count_found_orders;
				    $DATA[1][]= $average;



				    $DATA[2][]=$average - $sigma;
				    $DATA[3][]=$average + $sigma;
//				    $DATA[1][]=rand(0,100)/2;
//				    $DATA[2][]=rand(0,100)/3;
				    $DATA["x"][]=$i;
				}


/*
$DATA=Array();
for ($i=0;$i<20;$i++) {
    $DATA[0][]=rand(0,100);
    $DATA[1][]=rand(0,100)/2;
    $DATA[2][]=rand(0,100)/3;
    $DATA["x"][]=$i;
    }
*/

//func_print_r($DATA, $orders_count, $min_time_to_dispatch_in_hours, $max_time_to_dispatch_in_hours, $count_found_orders);
//die();

// Задаем изменяемые значения #######################################

// Размер изображения

$W=2000;
$H=1000;

// Отступы
$MB=20;  // Нижний
$ML=8;   // Левый 
$M=5;    // Верхний и правый отступы.
         // Они меньше, так как там нет текста

// Ширина одного символа
$LW=imagefontwidth(2);

// Подсчитаем количество элементов (точек) на графике
$count=count($DATA[0]);

/*
if (count($DATA[1])>$count) $count=count($DATA[1]);
if (count($DATA[2])>$count) $count=count($DATA[2]);
*/

if ($count==0) $count=1;

// Подсчитаем максимальное значение
$max=0;

for ($i=0;$i<$count;$i++) {
    $max=$max<$DATA[0][$i]?$DATA[0][$i]:$max;
//    $max=$max<$DATA[1][$i]?$DATA[1][$i]:$max;
//    $max=$max<$DATA[2][$i]?$DATA[2][$i]:$max;
}

//func_print_r($max);
//die();

// Увеличим максимальное значение на 10% (для того, чтобы столбик
// соответствующий максимальному значение не упирался в в границу
// графика
$orig_max = $max;
$max=intval($max+($max/10));

// Количество подписей и горизонтальных линий
// сетки по оси Y.

$county=10;

#
##
###
if ($max < 10){
	$county = $max;
}
else {
	for ($i=0;$i<100;$i++){
		$max += $i;

		if (($max % $county) == 0){
			break;
		}
	}
}
###
##
#


// Работа с изображением ############################################

// Создадим изображение
$im=imagecreate($W,$H);

// Цвет фона (белый)
$bg[0]=imagecolorallocate($im,255,255,255);

// Цвет задней грани графика (светло-серый)
$bg[1]=imagecolorallocate($im,231,231,231);

// Цвет левой грани графика (серый)
$bg[2]=imagecolorallocate($im,212,212,212);

// Цвет сетки (серый, темнее)
$c=imagecolorallocate($im,184,184,184);

// Цвет текста (темно-серый)
$text=imagecolorallocate($im,136,136,136);
$text_b=imagecolorallocate($im,0,0,0);

// Цвета для линий графиков
$bar[3]=imagecolorallocate($im,255,0,0);
$bar[2]=imagecolorallocate($im,255,0,0);
$bar[0]=imagecolorallocate($im,0,0,0);
$bar[1]=imagecolorallocate($im,0,0,255);

$text_width=0;
// Вывод подписей по оси Y
for ($i=1;$i<=$county;$i++) {
    $strl=strlen(($max/$county)*$i)*$LW;
    if ($strl>$text_width) $text_width=$strl;
    }

// Подравняем левую границу с учетом ширины подписей по оси Y
$ML+=$text_width;

// Посчитаем реальные размеры графика (за вычетом подписей и
// отступов)
$RW=$W-$ML-$M;
$RH=$H-$MB-$M;

// Посчитаем координаты нуля
$X0=$ML;
$Y0=$H-$MB;

$step=$RH/$county;

// Вывод главной рамки графика
imagefilledrectangle($im, $X0, $Y0-$RH, $X0+$RW, $Y0, $bg[1]);
imagerectangle($im, $X0, $Y0, $X0+$RW, $Y0-$RH, $c);

// Вывод сетки по оси Y
for ($i=1;$i<=$county;$i++) {
    $y=$Y0-$step*$i;
    imageline($im,$X0,$y,$X0+$RW,$y,$c);
    imageline($im,$X0,$y,$X0-($ML-$text_width)/4,$y,$text);
    }

//func_print_r($count, $RW, $Y0, $X0, $c);
//die();

// Вывод сетки по оси X
// Вывод изменяемой сетки
for ($i=0;$i<$count;$i++) {
    imageline($im,$X0+$i*($RW/$count) + ($RW/$count)/2,$Y0,$X0+$i*($RW/$count) + ($RW/$count)/2,$Y0,$c);
    imageline($im,$X0+$i*($RW/$count) + ($RW/$count)/2,$Y0,$X0+$i*($RW/$count) + ($RW/$count)/2,$Y0-$RH,$c);

#
##
###
	if ($i == 0){
		$first_hour_line_x = $X0+$i*($RW/$count) + ($RW/$count)/2; 
	}
        if ($i == 1){
                $second_hour_line_x = $X0+$i*($RW/$count) + ($RW/$count)/2;
        }
###
##
#

}

// Вывод линий графика
$dx=($RW/$count)/2;

$pi=$Y0-($RH/$max*$DATA[0][0]);
$po=$Y0-($RH/$max*$DATA[1][0]);
$pu=$Y0-($RH/$max*$DATA[2][0]);
$px=intval($X0+$dx);

for ($i=1;$i<$count;$i++) {
    $x=intval($X0+$i*($RW/$count)+$dx);

    $y=$Y0-($RH/$max*$DATA[0][$i]);
    imageline($im,$px,$pi,$x,$y,$bar[0]);
    $pi=$y;

/*
    $y=$Y0-($RH/$max*$DATA[1][$i]);
    imageline($im,$px,$po,$x,$y,$bar[1]);
    $po=$y;
*/

    $x_average1 = intval( $X0 + ($RW/$count)/2 + ($second_hour_line_x - $first_hour_line_x)*$DATA[1][0]  );
    $y=$Y0-($RH/$max*$i);
    imageline($im,$x_average1,$Y0,$x_average1,$y,$bar[1]);

    $x_average2 = intval( $X0 + ($RW/$count)/2 + ($second_hour_line_x - $first_hour_line_x)*$DATA[2][0]  );
    imageline($im,$x_average2,$Y0,$x_average2,$y,$bar[2]);

    $x_average3 = intval( $X0 + ($RW/$count)/2 + ($second_hour_line_x - $first_hour_line_x)*$DATA[3][0]  );
    imageline($im,$x_average3,$Y0,$x_average3,$y,$bar[2]);


/*
    $y=$Y0-($RH/$max*$DATA[2][$i]);
    imageline($im,$px,$pu,$x,$y,$bar[2]);
    $pu=$y;
*/

    $px=$x;
}

//func_print_r($X0);
//die();

// Уменьшение и пересчет координат
$ML-=$text_width;

// Вывод подписей по оси Y
for ($i=1;$i<=$county;$i++) {
    $str=($max/$county)*$i;
    imagestring($im,2, $X0-strlen($str)*$LW-$ML/4-2,$Y0-$step*$i-
                       imagefontheight(2)/2,$str,$text);
    }


#
##
###
$average = sprintf("%.1f", round((double)$average+0.00000000001, 1));
imageline($im,1780,58,1870,58,$bar[1]);
imagestring($im, 4, 1880, 50, "average=".$average, $text_b);

$sigma = sprintf("%.1f", round((double)$sigma+0.00000000001, 1));
imageline($im,1780,88,1870,88,$bar[2]);
imagestring($im, 4, 1880, 80, "sigma=".$sigma, $text_b);
###
##
#

//func_print_r($text);
//die();

// Вывод подписей по оси X
$prev=100000;
$twidth=$LW*strlen($DATA["x"][0])+6;
$i=$X0+$RW;

while ($i>$X0) {
    if ($prev-$twidth>$i) {
        $drawx=$i-($RW/$count)/2;
        if ($drawx>$X0) {
            $str=$DATA["x"][round(($i-$X0)/($RW/$count))-1];
            imageline($im,$drawx,$Y0,$i-($RW/$count)/2,$Y0+5,$text);
            imagestring($im,2, $drawx-(strlen($str)*$LW)/2, $Y0+7,$str,$text);
            }
        $prev=$i;
        }
    $i-=$RW/$count;
    }

header("Content-Type: image/png");

// Генерация изображения
ImagePNG($im);

imagedestroy($im);


######################################################################################
			}
die("End");
		}
		elseif ($data["report_mode"] != "csv") {
			func_display("main/order_report_html.tpl",$smarty);
		} else {
			$smarty->assign("delimiter", ";");
			$fn = 'sales_report_'.date('Y-m-d').'.csv';
			header('Content-type: text/csv; name="'.$fn.'"');
			header("Content-Disposition: attachment; filename=".$fn);
			func_display("main/order_report_csv.tpl",$smarty);
		}
		exit;
	}

}

if (!empty($search_data["order_reports"])){
 $smarty->assign("search_prefilled", @$search_data["order_reports"]);
}

$location[] = array(func_get_langvar_by_name("lbl_order_reports"), "order_reports.php");

$manufacturers = func_query_hash("SELECT manufacturerid, manufacturer FROM $sql_tbl[manufacturers] WHERE avail='Y' ORDER BY manufacturer, orderby","manufacturerid",false);
if (!empty($search_data["order_reports"]["manufacturers"])) {
	foreach ($search_data["order_reports"]["manufacturers"] as $key) {
		$manufacturers[$key]["selected"] = true;
	}
}
$smarty->assign("manufacturers", $manufacturers);

$smarty->assign("main","order_reports");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
