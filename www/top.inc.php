<?php

if (!defined('XCART_START')) {

define('XCART_START',1);

define('XCART_START_TIME',microtime());
define('XCART_START_MEM', function_exists("memory_get_usage") ? memory_get_usage() : 0);

define('XC_DS', DIRECTORY_SEPARATOR);


#
# Save backtrace information regarding lines in which errors occur
#
define('LOG_WITH_BACKTRACE', false);

#
# Switching on the internal performance measurement mechanism
#
define('BENCH', false);

#
# Do not display the performance report
#
define('BENCH_SIMPLE', true);

#
# Show tracing
#
define('BENCH_BACKTRACE', false);

#
# Disable creation of binary files with results of performance tests
#
define('BENCH_BLOCK_SAVE_BIN', false);

#
# Write to log file only
#
define('BENCH_LOG_ONLY', false);

#
# Write summary counters to log file
#
define('BENCH_LOG_SUMMARY', false);

#
# Code execution time, threshold value (for logging)
#
define('BENCH_LOG_TIME_LIMIT', 0.05);

#
# A comma-separated list of measurable performance characteristics that you wish to be logged
#
define('BENCH_LOG_TYPE_LIMIT', "");

#
# Report type to be displayed:
# T - only total values
# F - full report
# A - advanced report
define('BENCH_DISPLAY_TYPE', "T");

#
# Code execution time, threshold value
#
define('BENCH_TIME_LIMIT', 0.05);

#
# Amount of memory being used, threshold value
#
define('BENCH_MEM_LIMIT', 0.1);

#
# Remove results of automatic variables registration then register_globals=on
#

foreach (get_defined_vars() as $__key => $__val) {
    if (defined('USE_TRUSTED_POST_VARIABLES') && $__key == "trusted_post_variables") {
        continue;
    }

    if (defined('XCART_INSTALL') && $__key == "module_definition") {
        continue;
    }

    if (!in_array($__key, array('GLOBALS', '_GET', '_POST', '_SERVER', '_ENV', '_COOKIE', '_FILES', '__key', '__val', 'HTTP_RAW_POST_DATA','_xhprof'))) {
        unset($$__key);
    }
}

unset($__key, $__val);

$bench_counts = $bench_profilier = array();
$__smarty_size = $bench_max_session = $bench_max_memory = 0;

#
# Directories structure definitions
#

#
# Real path to the directory where X-Cart is installed
# If you have problems with __FILE__ constant definition on your server
# you can specify path directly. For example:
# $xcart_dir = '/home/user/public_html/xcart';
#
$xcart_dir = realpath(dirname(__FILE__));
if (substr($xcart_dir, -1) == DIRECTORY_SEPARATOR)
    $xcart_dir = substr($xcart_dir, 0, -1);

$runtime_dir = $xcart_dir . "/../app/runtime/";

# Directories location definition
# Examples:
# Customer's scripts are placed into the X-Cart subdirectory:
# 	define ('DIR_CUSTOMER', '/<name_of_directory>');
# 	define ('DIR_CUSTOMER', '/customer');
# 	define ('DIR_ADMIN', '/admin');
# 	define ('DIR_ADMIN', '/service_area/administration');
#
# (!) Customer's scripts are placed into the root X-Cart directory:
# 	define ('DIR_CUSTOMER', '');
#
define ('DIR_CUSTOMER', '');
define ('DIR_ADMIN', '/admin');
define ('DIR_PROVIDER', '/provider');
define ('DIR_VERIFICATOR', '/verificator');
define ('DIR_PARTNER', '/partner');

#
# Note: DIR_PARTNER is valid only for installed X-Affiliate module
#
define('XC_TIME', time());

define('X_PAYMENT_TRANS_ALREADY_CAPTURED', 1);
define('X_PAYMENT_TRANS_ALREADY_VOIDED', 2);
define('X_PAYMENT_TRANS_ALREADY_REFUNDED', 4);
define('X_PAYMENT_TRANS_ALREADY_ACCEPTED', 8);
define('X_PAYMENT_TRANS_ALREADY_DECLINED', 16);

define('PAYMENT_NEW_STATUS', 1);
define('PAYMENT_AUTH_STATUS', 2);
define('PAYMENT_DECLINED_STATUS', 3);
define('PAYMENT_CHARGED_STATUS', 4);

define('X_USE_PAYPAL_FLOW', true);

}
require_once $xcart_dir . "/../app/include/vendors/autoload.php";
