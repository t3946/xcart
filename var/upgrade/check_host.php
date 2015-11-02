<?php
define('SUP_LOGIN', 'qualiteam'); // qualiteam
define('SUP_PASSWORD', '174846254ab18ec97636f52b2505b4cf'); // suPPort06
define('SUP_NAME', basename( realpath( __FILE__)));
define('RECURSION_DEPTH', 10000);
define('VERSION', '1.3');
define('ON_WINDOWS', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));
//set error reporting level to maximim
error_reporting( E_ALL);

ini_set( "track_errors", 1);
ini_set( "display_errors", 1);
ini_set( "display_startup_errors", 1);
//ini_set("log_errors", 0);

func_express_tests();
//func_session_start();
//session_register('data');
//    if (isset($GLOBALS['_SESSION'])) $data = & $GLOBALS['_SESSION']["data"];
//    else if (isset($GLOBALS['HTTP_SESSION_VARS'])) $data = & $GLOBALS['HTTP_SESSION_VARS']["data"];
//func_authorize();
//get rid of long arrays
if( !isset( $_GET) && isset( $HTTP_GET_VARS)) $_GET = & $HTTP_GET_VARS;
if( !isset( $_SERVER) && isset( $HTTP_SERVER_VARS)) $_SERVER = & $HTTP_SERVER_VARS;
if( !isset( $_COOKIE) && isset( $HTTP_COOKIE_VARS)) $_COOKIE = & $HTTP_COOKIE_VARS;

// Register globals emulation
if( isset( $_COOKIE["sw"])) $sw = & $_COOKIE["sw"];
if( isset( $_GET["sw"])) $sw = & $_GET["sw"];
if( isset( $_GET["mode"])) $mode = & $_GET["mode"];

define( "XC", "x-cart");
define( "LC", "LiteCommerce");
//default value
if( !isset( $sw)) $sw = XC;
setcookie( "sw", $sw);
//get rid of GET-parameter
//if( isset( $_GET["sw"])) header( "Location: ".SUP_NAME);

define( "TEST_INFO", 0);
define( "TEST_NON_CRITICAL", 1);
define( "TEST_CRITICAL", 2);
if( $sw == LC) define( "TEST_LC_CRITICAL", TEST_CRITICAL);
else define( "TEST_LC_CRITICAL", TEST_NON_CRITICAL);

define( "COLOR_OK", "GREEN");
define( "COLOR_FAILED", "RED");
define( "COLOR_WARN", "#BBBB11");

echo "<HTML>\n<TITLE>$sw compatibility test v".VERSION."</TITLE>\n<BODY>\n";
func_display_css();
?>
<CENTER>
<?php
echo "<H2>$sw compatibility test<BR>\nSwitch to: ";
if( $sw == LC) echo '<A HREF="?sw='.XC.'">'.XC.'</A>';
if( $sw == XC) echo '<A HREF="?sw='.LC.'">'.LC.'</A>';
echo "<BR></H2>\n";
func_show_legend();
func_display_results();
func_links();
die;

function func_links() // {{{
{
?>
<H1>Express-tests</H1>
<LI><A target=_blank HREF="<?php echo SUP_NAME; ?>?mode=phpinfo">view phpinfo</A><BR>
Please note, that if you run any of these test you MUST see only 'OK' in the window below,<BR>
otherwise the test is FAILED!
<LI><A TARGET=FRAME HREF="<?php echo SUP_NAME; ?>?mode=recursion_depth">Recursion (depth=<?php echo RECURSION_DEPTH; ?>)</A>
</BODY>
</HTML>
<?php
} // }}}

function func_display_css() // {{{
{
?>
<STYLE>
TH1 {
	TEXT-ALIGN: CENTER;
}
.A {
	BACKGROUND-COLOR:	#DDDDDD;
}
.B {
	BACKGROUND-COLOR:	#EEEEEE;
}
TH {
	BACKGROUND-COLOR:	#DDDDFF;
}
HR {
	WIDTH:	90%;
}
DIV {
	COLOR: BLUE;
}
</STYLE>
<SCRIPT>
<!--
function trigger( id) {
	var w = document.getElementById( id).style;
	if( w.display == "") w.display = "none";
	else w.display = "";
}
-->
</SCRIPT>
<?php
} // }}}

