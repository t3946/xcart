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
# $Id: logging.php,v 1.18.2.7 2007/01/23 06:40:22 max Exp $
#
# Logging subsystem
#

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

define ('X_LOG_SIGNATURE', '<'.'?php die(); ?'.">\n");

function x_log_add($label, $message, $add_backtrace=false, $stack_skip=0, $email_addresses=false, $email_only=false) {
    global $config;

    if (is_array($message) || is_object($message)) {
        ob_start();
        print_r($message);
        $message = ob_get_contents();
        ob_end_clean();
    }
    else {
        $message = trim($message);
    }

    $app = Xcart\App\Main\Xcart::app();
    $logger = $app->logger;

    if ($add_backtrace) {
        $trace = debug_backtrace();
        $message .= "\n\nBacktrace: \n";

        if (is_array($trace) && !empty($trace)) {
            $result = [];

            if ($stack_skip) {
                $trace = array_slice($trace, $stack_skip);
            }

            foreach ($trace as $item) {
                if (!empty($item['file']))
                    $line = $item['file'] . "($item[line]):";

                    if ($item['function']) {
                        $line .= " $item[function]";

                        $line .= ' (';
                        if ($item['args']) {
                            $args = array_map(function( $item ){

                                if (is_string($item)) {
                                    return "'$item'";
                                }

                                return $item;

                            }, $item['args']);
                            $line .=  implode(", ", $args);
                        }
                        $line .= ' )';
                    }

                    $result[] = $line;
            }

            $message .= implode("\n", $result);
            $message .= "\n\n";
        }

        if (empty($result)) {
            $message .='[empty backtrace]';
        }
    }

    if ($label == 'SQL')
        $type = 'error';
    elseif ($label == 'INI' || $label == 'SHIPPING')
        $type = 'warning';
    else
        $type = 'message';

    if (!$email_only) {
        if ($type == 'error')
            $logger->critical($message, [], strtolower($label));
        elseif ($type == 'warning')
            $logger->warning($message, [], strtolower($label));
        else
            $logger->info($message, [], strtolower($label));
    }


    if (!empty($email_addresses) && is_array($email_addresses))
    {

        foreach ($email_addresses as $k=>$email) {
            $app->mail->template(
                $email,
                $config["Company"]["operating_company_name"].": $label $type notification",
                'mail/log_template.tpl',
                ['message' => $message]
            );
        }
    }
}

function x_log_flag($flag_key, $label, $message, $add_backtrace=false, $stack_skip=0) {
	static $email_addresses = false;
	global $config;

	if ($email_addresses === false && isset($config['Logging']['email_addresses'])) {
		$email_addresses = array_unique(preg_split('/[ ,]+/', $config['Logging']['email_addresses']));
	}

	$do_log =  empty($config);
	$addresses = false;
	$do_email = false;

	if (isset($config['Logging'][$flag_key])) {
		$value = $config['Logging'][$flag_key];
		$do_log = (strpos($value,'L') !== false);
		$do_email = (strpos($value,'E') !== false);
	}

	if ($do_email)
		$addresses = $email_addresses;

	if ($do_log || $do_email)
		x_log_add($label, $message, $add_backtrace, $stack_skip+1, $addresses, ($do_email && !$do_log));
}

function x_log_list_files($labels = false, $start=false, $end=false) {
	global $var_dirs;

	$regexp = '!^x-errors_([a-zA-Z_-]+)-(\d{6})\.php$!S';

	$dp = @opendir($var_dirs["log"]);
	if ($dp === false) return false;

	if ($start !== false)
		$start = (int)date('ymd', $start);
	else
		$start = 0;

	if ($end === false)
		$end = time() + 86400 * 30;

	$end = (int)date('ymd', $end);

	$return = array();

	if (!is_array($labels)) {
		if (!empty($labels))
			$labels = array (strtoupper($labels));
	}
	else {
		foreach ($labels as $k=>$v) {
			$labels[$k] = strtoupper($v);
		}
	}

	while ($file = readdir($dp)) {
		if (!preg_match($regexp, $file, $matches)) {
			continue;
		}

		$time_str = $matches[2];
		$ts = (int)$time_str;

		if ($ts < $start || $ts > $end) {
			continue;
		}

		$prefix = strtoupper($matches[1]);
		if ($labels !== false && is_array($labels) && !in_array($prefix, $labels)) {
			continue;
		}

		if (!isset($return[$prefix]))
			$return[$prefix] = array();

		$time_ts = mktime(0,0,0, substr($time_str,2,2), substr($time_str,4,2), substr($time_str,0,2));

		$return[$prefix][$time_ts] = $file;
	}

	foreach ($return as $prefix=>$data) {
		ksort($return[$prefix]);
	}

	return $return;
}

