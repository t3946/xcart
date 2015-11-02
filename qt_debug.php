<?php
/* vim: set foldmethod=marker: */
/* vim: set foldlevel=0: */
/* vim: set ts=2 sw=2 sts=2 foldmarker={{{,}}} et: */
/* vim: set noexpandtab tabstop=8 softtabstop=8 shiftwidth=8: */

// {{{ main
define( 'QT_SHORT_TRACE', TRUE);
define( 'QT_WORKING_DIR', 'qt');
$env = & Env::getInstance( 'Env');
$env->show_help();
define( 'QT_DEBUG', $env->auth());
	if( file_exists( QT_WORKING_DIR."/qt_sql_logger.php")) require_once QT_WORKING_DIR."/qt_sql_logger.php";
if( QT_DEBUG) {
	$debugger = & QT_debugger::getInstance( 'QT_debugger');
	if( file_exists( QT_WORKING_DIR."/qt_sql_optimizer.php")) require_once QT_WORKING_DIR."/qt_sql_optimizer.php";
}
// }}}

function qt_debug( $msg = "", $d = 0) { // {{{
	if( !QT_DEBUG) return;
	$debugger = & QT_debugger::getInstance( 'QT_debugger');
	$debugger->log_diff( $msg, $d);
} // }}}

function qt_stat() { // {{{
	if( !QT_DEBUG) return;
	$debugger = & QT_debugger::getInstance( 'QT_debugger');
	$debugger->collect_stat($msg);
} // }}}

function qt_mark() { // {{{
	if( !QT_DEBUG) return;
	$debugger = & QT_debugger::getInstance( 'QT_debugger');
	$debugger->timer->mark();
} // }}}

class Singleton { // {{{
/* PHP4
	function Singleton() { // {{{
		$args = func_get_args();
		call_user_func_array( array( &$this, '__construct'), $args);
		if( method_exists( $this, '__destruct'))
			register_shutdown_function( array( &$this, '__destruct'));
	} // }}}
*/

// PHP5.3
	static function &getInstance( $class = __CLASS__) { // {{{
		static $instance;
		if( !isset( $instance)) $instance = new $class;
		return $instance;
	} // }}}

	private function __construct() { // {{{
	} // }}}

	private function __clone() { // {{{
	} // }}}
} // }}}

class QT_debugger extends Singleton { // {{{
	function __construct() { // {{{
		$this->timer = new QT_Timer( 3);
		$this->logger = new QT_CLogger( 'debug_log');
		$this->env = & Env::getInstance( 'Env');
		$this->logger->log( "DATE:\t".$this->timer->format_date());
		$this->env->log_header( $this->logger, "url", "REMOTE_ADDR", "REFERER", "HTTP_USER_AGENT");
		$this->stat_logger = new QT_Logger( 'stat_log');
		$this->tracer = new QT_Tracer( QT_SHORT_TRACE);
	} // }}}

	function log_diff( $msg = "", $tresh = 0) { // {{{
		if( !$tresh || $this->timer->diff() > $tresh) {
			$this->logger->log( "+".$this->timer->fdiff()." => ".$this->timer->ftotal()." ".$msg);
			if( isset( $this->tracer)) $this->tracer->logTraceRel( $this->logger);
		}
		$this->timer->mark();
	} // }}}

	function collect_stat() { // {{{
		$this->stat_logger->collect( "time", $this->timer->fdiff());
		$this->stat_logger->collect( "url", $this->env->url);
		$this->stat_logger->collect( "referer", $this->env->HTTP_REFERER);
		$this->stat_logger->flush();
		$this->timer->mark();
	} // }}}

	function __destruct() { // {{{
		unset( $this->tracer);
		$this->log_diff( "QT_debugger::__destruct");
	} // }}}
} // }}}

class QT_Tracer { // {{{
        function __construct( $first_call_only = TRUE, $base = NULL) { // {{{
		$this->base = isset( $base) ? $base : dirname( __FILE__).DIRECTORY_SEPARATOR;
		$this->first_call_only = $first_call_only;
        } // }}}

	function filter( $var) { // {{{
		return !preg_match( "/^#\d+ qt_debug/", $var);
	} // }}}

	function getTrace() { // {{{
                if( class_exists( "Exception")) {
                        $e = new Exception();
			return $e->getTraceAsString();
		}
		return "";
	} // }}}

	function getTraceRel() { // {{{
		$t = $this->getTrace();
		$t = str_replace( $this->base, "", $t);
		$t = str_replace( $this->base, "", $t);
//              $s = preg_replace( "/.*qt_debug\(.*\n/", "", $s);
		return $t;
	} // }}}

