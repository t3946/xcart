<?php

define('USE_TRUSTED_POST_VARIABLES', 1);
define('USE_TRUSTED_SCRIPT_VARS', 1);
$trusted_post_variables = ["var_value", "new_var_value"];

require "./auth.php";
require $xcart_dir . "/include/security.php";

x_load('files', 'backoffice');

x_session_register("serverfile");

$location[] = [func_get_langvar_by_name("lbl_edit_languages"), ""];

$topics = func_query_column("SELECT topic FROM $sql_tbl[languages] WHERE topic<>'' GROUP BY topic ORDER BY topic");

if (!in_array($topic, $topics)) {
    $topic = "";
}

$d_langs = explode("|", $config["disabled_languages"]);
if ($d_langs) {
    foreach ($d_langs as $key => $value) {
        $d_langs [$key] = trim($value);
    }
}

$languages = $avail_languages;

if ($languages) {
    foreach ($languages as $key => $value) {
        $languages[$key]["disabled"] = (in_array($value["code"], $d_langs) ? "Y" : "N");
    }
}

if ($mode == "update_charset") {

    require $xcart_dir . "/include/safe_mode.php";

    if ($text_dir == 'Y') {
        $config['r2l_languages'][$language] = true;
    }
    elseif (isset($config['r2l_languages'][$language])) {
        unset($config['r2l_languages'][$language]);
    }
    $tmp = serialize($config['r2l_languages']);
    db_query_param("REPLACE INTO $sql_tbl[config] (name,value) VALUES ('r2l_languages',:value )", ['value' => $tmp]);
    db_query_param("UPDATE $sql_tbl[countries] SET charset=:charset WHERE code=:code", ['code' => $language, 'charset' => $charset]);
    func_data_cache_get("charsets", [], true);

    func_header_location("languages.php?language=$language");
}
elseif ($mode == "update") {

    require $xcart_dir . "/include/safe_mode.php";

    if ($var_value) {
        foreach ($var_value as $key => $value) {
            func_array2update("languages", ["value" => $value], ['code' => $language, 'name' => $key]);
        }
    }

    if ($topic == 'Languages') {
        func_data_cache_get("languages", [$language], true);
    }

    $top_message = [
        "content" => func_get_langvar_by_name("lbl_lng_variable_updated"),
    ];

    $smarty->clear_all_cache();
    $smarty->clear_compiled_tpl();

    func_header_location("languages.php?language=$language&page=$page&filter=" . urlencode($filter) . "&topic=$topic");
}
elseif ($mode == "add") {

    require $xcart_dir . "/include/safe_mode.php";

    if (empty($new_var_name)) {
        $top_message["content"] = func_get_langvar_by_name("msg_err_empty_label");
        $top_message["type"] = "E";
        func_header_location("languages.php?language=$language&page=$page&filter=" . urlencode($filter) . "&topic=$topic");
    }
    elseif ($new_var_name != preg_replace('/[^A-Za-z0-9_]/', '', $new_var_name)) {
        $top_message["content"] = func_get_langvar_by_name("msg_err_invalid_label");
        $top_message["type"] = "E";
        func_header_location("languages.php?language=$language&page=$page&filter=" . urlencode($filter) . "&topic=$topic");
    }

    $topic = in_array($new_topic, $topics) ? $new_topic : $topics[0];

    $is_exists = func_query_first_cell_param("SELECT COUNT(*) FROM $sql_tbl[languages] WHERE name = :name AND code=:code", ['name' => $new_var_name, 'code' => $language]) > 0;
    if ($is_exists) {
        func_array2update("languages",
            [
                'value' => $new_var_value,
            ],
            ['name' => $new_var_name, 'code' => $language]
        );
    }
    else {
        foreach ($languages as $key => $value) {
            func_array2insert("languages",
                [
                    "code" => $value['code'],
                    "name" => $new_var_name,
                    "value" => $new_var_value,
                    "topic" => $topic,
                ],
                true
            );
        }
    }

    if ($topic == 'Languages') {
        func_data_cache_get("languages", [$language], true);
    }

    $top_message = [
        "content" => func_get_langvar_by_name("lbl_lng_variable_added"),
    ];

    func_header_location("languages.php?language=$language&page=$page&filter=" . urlencode($filter) . "&topic=$topic");
}
elseif ($mode == "delete" && !empty($ids)) {

    require $xcart_dir . "/include/safe_mode.php";

    db_query_param("DELETE FROM $sql_tbl[languages] WHERE name IN (:ids)", ['ids' => $ids]);

    if ($topic == 'Languages') {
        func_data_cache_get("languages", [$language], true);
    }

    $top_message = [
        "content" => func_get_langvar_by_name("lbl_lng_variables_deleted"),
    ];

    func_header_location("languages.php?language=$language&page=$page&filter=" . urlencode($filter) . "&topic=$topic");
}
elseif ($mode == "del_lang") {

    require $xcart_dir . "/include/safe_mode.php";

    db_query_param("DELETE FROM $sql_tbl[languages] WHERE code=:code", ['code' => $language]);
    db_query_param("DELETE FROM $sql_tbl[products_lng] WHERE code=:code", ['code' => $language]);

    $lngs = func_query_column("SELECT code FROM $sql_tbl[languages] GROUP BY code");
    if (!empty($lngs)) {
        foreach ($lngs as $v) {
            func_data_cache_get("languages", [$v], true);
        }
    }

    if (!empty($active_modules['Fancy_Categories'])) {
        func_fc_remove_cache(10, false, false, [$language]);
    }

    $top_message = [
        "content" => func_get_langvar_by_name("lbl_languages_has_been_deleted"),
    ];

    func_header_location("languages.php?lang_deleted");
}
elseif ($mode == "export" && $language) {
    $smarty->assign("csv_delimiter", $delimiter);

    $lng_res = func_query_first_cell_param("SELECT value FROM $sql_tbl[languages] WHERE name= :name", ['name' => "language_" . $language]);

    $data = func_query("SELECT * FROM $sql_tbl[languages] WHERE code='$language' ORDER BY name");
    if ($data) {
        foreach ($data as $key => $value) {
            $data[$key]["value"] = "\"" . preg_replace("/\"/i", "\"\"", $value["value"]) . "\"";
        }

        $smarty->assign("data", $data);

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=lng_" . $lng_res . ".csv");

        $_tmp_smarty_debug = $smarty->debugging;
        $smarty->debugging = false;

        func_display("main/lng_export.tpl", $smarty);

        $smarty->debugging = $_tmp_smarty_debug;
        exit;
    }
}

