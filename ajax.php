<?php
//require "./auth.php";
$ajax_action = 'add_retail_trust';
extract($_POST);
switch ($ajax_action) {
    case 'add_retail_trust':
        $aParams = [];
        if (!empty($params)) {
            foreach ($params as $param)
                $aParams[$param['name']][] = $param['value'];
        }
        print_r($aParams);
        break;

}