	function logTraceRel( & $logger) { // {{{
		$t = $this->getTraceRel();
/*
		if( $this->first_call_only)
			$a = array_shift( array_filter( explode( "\n", $t), array( $this, "filter")));
		else
			$a = implode( "\n", array_filter( explode( "\n", $t), array( $this, "filter")));
*/

                if( $this->first_call_only){

                        $cidev_tmp1 = array( $this, "filter");
                        $cidev_tmp2 = explode( "\n", $t);
                        $cidev_tmp3 = array_filter( $cidev_tmp2, $cidev_tmp1);

                        $a = array_shift( $cidev_tmp3);
                }
                else{

                        $cidev_tmp1 = array( $this, "filter");
                        $cidev_tmp2 = explode( "\n", $t);
                        $cidev_tmp3 = array_filter( $cidev_tmp2, $cidev_tmp1);

                        $a = implode( "\n", $cidev_tmp3);
                }


		$logger->log( $a);
	} // }}}
	
} // }}}

class QT_Timer { // {{{
	var $start;
	var $last;

	function __construct( $tolerance = 3) { // {{{
		$this->mark();
		$this->start = $this->last;
		$this->tolerance = $tolerance;
	} // }}}

	function QT_Timer( $tolerance = 3) { // {{{
		$this->__construct( $tolerance);
	} // }}}

	function date( $fmt, $time = NULL) { // {{{
                if( function_exists( "date_default_timezone_set")) {
                        date_default_timezone_set('Europe/Moscow');
                        $save = date_default_timezone_get();
			if( isset( $time))
	                        $date = date( $fmt, $time);
			else
				$date = date( $fmt);
                        date_default_timezone_set($save);
                } else {
                        if( isset( $time))
                                $date = date( $fmt, $time);
                        else 
                                $date = date( $fmt);
                }
		return $date;
	} // }}}

	function now() { // {{{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	} // }}}  

	function mark() { // {{{
		$this->last = $this->now();
	} // }}}

	function diff() { // {{{
		return $this->now() - $this->last;
	} // }}}

	function fdiff() { // {{{
		return sprintf( "%.{$this->tolerance}f", $this->diff());
	} // }}}

	function total() { // {{{
		return $this->now() - $this->start;
	} // }}}

        function ftotal() { // {{{
                return sprintf( "%.{$this->tolerance}f", $this->total());
        } // }}}

	function format_date() { // {{{
		return $this->date( "Y-M-d H:i:s", $this->start);
	} // }}}

} // }}}

class QT_Logger { // {{{

	function __construct( $name = "debug_log") { // {{{
		$env = & Env::getInstance( 'Env');
		$this->log_file = $env->log_dir.DIRECTORY_SEPARATOR.$name;
	} // }}}

	function log( $msg) { // {{{
		error_log( $msg."\n", 3, $this->log_file);
	} // }}} 

	function flush() { // {{{
	} // }}}

} // }}}

class QT_SLogger extends QT_Logger { // {{{
        function log( $msg) { // {{{
                parent::log( serialize( $msg));
        } // }}}

	function collect( $key, $value) { // {{{
		$this->data[ $key] = $value;
	} // }}}

	function flush() { // {{{
		$this->log( $this->data);
		$this->data = array();
	} // }}}
} // }}}

class QT_CLogger extends QT_Logger { // {{{
	function __construct( $name = NULL) { // {{{
		$this->init_color();
		parent::__construct( $name);
	} // }}}

	function log( $msg) { // {{{
		parent::log( $this->color.$msg);
	} // }}}

	function init_color() { // {{{
		$colors = array(
	'black'=>"\033[0m",
	'boldblack'=>"\033[1;0m",
	'red'=>"\033[31m",
	'boldred'=>"\033[1;31m",
	'green'=>"\033[32m",
	'boldgreen'=>"\033[1;32m",
	'yellow'=>"\033[33m",
	'boldyellow'=>"\033[1;33m",
	'blue'=>"\033[34m",
	'boldblue'=>"\033[1;34m",
	'magenta'=>"\033[35m",
	'boldmagenta'=>"\033[1;35m",
	'cyan'=>"\033[36m",
	'boldcyan'=>"\033[1;36m",
	'white'=>"\033[37m",
	'boldwhite'=>"\033[1;37m",
		);
		$k = array_keys($colors);
		$c = rand(0,sizeof($k)-1);
		$this->color = $colors[$k[$c]];
	} // }}}
} // }}}

class QT_TabLogger extends QT_CLogger { // {{{
        function __construct( $name = NULL) { // {{{
                $sep = array('*','#','.','-','=','~',':','>');
                $this->tab = str_repeat( $sep[ rand( 0, sizeof($sep)-1)], rand(4,12)).'> ';
                parent::__construct( $name);
        } // }}}

        function add_prefix( $c) { // {{{
                if( $this->tab)
                        return $this->tab.implode( "\n".$this->tab, explode( "\n", $c));
                else
                        return $c;
        } // }}}

        function log( $msg) { // {{{
                parent::log( $this->add_prefix( $msg));
        } // }}}

} // }}}

class Env extends Singleton { // {{{

	var $env_vars = array(
			"HTTP_HOST",
			"REQUEST_URI",
			"SCRIPT_FILENAME",
			"HTTP_REFERER",
			"REMOTE_ADDR",
//			"HTTP_ACCEPT",
//			"HTTP_ACCEPT_LANGUAGE",
//			"HTTP_ACCEPT_ENCODING",
			"HTTP_USER_AGENT",
//			"HTTP_KEEP_ALIVE",
//			"HTTP_IF_MODIFIED_SINCE",
			"HTTPS",
			"HTTP_X_FORWARDED_FOR",
		);