if (!func_query_first_cell_param("SELECT COUNT(*) FROM $sql_tbl[languages] WHERE code = :code", ['code' => $config['default_admin_language']])) {
    $config['default_admin_language'] = func_query_first_cell_param("SELECT code FROM $sql_tbl[languages] ORDER BY code", []);
}

$sql
    = /** @lang MySQL */
    <<<SQL
   SELECT cntr.*, 
        IFNULL(lng1c.value, lng2c.value) AS country, 
        IFNULL(lng1l.value, lng2l.value) AS language 
        
FROM {$sql_tbl['countries']} AS cntr
LEFT JOIN {$sql_tbl['languages']} AS lng1c 
    ON lng1c.name = CONCAT('country_', cntr.code) AND lng1c.code = :shop_lang
LEFT JOIN {$sql_tbl['languages']} AS lng2c 
    ON lng2c.name = CONCAT('country_', cntr.code) AND lng2c.code = :default_lang
LEFT JOIN {$sql_tbl['languages']} AS lng1l 
    ON lng1l.name = CONCAT('language_', cntr.code) AND lng1l.code = :shop_lang
LEFT JOIN {$sql_tbl['languages']} AS lng2l 
    ON lng2l.name = CONCAT('language_', cntr.code) AND lng2l.code = :default_lang
    
WHERE (lng1l.value != '' OR lng2l.value != '') 
GROUP BY LANGUAGE 
ORDER BY LANGUAGE
SQL;

$new_languages = func_query_param($sql, ['shop_lang' => $shop_language, 'default_lang' => $config['default_admin_language']]);

if ($mode == "add_lang") {

    require $xcart_dir . "/include/safe_mode.php";

    if (!$new_language) {
        func_header_location("languages.php");
    }

    $exists_result = func_query_first_param("SELECT * FROM $sql_tbl[languages] WHERE code=:code", ['code' => $new_language]);

    if (!$exists_result) {
        $result = func_query_param("SELECT * FROM $sql_tbl[languages] WHERE code=:code", ['code' => $config['default_customer_language']]);
        if ($result) {
            foreach ($result as $key => $value) {
                db_query_param("INSERT INTO $sql_tbl[languages] (code, name, value, topic) VALUES (:code, :name, :value, :topic)", ['code' => $new_language, 'name' => $value["name"], 'value' => $value["value"], 'topic' => $value['topic']]);
            }
        }

        $lngs = func_query_column("SELECT code FROM $sql_tbl[languages] GROUP BY code");
        if (!empty($lngs)) {
            foreach ($lngs as $v) {
                func_data_cache_get("languages", [$v], true);
            }
        }
    }

    if ($source == "server" && !empty($localfile)) {
        # File is located on the server
        if (func_allow_file($localfile, true) && is_file($localfile)) {
            $import_file = $localfile;
            $is_import = true;
        }
        else {
            $top_message["content"] = func_get_langvar_by_name("msg_err_file_wrong");
            $top_message["type"] = "E";
            $serverfile = $localfile;
            func_header_location("languages.php");
        }
    }
    elseif ($source == "upload" && $import_file && $import_file != "none") {
        $import_file = func_move_uploaded_file("import_file");
        $is_import = true;
    }
    else {
        $is_import = false;
    }
    if ($is_import) {
        if ($fp = func_fopen($import_file, "r", true)) {
            $lngs = $avail_languages;
            while ($columns = fgetcsv($fp, 65536, $delimiter)) {
                if (sizeof($columns) >= 4) {
                    $res = func_query_first_param("SELECT * FROM $sql_tbl[languages] WHERE name=:name AND $sql_tbl[languages].code = :code LIMIT 1", ['name' => $columns[0], 'code' => $new_language]);
                    if ($res) {
                        db_query_param("UPDATE $sql_tbl[languages] SET value=:value, topic=:topic WHERE name=:name AND code=:code", ['value' => $columns[1], 'topic' => $columns[3], 'name' => $columns[0], 'code' => $new_language]);
                    }
                    else {
                        db_query_param("INSERT INTO $sql_tbl[languages] (code, name, value, topic) VALUES (:code, :name, :value, :topic)", ['code' => $new_language, 'name' => $columns[0], 'value' => $columns[1], 'topic' => $columns[3]]);
                    }
                }
            }
            fclose($fp);
        }
    }

    func_data_cache_get("charsets", [], true);

    func_header_location("languages.php?language=$new_language&topic=$topic&page=$page");
}

