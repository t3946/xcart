<?php

use Xcart\Images\Splash;

define('USE_TRUSTED_POST_VARIABLES', 1);

global $xcart_dir, $smarty, $REQUEST_METHOD;
require "./auth.php";
$trusted_post_variables = ["splash_file"];
require $xcart_dir . "/include/security.php";

$dir = '/images/splashes';
$location[] = array("Product splashes", "");
if ($REQUEST_METHOD == 'POST') {
    if (!empty($_POST['splash_scheckbox']) && is_array($_POST['splash_scheckbox'])) {
        foreach ($_POST['splash_scheckbox'] as $sp_id => $item) {
            $oSplash = $filePath = null;
            $bError = false;
            if (!empty($_FILES['splash_file']) && is_array($_FILES['splash_file']) && !$_FILES['splash_file']["error"][$sp_id]) {
                if (!file_exists($xcart_dir.'/'.$dir)) {
                    $bError = !mkdir($xcart_dir.'/'.$dir, 0775, true);
                }
                if (!$bError) {
                    $filePath = sprintf("{$dir}/%s", $_FILES['splash_file']["name"][$sp_id]);
                    $sNewFilePath = $xcart_dir . $filePath;
                    if (!move_uploaded_file($_FILES["splash_file"]['tmp_name'][$sp_id], $sNewFilePath)) {
                        $filePath = '';
                    }
                }
            }
            if (!$bError) {
                if ($sp_id) {
                    $oSplash = Splash::objects()->filter(['id' => $sp_id])->get();
                }
                if ($oSplash && $_POST['mode'] == 'update_splashes') {
                    $oSplash->setAttributes([
                        'id' => $oSplash->id,
                        'splash_name' => $_POST['splash_name'][$sp_id],
                        'comment' => $_POST['splash_comment'][$sp_id],
                        'active' => $_POST['splash_active'][$sp_id],
                    ]);
                    if ($filePath) {
                        $oSplash->setAttribute('image_path', $filePath);
                    }
                    $oSplash->_update();

                } elseif ($_POST['mode'] == 'add_splash') {
                    (new Splash())->setAttributes(
                        ['splash_name' => $_POST['splash_name'][$sp_id],
                            'comment' => $_POST['splash_comment'][$sp_id],
                            'image_path' => $filePath,
                            'active' => $_POST['splash_active'][$sp_id]])
                        ->_insert();
                }}
        }
    }
    func_header_location('/admin/configuration.php?option=Product_splashes');
}

$smarty->assign("splashes", Splash::objects()->all());

# Assign the current location line
$smarty->assign("location", $location);
$smarty->assign("main", "product_splashes");

