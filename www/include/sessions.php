<?php
if (!defined('XCART_START')) {
    header("Location: ../");
    die("Access denied");
}

#
# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
# DO NOT CHANGE ANYTHING BELOW THIS LINE UNLESS
# YOU REALLY KNOW WHAT ARE YOU DOING
# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
#

if (defined('XCART_SESSION_START')) {
    return;
}

define("XCART_SESSION_START", 1);
//
//if ($use_sessions_type == 2) {
//    include $xcart_dir . "/include/mysql_sessions.php";
//}

#
# PHP build-in sessions tuning (for type "1" & "2")
#

//# PHP 4.3.0 and higher allow to turn off trans-sid using this command:
//ini_set("url_rewriter.tags", "");
//# Let's garbage collection will occurs more frequently
//ini_set("session.gc_probability", 90);
//ini_set("session.gc_divisor", 100); # for PHP >= 4.3.0
//ini_set("session.use_cookies", false);

#
# Anti cache block
#
#DEFINE("SET_EXPIRE",time()+600);

//register_shutdown_function("x_session_save");

# Erase old service array (Group editing of products functionality)
if (defined("AREA_TYPE")) {
    if (constant("AREA_TYPE") == 'A' || constant("AREA_TYPE") == 'P') {
        $res = db_query("SELECT $sql_tbl[ge_products].geid FROM $sql_tbl[ge_products] LEFT JOIN $sql_tbl[sessions_data] ON $sql_tbl[ge_products].sessid = $sql_tbl[sessions_data].sessid WHERE $sql_tbl[sessions_data].sessid IS NULL");
        if ($res) {
            while ($row = db_fetch_row($res)) {
                func_ge_erase($row[0]);
            }

            db_free_result($res);
        }
    }
}

if (!Xcart\App\Cli\Cli::isCli()) {
    x_session_start();

    $smarty->assign("XCARTSESSNAME", $XCART_SESSION_NAME);
    $smarty->assign("XCARTSESSID", $XCARTSESSID);
}

####################################################################
#   FUNCTIONS
####################################################################

#
# Start session
#
function x_session_start($sessid = '')
{
    # $sessid should contain only '0'..'9' or 'a'..'z' or 'A'..'Z'
    if (strlen($sessid) > 32 || !empty($sessid) && !preg_match('!^[0-9a-zA-Z]+$!S', $sessid)) {
        $sessid = null;
    }


    if (!\Xcart\App\Cli\Cli::isCli()) {
        \Xcart\App\Main\Xcart::app()->request->session->start($sessid);
    }
}

#
# Change current session to session with specified ID
#
function x_session_id($sessid = "")
{

    if (!\Xcart\App\Cli\Cli::isCli()) {
        $old = \Xcart\App\Main\Xcart::app()->request->session->all();
        \Xcart\App\Main\Xcart::app()->request->session->start($sessid);
    }

    if ($sessid) {
        if ($old) {
            if (!empty($old)) {
                foreach ($old as $var => $v) {
                    if (isset($GLOBALS[$var])) {
                        unset($GLOBALS[$var]);
                    }
                }
            }
        }
    }


    if (!\Xcart\App\Cli\Cli::isCli()) {
        return \Xcart\App\Main\Xcart::app()->request->session->getId();
    }
}

#
# Cut off variable if it is come from _GET, _POST or _COOKIES
#
function check_session_var($varname)
{
    global $_GET, $_POST, $_COOKIE;

    if (isset($_GET[$varname]) || isset($_POST[$varname]) || isset($_COOKIE[$varname])) {
        return false;
    }

    return true;
}

#
# Register variable XCART_SESSION_VARS array from the database
#
function x_session_register($varname, $default = "")
{
    if (empty($varname)) {
        return;
    }

    if (!\Xcart\App\Cli\Cli::isCli()) {
        $session = \Xcart\App\Main\Xcart::app()->request->session;

        if (!$session->has($varname)) {
            if (isset($GLOBALS[$varname]) && check_session_var($varname)) {
                $session->add($varname, $GLOBALS[$varname]);
            }
            else {
                $session->add($varname, $default);
            }
        }
        else {
            if (isset($GLOBALS[$varname]) && check_session_var($varname)) {
                $session->add($varname, $GLOBALS[$varname]);
            }
        }

        $GLOBALS[$varname] = $session->get($varname);
    }
}

#
# Save the XCART_SESSION_VARS array in the database
#
function x_session_save()
{
    if (!\Xcart\App\Cli\Cli::isCli()) {
        \Xcart\App\Main\Xcart::app()->request->session->collectGlobals(func_get_args());
    }
}

function covex_log()
{
    return 1;
}

function x_session_save_to_db()
{
    if (!\Xcart\App\Cli\Cli::isCli()) {
        \Xcart\App\Main\Xcart::app()->request->session->collectGlobals(func_get_args());
    }
}

#
# Unregister variable $varname from $XCART_SESSION_VARS array
#
function x_session_unregister($varname, $unset_global = false)
{
    if (!\Xcart\App\Cli\Cli::isCli()) {
        \Xcart\App\Main\Xcart::app()->request->session->remove($varname);
    }

    if ($unset_global) {
        func_unset($GLOBALS, $varname);
    }
}

#
# Find out whether a global variable $varname is registered in
# $XCART_SESSION_VARS array
#
function x_session_is_registered($varname)
{
    if (!\Xcart\App\Cli\Cli::isCli()) {
        return \Xcart\App\Main\Xcart::app()->request->session->has($varname);
    }
}

#
# Change session ID
#
function x_session_change()
{

    if (!\Xcart\App\Cli\Cli::isCli()) {
        \Xcart\App\Main\Xcart::app()->request->session->regenerateID(true);
    }

    if (!defined("SESSION_ID_CHANGED")) {
        define("SESSION_ID_CHANGED", true);
    }

    if (!\Xcart\App\Cli\Cli::isCli()) {
        return \Xcart\App\Main\Xcart::app()->request->session->getId();
    }
}