if ($mode == "change" && !empty($language)) {

    require $xcart_dir . "/include/safe_mode.php";

    if (empty($d_langs)) {
        $d_langs = [];
    }

    if (in_array($language, $d_langs)) {
        $x = array_search($result["code"], $d_langs);
        unset($d_langs[$x]);
    }
    else {
        $d_langs[] = $language;
    }
    $d_langs = array_unique($d_langs);

    foreach ($d_langs as $k => $v) {
        if (empty($v)) {
            unset($d_langs[$k]);
        }
    }

    db_query_param("UPDATE $sql_tbl[config] SET value=:value WHERE name='disabled_languages'", ['value' => implode("|", $d_langs)]);

    func_header_location("languages.php?language=$language&mode_changed");
}
if ($mode == "change_defaults") {

    require $xcart_dir . "/include/safe_mode.php";

    if (!empty($new_customer_language)) {
        db_query_param("update $sql_tbl[config] set value=:value where name='default_customer_language'", ['value' => $new_customer_language]);
    }
    if (!empty($new_admin_language)) {
        db_query("update $sql_tbl[config] set value=:value where name='default_admin_language'", ['value' => $new_admin_language]);
    }

    func_header_location("languages.php?language=$language");
}

if ($language) {
    $r = func_query_first_param("SELECT code, charset FROM $sql_tbl[countries] WHERE code=:code", ['code' => $language]);
    $r['language'] = func_query_first_cell_param("SELECT value FROM $sql_tbl[languages] WHERE name = :name", ['name' => 'language_' . $language]);
    $smarty->assign("default_charset", $r["charset"]);

    $lang_disabled = (in_array($r["code"], $d_langs) ? "Y" : "N");
    $smarty->assign("lang_disabled", $lang_disabled);

    $params = ['code' => $language];
    if ($topic) {
        $topic_condition = " AND topic = :topic ";
        $params['topic'] = $topic;
    }
    else {
        $topic_condition = " AND topic <> ''";
    }

    if (!empty($filter)) {
        $filter_condition = "AND (name LIKE :filter OR value LIKE :filter)";
        $params['filter'] = '%' . $filter . '%';
    }
    else {
        $filter_condition = "";
    }

    $query = "SELECT * FROM $sql_tbl[languages] WHERE code = :code {$filter_condition} {$topic_condition} order by topic, name";

    $objects_per_page = 20;

    $result = db_query_param($query, $params);
    $total_labels_in_search = db_num_rows($result);

    if ($total_labels_in_search > 0) {
        $total_nav_pages = ceil($total_labels_in_search / $objects_per_page) + 1;
        include $xcart_dir . "/include/navigation.php";

        $smarty->assign("data", func_query_param("$query LIMIT $first_page, $objects_per_page", $params));
    }

    $smarty->assign("total_labels_found", $total_labels_in_search);
    $smarty->assign("navigation_script", "languages.php?language=$language&topic=$topic&filter=" . urlencode($filter));
}

$smarty->assign("filter", $filter);
$smarty->assign("languages", $languages);
$smarty->assign("new_languages", $new_languages);

$smarty->assign("topics", $topics);

$smarty->assign("upload_max_filesize", ini_get("upload_max_filesize"));
$smarty->assign("my_files_location", func_get_files_location());
if (!empty($serverfile)) {
    $smarty->assign("localfile", $serverfile);
    $serverfile = false;
}
else {
    $smarty->assign("localfile", func_get_files_location() . "/lng_file.csv");
}

$smarty->assign("main", "languages");

# Assign the current location line
$smarty->assign("location", $location);

@include $xcart_dir . "/modules/gold_display.php";
func_display("admin/home.tpl", $smarty);