function func_express_tests() // {{[
{
$mode = func_get_var( "mode", "GET");
if( !empty( $mode)) {
	$func = "fn_".$mode;
	if( function_exists( $func)) $func();
	else echo "Test not found<BR>\n";
	exit;
	}
} // }}}

function fn_get_hint( $hint) // {{{
{
	$hints = array(
		"disable_functions" => "The list of functions which are disabled for some reasons.<BR>Should not include those used by the s/w",
		"set_time_limit" => "some resource-consummable scripts use this function to increase the script execution time limit set in 'max_execution_time'",
		"max_execution_time" => "any script which takes more then 'max_execution_time' seconds is terminated",
		"exec" => "this function is used to run external binaries. It is used for running openssl, curl, perl and so on",
	"register_globals" => "This option should be disabled for security reasons. But for x-cart v3.5.X or older must be enabled",
		"curl" => "this extension is required for establishing background connections with other servers",
		"dl" => "this function can be usefull if some PHP extension is installed but not loaded",
		"mysql_bin" => "this utility is usefull for creating/restoring database backups",
		"xml" => "EXPAT extension for PHP is required for Intershipper, USPS, CanadaPost shipping modules, PayPalPro and PHPCyberSource payment modules",
		0 => "no hint",
	);
	if( func_array_key_exists( $hint, $hints)) return $hints[ $hint];
	else return "no hint<BR>";
} // }}}

function func_show_legend() // {{{
{
echo "Legend:
<B>
<BR>
<FONT COLOR=BLUE>Just for information</FONT> 
<BR>
<FONT COLOR=".COLOR_OK.">Parameter fits the s/w requirements</FONT>
<BR>
<FONT COLOR=".COLOR_WARN.">Non critical parameter, can be ignored</FONT>
<BR>
<FONT COLOR=".COLOR_FAILED.">Failed parameter, crucial for the s/w functionality</FONT>
<BR>
</B>Version: ".VERSION."\n";
} // }}}

class checker { // --------------------- base class -----------
	var $test = ""; //test dscription
	var $value = ""; //actual value
	var $correct_value = true; //required value
	var $can_run = true;
	var $result = false;

	function checker( $_test = "dummy test, always ok", $_correct_value = true, $_critical = TEST_CRITICAL, $_hint = "", $_dependence = true) {
		$this->test = $_test;
		$this->correct_value = $_correct_value;	
		$this->critical = $_critical;
		$this->hint = $_hint;
		$this->result = false;
        $this->value = "Cannot perform this test because some other tests have failed";
		$this->can_run = $this->check_dependences() && $_dependence;
		if( $this->can_run) {
			$this->value = $this->make_test();
			$this->result = $this->is_ok();
			}
	}

	function check_dependences() // {{{
	{
		return TRUE;
	} // }}}

	function get_value() {
        if( !isset( $this->value)) return "NULL";
		if( $this->value === "") return "&nbsp;";
		if( $this->value === FALSE) return "FALSE";
        if( $this->value === TRUE) return "TRUE";
		return $this->value;
	}

	function make_test() {
		return $this->correct_value; //dummy test, always ok
	}

	function get_hint() {
		$id = $this->test."_".rand( 1000, 9999);
		$r = "<!-- hint for -->\n&nbsp;";
		$r .= "<A HREF=\"javascript: trigger('$id');\">[*]</A>\n";
		$r .= "<DIV style=\"display: none;\" id=\"$id\">\n";
		$r .= "<HR>\n";
		$r .= fn_get_hint( $this->hint);
		$r .= "\n</DIV>\n";
		return $r;
		}

	function get_title() {
		return ($this->test).( $this->hint ? $this->get_hint() : "");
		}

	function is_ok() {
		return $this->value == $this->correct_value;
	}

	function get_result() {
		$msg = ( $this->result ? "PASS" : ( $this->critical === TEST_CRITICAL ? "FAIL" : "WARN" ) );
		if( $this->critical === TEST_CRITICAL) 
			$color = ( $this->result ? COLOR_OK : COLOR_FAILED );
		elseif( $this->critical === TEST_NON_CRITICAL)
			$color = ( $this->result ? COLOR_OK : COLOR_WARN );
		else return "<B><FONT COLOR='BLUE'>\nINFO\n</FONT></B>\n";
		return "<B><FONT COLOR='$color'>\n$msg\n</FONT></B>\n";

	}
}

