<?php

use Xcart\Images\Splash;

define('USE_TRUSTED_POST_VARIABLES', 1);

global $xcart_dir, $smarty, $REQUEST_METHOD;
require "./auth.php";
$trusted_post_variables = ["splash_file"];
require $xcart_dir . "/include/security.php";


$location[] = array("Product splashes", "");
if ($REQUEST_METHOD == 'POST') {
    if (!empty($_FILES) && is_array($_FILES) && !empty($_FILES['splash_file']) && !$_FILES['splash_file']['error']) {
        $filePath = sprintf("/images/splashes/%s", $_FILES["splash_file"]["name"]);
        $sNewFilePath = $xcart_dir . $filePath;
        if (move_uploaded_file($_FILES["splash_file"]['tmp_name'], $sNewFilePath)) {
            Splash::create([
                'splash_name' => addslashes($_POST['splash_name']),
                'image_path' => $filePath,
                'comment' => addslashes($_POST['splash_comment']),
                'active' => $_POST['splash_active']
            ])->_insert();
        }
    }
}

$smarty->assign("splashes", Splash::objects()->all());

# Assign the current location line
$smarty->assign("location", $location);
$smarty->assign("main", "product_splashes");

