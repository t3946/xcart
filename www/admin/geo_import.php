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
# $Id: geo_import.php,v 1.5 2006/01/11 06:55:57 mclap Exp $
#

define("IS_IMPORT", true);
require "./auth.php";
require $xcart_dir."/include/security.php";
x_load('backoffice','files','image','import', 'debug');

set_time_limit(86400);
ini_set("memory_limit", "512M");


$location[] = array("GEO import", "");

if ($REQUEST_METHOD == "POST") {

	#
	# Initial settings
	#
	$delimiter = ",";
	$source = "server"; # File is located on the server



#####################################################################################################
# GeoLiteCity-Blocks
#

	$import_file_blocks = array();
        if ($source == "server" && !empty($localfile_blocks)) {
        	# File is located on the server
                $localfile_blocks = stripslashes($localfile_blocks);
                if (func_allow_file($localfile_blocks, true)) {
                	$import_file_blocks["location"] = $localfile_blocks;
                        $import_file_blocks["uploaded"] = false;
		}
	}

        #
        # Open import file
        #
        if ($import_file_blocks != "" && isset($import_file_blocks["location"])) {
                $fp = @func_fopen($import_file_blocks["location"], "r", true);
                if (!@func_filesize($import_file_blocks["location"]) || $fp === false) {
                        if ($fp !== false) {
                                fclose($fp);
                                $fp = false;
                        }

                        if ($import_file_blocks["uploaded"])
                                @unlink($import_file_blocks["location"]);

                        $import_file_blocks = "";
                }
        }

	if (!empty($import_file_blocks)){
		#
		# Importing GeoLiteCity-Blocks to DB
		#
		print("Starting...<br />");
		$cnt = 0;
		db_query("DELETE FROM $sql_tbl[geo_litecity_blocks]");

		while (($columns = fgetcsv ($fp, 1000, $delimiter))) {

			$tmp_check = intval($columns[0]);
			if ($tmp_check > 0){
				db_query("INSERT INTO $sql_tbl[geo_litecity_blocks] (startIpNum, endIpNum, locId) VALUES ('".addslashes($columns[0])."','".addslashes($columns[1])."','".addslashes($columns[2])."')");

                                $cnt++;
                                if ($cnt % 100 == 0) {
                                        func_flush(".");
                                        if($cnt % 5000 == 0) {
                                                func_flush("<br />\n");
                                        }

                                        func_flush();
                                }
			}
		}
		fclose($fp);
	}



#####################################################################################################
# GeoLiteCity-Location
#
        $import_file_location = array();
        if ($source == "server" && !empty($localfile_location)) {
                # File is located on the server
                $localfile_location = stripslashes($localfile_location);
                if (func_allow_file($localfile_location, true)) {
                        $import_file_location["location"] = $localfile_location;
                        $import_file_location["uploaded"] = false;
                }
        }

        #
        # Open import file
        #
        if ($import_file_location != "" && isset($import_file_location["location"])) {
                $fp = @func_fopen($import_file_location["location"], "r", true);
                if (!@func_filesize($import_file_location["location"]) || $fp === false) {
                        if ($fp !== false) {
                                fclose($fp);
                                $fp = false;
                        }

                        if ($import_file_location["uploaded"])
                                @unlink($import_file_location["location"]);

                        $import_file_location = "";
                }
        }

        if (!empty($import_file_location)){
                #
                # Importing GeoLiteCity-Location to DB
                #
                print("<br />Starting...<br />");
                $cnt = 0;
                db_query("DELETE FROM $sql_tbl[geo_litecity_location]");

                while (($columns = fgetcsv ($fp, 1000, $delimiter))) {

                        $tmp_check = intval($columns[0]);
                        if ($tmp_check > 0){
                                db_query("INSERT INTO $sql_tbl[geo_litecity_location] (locId, country, region, city, postalCode, latitude, longitude, metroCode, areaCode) VALUES ('".addslashes($columns[0])."','".addslashes($columns[1])."','".addslashes($columns[2])."','".addslashes($columns[3])."','".addslashes($columns[4])."','".addslashes($columns[5])."','".addslashes($columns[6])."','".addslashes($columns[7])."','".addslashes($columns[8])."')");

                                $cnt++;
                                if ($cnt % 100 == 0) {
                                        func_flush(".");
                                        if($cnt % 5000 == 0) {
                                                func_flush("<br />\n");
                                        }

                                        func_flush();
                                }
                        }
                }
                fclose($fp);
        }

#####################################################################################################


        if (empty($import_file_blocks) && empty($import_file_location)) {
        # File cannot be opened: display error
//		x_session_unregister("import_file_location");
//		x_session_unregister("import_file_blocks");
                $top_message["content"] = func_get_langvar_by_name("msg_err_file_wrong");
                $top_message["type"] = "E";
                func_header_location("geo_import.php");
        } else {
                $top_message["content"] = "Done";
                $top_message["type"] = "I";
                func_header_location("geo_import.php");
	}
}

$smarty->assign("my_files_location",func_get_files_location());

$smarty->assign("main","geo_import");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir."/modules/gold_display.php";
func_display("admin/home.tpl",$smarty);
?>