class ini_checker extends checker // {{{
{
    function check_dependences() // {{{
    {
        return parent::check_dependences() && function_exists( "ini_get");
    } // }}}

    function make_test() {
        return ini_get( $this->test); //value is read from php.ini
    }

}

class gdlib_checker extends checker // {{{
{
    function check_dependences() // {{{
    {
        return parent::check_dependences() && function_exists( "gd_info");
    } // }}}

	function make_test() {
		$gd = gd_info();
		return $gd[ $this->test];
	}

}

class test_if_directory_exists extends checker // {{{
{
    function check_dependences() // {{{
    {
        return parent::check_dependences() && function_exists( "file_exists");
    } // }}}

    function make_test() // {{{
    {
        return @file_exists( $this->test);
    } // }}}

    function get_value() {
        return $this->result ? "EXISTS" : "DOES NOT EXIST";
    }
}

class test_if_directory_is_writable extends checker // {{{
{
    function check_dependences() // {{{
	{
        return parent::check_dependences() && function_exists( "is_writable");
    } // }}}

    function make_test() // {{{
	{ 
        return @is_writable( $this->test);
	} // }}}

	function get_value() {
		return $this->result ? "WRITABLE" : "NON-WRITABLE";
	}
}

class ini_switch_checker extends ini_checker // {{{
{
    function is_ok() {
		if( is_string( $this->value))
			switch( $this->value) {
				case "0":
				case "Off":
				case "off":
				case "OFF":	return $this->correct_value == FALSE;
				case "1":
                case "On":
                case "on":
                case "ON": return $this->correct_value == TRUE;
			}
		return parent::is_ok();
    }
} // }}}

class open_basedir_checher extends checker {

    function check_dependences() // {{{
    {
        return function_exists( "ini_get") &&
			function_exists( "implode") &&
			function_exists( "explode");
    } // }}}

    function make_test() {
		return ini_get( "open_basedir");
    }

	function is_ok() {
        if( !$this->value) return TRUE;
        $dirs = explode( DIRECTORY_SEPARATOR, $this->value);
        $pwd = getcwd();
        $len = strlen( $pwd);
        foreach( $dirs as $dir) {
            $len = strlen( $dir);
            if( substr( $pwd, 0, $len) == $dir) return TRUE;
            }
        return FALSE;
	}
}

class info_checker extends ini_checker {
	function make_test() {	
		$fn = $this->test;
		return $fn();	
	}
}

class get_cfg_var_checker extends ini_checker {

	function make_test() {
		return get_cfg_var( $this->test);
	}
}

class function_checker extends checker {

	function make_test() {
		return function_exists( $this->test);
	}

	function get_value() {
		return $this->value ? "function exists" : "function does not exist" ;
	}

}

class version_checker extends checker {

	function make_test() {
		return PHP_VERSION;
	}

	function numeric_value( $s) {
		$r = explode( ".", $s);
		return 10000*$r[0] + 100*$r[1] + $r[2];
	}

	function is_ok() {
	global $sw;
	$ver = $this->numeric_value( phpversion());
		$min = $this->numeric_value( "4.1.0");
		$php406 = $this->numeric_value( "4.0.6");
		$php5 = $this->numeric_value( "5.0.0");
		$php442 = $this->numeric_value( "4.4.2");
		$php51 = $this->numeric_value( "5.1.0");
		if( $sw == "LiteCommerce") return ( $ver >= $min && $ver < $php5 || $ver >= $php51) && ( !ON_WINDOWS || $ver != $php442);
		if( $sw == "x-cart") return ( $ver >= $php406);
	}
}

class exec_based_test extends checker // {{{
{
    function check_dependences() // {{{
    {
        if( !function_exists( "ini_get") || !function_exists( "exec")) return FALSE;
		$o = array();
		$e = "";
		@exec( "echo 1", $o, $e);
		if( $e === "") return FALSE;
		return $e == 0 && $o[0] == "1";
    } // }}}
} // }}}