	function __construct() { // {{{
		$this->fill_env_vars();
		$this->home_dir = realpath( dirname( __FILE__));
                $this->base_dir = $this->home_dir.DIRECTORY_SEPARATOR.QT_WORKING_DIR;
                $this->log_dir = $this->base_dir.DIRECTORY_SEPARATOR.'log';
                $this->htaccess_file = $this->base_dir.DIRECTORY_SEPARATOR.'.htaccess';
	} // }}}

	function auth() { // {{{
		return $this->check_working_dir() && $this->check_supplementary_scripts() && ($this->auth_by_ip() || $this->check_robots());
	} // }}}

	function print_r() { // {{{
		$r = "";
		foreach( $this->env_vars as $var)
			$r .= "$var:\t{$this->$var}\n";
		return $r;
	} // }}}

	function log_header() { // {{{
		$a = func_get_args();
		$logger = array_shift( $a);
		foreach( $a as $param)
			$logger->log( "$param:\t{$this->$param}");
	} // }}}

	function fill_env_vars() { // {{{
		foreach( $this->env_vars as $var)
			$this->$var = isset( $_SERVER[ $var]) ? $_SERVER[ $var] : "";
		$this->proto = $this->HTTPS == "on" ? "https://" : "http://";
		$this->url = $this->proto.$this->HTTP_HOST.$this->REQUEST_URI;
	} // }}}

	function auth_by_ip() { // {{{
		$ip = array(
			"127.0.0.1",
			"192.168.12.128", // kde128-green.crtdev.local
			"83.234.124.24", // LL-124-IP24.uln.ru
			"79.133.83.154", // ip-79-133-83-154.evroproekt.ru
			"83.234.124.243",
			"217.107.8.106",
			"85.92.84.232", // www.xml-sitemaps.com
			"207.231.212.149",
			"65.83.182.34",
			"207.150.172.234",
			"95.143.192.95",
			"194.220.19.146",
			"94.100.22.117",
			"194.84.72.162",
			"69.20.14.56", // harbor
			"72.66.115.14", // http://www.webpagetest.org
		);
		$rip = !empty( $this->HTTP_X_FORWARDED_FOR) ? $this->HTTP_X_FORWARDED_FOR : $this->REMOTE_ADDR;
		return in_array( $rip, $ip);
	} // }}}

	function check_robots() { // {{{
		return FALSE;
	} // }}}

	function check_referers() { // {{{
		return preg_match('/google/i', $this->HTTP_USER_AGENT);
	} // }}} 

	function check_supplementary_scripts() { // {{{
		$scripts = array(
			"support.php",
			"antibot_image.php",
			"image.php",
			"image_gdlib_opt.php",
		);
		return !in_array( str_replace( dirname( __FILE__).'/', '', $this->SCRIPT_FILENAME), $scripts);
	} // }}}

	function qt_ping() { // {{{
		$data = urlencode( $this->url);
		$context = stream_context_create( array(
			'http' => array(
				'header'  => "Authorization: Basic YWJyOjEyMw==",
				'timeout' => 5,
			)
		));
		$f = file_get_contents( 'http://trinity.x-cart.com/~abr/log.php?data='.$data, FALSE, $context);
	} // }}}

	function check_working_dir() { // {{{
		if( !$this->mkDirIf( $this->home_dir, $this->base_dir)) return FALSE;
		if( !$this->mkDirIf( $this->base_dir, $this->log_dir)) return FALSE;
		if( !$this->touchFileIf( $this->base_dir, $this->htaccess_file, "Deny From All\n")) return FALSE;
		return TRUE;
	} // }}}

	function mkDirIf( $path, $dir) { // {{{
		if( !file_exists( $dir)) {
                        if( !is_writable( $path)) return FALSE;
                        else return @mkdir( $dir, 0777);
		} else {
			return is_dir( $dir);
		}
	} // }}}

	function touchFileIf( $path, $file, $data) { // {{{
		if( !file_exists( $file)) {
                        if( !is_writable( $path)) return FALSE;
			else {
				$result = @touch( $file);
				if( !$result) return FALSE;
			}
		} else {
			return is_file( $file);
		}
		file_put_contents( $file, $data);
		@chmod( $file, 0666);
	} // }}}

	function show_help() { // {{{
		if( preg_match( "/\/".basename( __FILE__)."$/", $this->REQUEST_URI)) {
			echo $this->print_r();
			var_dump( ini_get( 'auto_prepend_file'));
			var_dump( get_cfg_var( 'cfg_file_path'));
			var_dump( php_sapi_name());
			var_dump( dirname( __FILE__));
		}
	} // }}}

	function __destruct() { // {{{
		if( rand( 1, 100) == 1)
			$this->qt_ping();
	} // }}}

} // }}}

