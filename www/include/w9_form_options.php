<?php
global $REQUEST_METHOD;
if ($REQUEST_METHOD == 'POST') {
    if (!empty($_FILES['w9_form_file'])) {
        global $xcart_dir, $login;
        $aPathInfo = (pathinfo($_FILES['w9_form_file']['name']));
        $sNewFilePath = $xcart_dir . '/files/w9_form_files/' . $_FILES['w9_form_file']['name'];
        $allow_extensions = ['pdf'];
        if (in_array($aPathInfo['extension'], $allow_extensions)) {
            if (move_uploaded_file($_FILES['w9_form_file']['tmp_name'], $sNewFilePath)) {
                \Xcart\Config::model(['name' => 'w9_form_file'])->setValue($_FILES['w9_form_file']['name'])->_update();
            } else {
                $top_message["content"] = 'Error file upload';
                $top_message["type"] = "E";
            }
        }
    }
}

$smarty->assign('oW9FormConfig', \Xcart\Config::model(['name' => 'w9_form_file']));