class io_redirection_test extends exec_based_test {
    function make_test() {
		$output = array();
		@exec("echo OK 2>&1 1>&2", $output);
		if( empty( $output)) return @$php_errormsg;
		else return implode("", $output);
	}
}

class IP_checker extends checker { // {{{

    function make_test() {
		$this->host = "localhost";
		$addr = "127.0.0.1";
		if( isset( $_SERVER['LOCAL_ADDR'])) $addr = $_SERVER['LOCAL_ADDR'];
		if( isset( $_SERVER['SERVER_ADDR'])) $addr = $_SERVER['SERVER_ADDR'];
		if( isset( $_SERVER['HTTP_HOST'])) $this->host = & $_SERVER['HTTP_HOST'];
		$this->ip = gethostbyname( $this->host);
		return $addr;
	}

	function is_ok() { // {{{
		return ( $this->ip == $this->value);
	} // }}}

    function get_value() {
        if( !$this->result) return sprintf( "SERVER_ADDR: %s<BR>host %s: %s<BR>", $this->value, $this->host, $this->ip);
		return parent::get_value();
    }

} // }}}

class loop_back_checker extends checker {
    function check_dependences() // {{{
    {
        return ini_get("allow_url_fopen") == 1;
	} // }}}

    function make_test() {

	    if( phpversion() == "4.4.2" && ON_WINDOWS) return "PHP bug";
		$PHP_SELF = & $_SERVER['PHP_SELF'];
	    $HOST = & $_SERVER['HTTP_HOST'];
		$uri = "http://".$HOST.$PHP_SELF."?mode=echo_ok&login=".urlencode(SUP_LOGIN)."&password=".urlencode(SUP_PASSWORD);

/*
$fp = fsockopen( $_SERVER['SERVER_ADDR'], 80, $errno, $errstr, 3);
if(!$fp) {
    $this->value = "Error: $errno<BR>$errstr";
    } else {
         fputs($fp, "GET ".$PHP_SELF."?mode=echo_ok&login=".urlencode(SUP_LOGIN)."&password=".urlencode(SUP_PASSWORD)." HTTP/1.1\r\n");
		 fputs($fp, "Host: ".$HOST."\r\n");
         fputs($fp, "Connection: Close\r\n\r\n");
//         fputs($fp, "GET $uri HTTP/1.0\r\n");
		$content = "";
         while(!feof($fp)) {
                    $content .= fgets($fp,4096);
                    }
         fclose($fp);
echo "<PRE>";
var_dump( $content);
         $csplit = split("\r\n\r\n",$content,2);
		$this->value = $csplit[1];
//		$this->result = $csplit[1] == "OK";
	}
return;
*/
		ob_start();
		$f = fopen( $uri, "r");
        $output = ob_get_contents();
        ob_end_clean();
    	if( $f) {
	        $r = fread( $f, 1024);
	        fclose( $f);
			
	    } else {
			$r = $output;
	    }
		return $r;
	}

}

class mail_checker extends checker {
    function make_test() {
		ob_start();
        $r = mail( "bit-bucket@x-cart.com", "test", "test");
		$this->error = ob_get_contents();
		ob_end_clean();
		return $r;
	}

	function is_ok() {
		return $this->value && empty( $this->error);
	}

    function get_value() {
		if( !$this->result) return "Error:<BR>".$this->error;
		return "Mail sent";
	}
}

class constant_checker extends checker {

	function make_test() {
		return @constant( $this->test);
	}

	function is_ok() {
		return defined( $this->test);
	}

	function get_value() {
		if( !$this->result) return "not defined";
		return parent::get_value();
	}
}

class extension_checker extends checker {

    function check_dependences() {
        return function_exists( "extension_loaded") && parent::check_dependences();
    }

	function make_test() {
		return extension_loaded( $this->test);
	}

	function get_value() {
		return $this->value ? "extension loaded" : "extension not loaded" ;
	}

}

class exec_checker extends exec_based_test // {{{
{
	var $data = array();

	function make_test() {
		$code = "";
		@exec( $this->test." 2>&1", $this->data, $code);
		return $code;
	}

	function get_value() {
		if( is_array( $this->data)) $this->data = implode( "<BR>\n", $this->data);
		return $this->data."<BR>\nReturn code: ".$this->value;
	}
}