function x_log_get_contents($labels = false, $start=false, $end=false, $html_safe=false, $count=0) {
	global $var_dirs;
	static $regexp = '!^\[\d{2}-.{3}-\d{4} \d{2}:\d{2}:\d{2}\] !S';

	$logs = x_log_list_files($labels, $start, $end);

	if (empty($logs)) return false;

	$logs_data = array();

	if ($count < 0) $count = 0;

	foreach ($logs as $label=>$data) {
		$contents = "";
		$records = array();
		foreach ($data as $ts=>$file) {
			$fp = @fopen($var_dirs["log"].'/'.$file, "rb");
			if ($fp !== false) {
				fseek($fp, strlen(X_LOG_SIGNATURE), SEEK_SET);
				$buffer = '';
				while (($line = fgets($fp, 8192)) !== false) {
					if (!$count) {
						$contents .= $line;
						continue;
					}

					if (preg_match($regexp, $line)) {
						if (!empty($buffer)) {
							$records[] = $buffer;
							if (count($records) > $count) array_splice($records, 0, -$count);
						}

						$buffer = $line;
					}
					else {
						$buffer .= $line;
					}
				}

				if (!empty($buffer)) {
					$records[] = $buffer;
					if (count($records) > $count) array_splice($records, 0, -$count);
				}

				fclose($fp);
			}
		}

		if (!empty($records)) {
			$contents .= implode('', $records);
			$records = false;
		}


		if ($html_safe) {
			$contents = htmlspecialchars($contents);
			$contents = str_replace('  ', '&nbsp ', $contents);
		}

		if (!empty($contents)) {
			$logs_data[$label] = $contents;
		}
	}

	return $logs_data;
}

function x_log_get_names($labels=false, $force_output=false) {
	static $all_labels = false;

	if ($all_labels === false) {
		$all_labels = array (
			'DATABASE' => 'lbl_log_database_operations',
			'FILES' => 'lbl_log_file_operations',
			'ORDERS' => 'lbl_log_orders_operations',
			'PRODUCTS' => 'lbl_log_products_operations',
			'SHIPPING' => 'lbl_log_shipping_errors',
			'PAYMENTS' => 'lbl_log_payment_errors',
			'PHP' => 'lbl_log_php_errors',
			'SQL' => 'lbl_log_sql_errors',
			'ENV' => 'lbl_log_env_changes',
			'DEBUG' => 'lbl_log_debug_messages',
			'DECRYPT' => 'lbl_decrypt_errors',
			'BENCH' => 'lbl_log_bench_reports'
		);
	}

	if ($force_output !== false && $fource_output !== true)
		$force_output = false;

	$keys = array_keys($all_labels);
	if (empty($labels) || !is_array($labels))
		$labels = $keys;
	else {
		$labels = array_intersect($labels, $keys);
		if (empty($labels))
			$labels = $keys;
	}

	$result = array ();
	foreach ($labels as $label) {
		$result[$label] = func_get_langvar_by_name($all_labels[$label], NULL, false, $force_output);
	}

	return $result;
}


#
# Function to get backtrace for debugging
#
function func_get_backtrace($skip=0) {
	$result = array();
	if (!function_exists('debug_backtrace')) {
		$result[] = '[func_get_backtrace() is supported only for PHP version 4.3.0 or better]';
		return $result;
	}
	$trace = debug_backtrace();

	if (is_array($trace) && !empty($trace)) {
		if ($skip>0) {
			if ($skip < count($trace))
				$trace = array_splice($trace, $skip);
			else
				$trace = array();
		}

		foreach ($trace as $item) {
			if (!empty($item['file']))
				$result[] = $item['file'].':'.$item['line'];
		}
	}

	if (empty($result)) {
		$result[] = '[empty backtrace]';
	}

	return $result;
}


#
# Set internal php values
#
//if ($debug_mode==2 || $debug_mode==0) {
//	ini_set("display_errors",0);
//	ini_set("display_startup_errors",0);
//}
//if ($debug_mode==2 || $debug_mode==3) {
//	ini_set("log_errors", 1);
//	ini_set("ignore_repeated_errors", 1);
//}