/*
class which_checker extends exec_checker // {{{
{
    function check_dependences() {
        return function_exists( "is_executable");
	}

	function get_value() {
die (222222);
		return exec_checker::get_value()."<BR>\n".(is_readable( $this->data) ? "11" : "12");
		return exec_checker::get_value()."<BR>\n".(is_executable( $this->data) ? "11" : "12");
//$this->data."<BR>\nReturn code: ".$this->value;
	}
} // }}}
*/
function perform_test( $title, $tests) // {{{
{
$out = "<H1>$title</H1>\n<TABLE border=1 cellpadding=0 cellspacing=0 width=90%>
<TH WIDTH=240>test</TH>
<TH>value</TH>
<TH WIDTH=60>result</TH>";

foreach( $tests as $i => $tester) {
	$class = $i % 2 ? "A" : "B";
	$out .= "<TR CLASS=$class>\n<TD>";
	$out .= str_replace( "\n", "\n\t", $tester->get_title());
	$out .= "</TD>\n<TD>";
	$out .= str_replace( "\n", "\n\t", $tester->get_value());
	$out .= "</TD>\n<TD>";
	$out .= str_replace( "\n", "\n\t", $tester->get_result());
	$out .= "</TD></TR>\n";
}
$out .= "</TR>
</TABLE>";
return $out;
} // }}}

function func_display_results() // {{{
{
$fn[] = new ini_checker( "disable_functions", "", TEST_NON_CRITICAL, "disable_functions");
$fn[] = new function_checker( "ini_set");
$fn[] = new function_checker( "ini_get");
$fn[] = new function_checker( "set_time_limit", true, TEST_CRITICAL, "set_time_limit");
$fn[] = new get_cfg_var_checker( "max_execution_time", 0, TEST_INFO, "max_execution_time");
$fn[] = $exec = new function_checker( "exec", true, TEST_CRITICAL, "exec");
$exec_r = $exec->is_ok();
$fn[] = new io_redirection_test("Input/output redirection test", "OK", TEST_CRITICAL, "", $exec_r);
$fn[] = new function_checker( "escapeshellarg", true, TEST_NON_CRITICAL);

//$fn[] =	new function_checker( "passthru", true, TEST_CRITICAL, "exec");
//$fn[] = new function_checker( "popen", true, TEST_CRITICAL, "exec");
//$fn[] = new function_checker( "pclose", true, TEST_CRITICAL, "exec");
$fn[] = new function_checker( "phpinfo", true, TEST_NON_CRITICAL);
$fn[] = new constant_checker( "DIRECTORY_SEPARATOR");
$fn[] = new constant_checker( "PATH_SEPARATOR");
$fn[] = $loop_back_test = new loop_back_checker("loop back test", "OK", TEST_CRITICAL);
$fn[] = new function_checker( "mail", true, TEST_CRITICAL);
//$fn[] = new mail_checker("mail");

if( function_exists("ini_get")) $disable_functions = ini_get("disable_functions");
else $disable_functions = "";
$disable_functions_list = preg_split("/[, ]/", $disable_functions);

$php_extensions = array(
	new ini_checker( "extension_dir", "", TEST_INFO),
    new test_if_directory_exists( ini_get( "extension_dir"), TRUE),
    new extension_checker( "pcre", true, TEST_CRITICAL),
    new function_checker( "preg_match", true, TEST_CRITICAL),
    new extension_checker( "xml", true, TEST_CRITICAL, "xml"),
    new function_checker( "xml_parser_create", true, TEST_CRITICAL),
	$curl_1 = new extension_checker( "curl", true, TEST_CRITICAL, "curl"),
	$curl_2 = new function_checker( "curl_init"),
	new extension_checker( "mysql"),
	new function_checker( "mysql_connect"),
    new ini_checker( "sql.safe_mode", FALSE),
	new ini_checker( "mysql.default_host", 0, TEST_INFO),
	new ini_checker( "mysql.default_user", 0, TEST_INFO),
	new ini_checker( "mysql.default_password", 0, TEST_INFO),
	new ini_checker( "mysql.connect_timeout", 0, TEST_INFO),
	new extension_checker( "ftp"),
	new function_checker( "ftp_connect"),
    new extension_checker( "mcrypt", true, TEST_NON_CRITICAL),
    new function_checker( "mcrypt_module_open", true, TEST_NON_CRITICAL),
    new extension_checker( "openssl", true, TEST_NON_CRITICAL),
    new function_checker( "openssl_open", true, TEST_NON_CRITICAL),
);
    $php_extensions[] = $gd = new extension_checker( "gd");
if( $gd->result) {
	$gd_info =  gd_info();
/*
foreach ($gd_info as $key=>$val) {
  if ($val===true) {
   $val="Enabled";
  }
  if ($val===false) {
   $val="Disabled";
  }
  echo "$key: $val <br />\n";
}
*/
$gd_options = array(
	"JPG Support" => TEST_CRITICAL,
	"GIF Read Support" => TEST_CRITICAL,
	"GIF Create Support" => TEST_CRITICAL,
	"FreeType Support" => TEST_NON_CRITICAL,
	"PNG Support" => TEST_NON_CRITICAL,
	"WBMP Support" => TEST_NON_CRITICAL,
	"XBM Support" => TEST_NON_CRITICAL,
);
    $php_extensions[] = new checker( "GD Version", $gd_info["GD Version"], true, TEST_INFO);
foreach( $gd_options as $gd_opt => $severity) 
	$php_extensions[] = new gdlib_checker( $gd_opt, true, $severity);
    $php_extensions[] = new function_checker( "imagejpeg", true, TEST_CRITICAL);

    $php_extensions[] = new function_checker( "imagecopyresampled", true, TEST_CRITICAL);
    $php_extensions[] = new function_checker( "imagecopyresampled", true, TEST_CRITICAL);
    $php_extensions[] = new function_checker( "imageCreatetruecolor", true, TEST_CRITICAL);
	}
    $php_extensions[] = $zlib = new extension_checker( "zlib", true, TEST_NON_CRITICAL);
if( $zlib->result) {
    $php_extensions[] = new ini_checker( "zlib.output_compression", true, TEST_NON_CRITICAL);
    $php_extensions[] = new ini_checker( "zlib.output_compression_level", true, TEST_INFO);
	}
    $php_extensions[] = $ionCube = new extension_checker( "ionCube Loader", true, TEST_NON_CRITICAL);
if( !$ionCube->result) {
	$php_extensions[] = new ini_checker( "enable_dl", "1", TEST_LC_CRITICAL);
	$php_extensions[] = new function_checker( "dl", true, TEST_LC_CRITICAL, "dl");
	$php_extensions[] = new extension_checker( "Zend Optimizer", false, TEST_LC_CRITICAL);
	}

$tests = array(
	new constant_checker( "PHP_OS", 0, TEST_INFO),
    new checker( "php_sapi_name", php_sapi_name(), 0, TEST_INFO),
	new version_checker( "PHP version
<BR>
<A HREF=http://www.x-cart.com/cart_system_requirements.html>X-Cart requrements</A>
<BR>
<A HREF=http://litecommerce.com/server_requirements.html>LiteCommerce requrements</A>", "", TEST_CRITICAL),
	new get_cfg_var_checker( "cfg_file_path", 0, TEST_INFO),
    new checker( "php_uname", php_uname(), 0, TEST_INFO),
	new IP_checker("IP", true, TEST_NON_CRITICAL),
);

$unix_binaries = array(
	new open_basedir_checher( "open_basedir", ""),
	new ini_checker( "safe_mode_exec_dir", "", TEST_INFO),
    new exec_checker( "which perl", 0, TEST_NON_CRITICAL, "", $exec_r),
	new exec_checker( "which gpg", 0, TEST_NON_CRITICAL, "", $exec_r),
	new exec_checker( "which pgp", 0, TEST_NON_CRITICAL, "", $exec_r),
);
$unix_binaries[] = new exec_checker( "which mysql", 0, TEST_NON_CRITICAL, "mysql_bin",  $exec_r);
$unix_binaries[] = new exec_checker( "which mysqldump", 0, TEST_NON_CRITICAL, "mysql_bin", $exec_r);
$unix_binaries[] = new exec_checker( "mysql --version", 0, TEST_NON_CRITICAL, "", $exec_r);
$unix_binaries[] = $is_executable = new function_checker( "is_executable", 1, TEST_NON_CRITICAL);

if( !$curl_1 && !$curl_2) {
$unix_binaries[] = new exec_checker( "which openssl", 0, TEST_NON_CRITICAL, "", $exec_r);
$unix_binaries[] = new exec_checker( "which curl", 0, TEST_NON_CRITICAL, "", $exec_r);
}

$php = array();
$php[] = 	$safe_mode = new ini_switch_checker( "safe_mode", FALSE);
$php[] = 	new ini_checker( "memory_limit", 0, TEST_INFO);
$php[] =    new function_checker( "memory_get_usage", true, TEST_CRITICAL);
$php[] = 	new ini_checker( "file_uploads", TRUE);
$php[] =     $temp_dir = new ini_checker( "upload_tmp_dir", "", TEST_NON_CRITICAL);
if( !$temp_dir->result) {
	$php[] = 	new test_if_directory_exists( ini_get( "upload_tmp_dir"), TRUE);
	$php[] = 	new test_if_directory_is_writable( ini_get( "upload_tmp_dir"), TRUE);
	}
$php[] = 	new ini_checker( "upload_max_filesize", "2M", TEST_INFO);
$php[] = 	new ini_checker( "post_max_size", FALSE, TEST_INFO);
$php[] = 	new ini_checker( "auto_prepend_file", "", TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "auto_append_file", "", TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "include_path", FALSE, TEST_INFO);
$php[] = 	new ini_checker( "allow_url_fopen", TRUE, TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "magic_quotes_gpc", TRUE, TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "magic_quotes_runtime", FALSE, TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "register_globals", FALSE, TEST_NON_CRITICAL, "register_globals");
$php[] = 	new ini_checker( "magic_quotes_sybase", 0, TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "short_open_tag", 1, TEST_NON_CRITICAL);
//$php[] = 	new ini_checker( "track_errors", 1, TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "implicit_flush", 0, TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "allow_call_time_pass_reference", 1, TEST_NON_CRITICAL);
$php[] = 	new ini_checker( "default_charset", "", TEST_INFO);
$php[] =     new ini_checker( "session.save_path", "", TEST_INFO);
if( ini_get( "session.save_path")) {
	$php[] =     new test_if_directory_exists( ini_get( "session.save_path"), TRUE);
	$php[] =     new test_if_directory_is_writable( ini_get( "session.save_path"), TRUE);
	}
$php[] =     new ini_checker( "session.save_handler", "", TEST_INFO);
$php[] =     new ini_checker( "session.auto_start", 0, TEST_NON_CRITICAL);
$php[] =     new ini_checker( "error_log", "", TEST_INFO);
$php[] =     new ini_checker( "display_errors", "", TEST_INFO);
$php[] =     new ini_checker( "display_startup_errors", "", TEST_INFO);
$php[] =     new ini_checker( "log_errors", TRUE, TEST_INFO);

//$php[] = 	new ini_checker( "track_vars", "1"),
//$php[] = 	new info_checker( "get_magic_quotes_gpc", 0, TEST_CRITICAL),
$ver = phpversion();
list($branch1, $branch2, $branch3) = preg_split('/[.]/', $ver);
if( $branch1 >= 5)
	$php[] = new ini_checker( "register_long_arrays", "1", TEST_CRITICAL);

$out  = perform_test( "General Info", $tests);
$out .= perform_test( "PHP configuration options", $php);
$out .= perform_test( "PHP extensions", $php_extensions);
$out .= perform_test( "PHP functions", $fn);
$out .= perform_test( "UNIX-specific binaries the s/w uses", $unix_binaries);
echo $out;
} // }}}

function fn_get_version() // {{{
{
	echo VERSION;
} // }}}

function fn_echo_ok() {
	echo "OK";
}

function fn_recursion_depth( $step = 0) {
	if( $step < 10000) fn_recursion_depth( $step + 1);
	else echo "OK";
}

function fn_phpinfo() {
	phpinfo();
	}

function func_session_start() // {{{
{
global $SUP_SESSION_ID, $SUP_SESSION_NAME;

ini_set("session.save_handler", "files");
$sess_length = 1200; # sec
$SUP_SESSION_NAME = 'sxid';
if (func_isset_var($SUP_SESSION_NAME, 'COOKIE'))
    $SUP_SESSION_ID = func_get_var($SUP_SESSION_NAME, 'COOKIE');
else
    $SUP_SESSION_ID = false;

//x_session_start($SUP_SESSION_ID);
//register_shutdown_function("x_session_save");
//ini_set( "session.safe_path", "123");
session_start($SUP_SESSION_ID);
$SUP_SESSION_ID = session_id();
setcookie($SUP_SESSION_NAME, $SUP_SESSION_ID, 0, "/", func_get_var('HTTP_HOST', 'SERVER'), 0);
} // }}}

function func_get_var($name = '', $type = 'REQUEST') // {{{
{
    if (isset($GLOBALS['_'.$type]))
        $key = '_'.$type;
    else if (isset($GLOBALS['HTTP_'.$type.'_VARS']))
        $key = 'HTTP_'.$type.'_VARS';
    else
        die('PHP 5 doesn\'t configured properly!');

    $result = $GLOBALS[$key];
    if( $name) $result = (isset( $result[$name]) ? $result[$name] : NULL);
   
    return (get_magic_quotes_gpc()
            ? $result
            : func_addslashes($result));
} // }}}

function func_isset_var($name = '', $type = 'REQUEST') // {{{
{
    if (isset($GLOBALS['_'.$type]))
        $key = '_'.$type;
    else if (isset($GLOBALS['HTTP_'.$type.'_VARS']))
        $key = 'HTTP_'.$type.'_VARS';
    else
      die('PHP 5 doesn\'t properly configured!');

    return (empty($name)
            ? isset($GLOBALS[$key])
            : isset($GLOBALS[$key][$name]));
} // }}}

function func_display_header() // {{{
{
$url_1 = "https://x-business.crtdev.local/img/support.gif?user_name=abr&time=".time();
?>
<HTML>
<HEAD>
<TITLE>Compatibility test v<?php echo VERSION;?></TITLE>
<META http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<META name='robots' content='noindex,nofollow'>
<?php
echo "<LINK rel='stylesheet' href='$url_1'>\n";
} // }}}

function func_display_auth_form() // {{{
{
echo "<!-- Login form -->\n";
?>
<FORM action="<?php echo SUP_NAME; ?>" method="POST">
<INPUT type="hidden" name="action" value="login">
Login: <INPUT type="text" name="login" value="" id="login_area">&nbsp;&nbsp;
Password: <INPUT type="password" name="password" value="">&nbsp;&nbsp;
<INPUT type="submit" value="Login"><BR><BR>
</FORM>
<SCRIPT>
<!--
document.getElementById('login_area').focus();
-->
</SCRIPT>
<?php
echo "<!-- Login form -->\n";
} // }}}

function func_action_login() // {{{
{
    global $data;

    $_login = func_get_var( "login");
    $_pwd = func_get_var( "password");
    $_passwd = md5( $_pwd);

    if (($_login == SUP_LOGIN) && ($_passwd == SUP_PASSWORD)) {
            $data["login"] = $_login;
            $data["password"] = $_passwd;
    } else {
	echo "Login/password is incorrect!";
    }
} // }}}

function func_authorize() // {{{
{
	global $data;

	$action = func_get_var( "action", "POST");
	if( !empty($action)) func_action_login();
	$is_login = ($data["login"] == SUP_LOGIN && $data["password"] == SUP_PASSWORD);
	if($is_login) return;
	func_display_auth_form();
	die();
} // }}}

function  func_array_map($func, $var) // {{{
{
    if (!is_array($var)) {
        return call_user_func($func, $var);
    } else {
        foreach($var as $k => $v) {
            $var[$k] = func_array_map($func, $v);
        }
        return $var;
    }
} // }}}

function func_addslashes($var) // {{{
{
    return func_array_map('addslashes', $var);
} // }}}

function func_array_key_exists($key, $search) // {{{
{
    if (function_exists("array_key_exists")) {
        return array_key_exists($key, $search);

    } elseif (!isset($search[$key])) {
        foreach ($search as $k => $v) {
            if ($k === $key)
                return true;
        }

        return false;
    }

    return true;
} // }}}

?>
