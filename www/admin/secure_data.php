<?php
if (!defined('XCART_SESSION_START')) {
    header("Location: ../");
    die("Access denied");
}
global $login;

if (!in_array($login, ['sergey2', 'elena', 'igor']))
    func_header_location("error_message.php?access_denied&id=25");
global $xcart_dir;
x_load('crypt');

if ($REQUEST_METHOD == 'POST' && $mode == "update") {
    if (!empty($secure_data)) {
        foreach ($secure_data as $id => $aSecureData) {
            $aToUpdate = ['id' => $id, 'data' => text_crypt($aSecureData), 'orderby' => $secure_data_order[$id]];
            if (!func_array2insert('secure_data', $aToUpdate, true, true)) {
                func_array2update('secure_data', $aToUpdate, "id=$id");
            }
            db_query("DELETE FROM " . $sql_tbl['secure_data_users'] . " WHERE secure_data_id = $id");
        }
    }
    if (!empty($secure_data_use)) {
        foreach ($secure_data_use as $id => $aUsers) {
            foreach ($aUsers as $sUser) {
                $aToUpdate = ['secure_data_id' => $id, 'login' => $sUser];
                func_array2insert('secure_data_users', $aToUpdate, true);
            }
        }
    }

    if (!empty($delete_data_checkbox)) {
        foreach ($delete_data_checkbox as $delete_secure_data => $value) {
            db_query("DELETE FROM " . $sql_tbl['secure_data_users'] . " WHERE secure_data_id = $delete_secure_data");
            db_query("DELETE FROM " . $sql_tbl['secure_data'] . " WHERE id = $delete_secure_data");

        }
    }

    $top_message["content"] = 'Done.';
    $top_message["type"] = "I";
    func_header_location("configuration.php?option=Secure_data");
}


$aSecureData = func_query("SELECT * FROM $sql_tbl[secure_data] ORDER BY orderby");
$aSecureDataLogins = func_query_hash("SELECT * FROM $sql_tbl[secure_data_users]", "secure_data_id");

if (empty($aSecureData)) {
    $aSecureData[] = ['id' => 1];
} else {
    foreach ($aSecureData as &$SecureData) {
        $SecureData['data'] = stripslashes(text_decrypt($SecureData['data']));
    }
}

if (!empty($aSecureDataLogins)) {
    $aUsersInSecureData = [];
    foreach ($aSecureDataLogins as $iSecureDataId => $aSelectedUsers) {
        foreach ($aSelectedUsers as $sLogin)
            $aUsersInSecureData[$iSecureDataId][] = $sLogin['login'];
    }
}


$smarty->assign("aSecureData", $aSecureData);
$aCustomers = Xcart\Customer::getCustomersByType('A');
if (!empty($aCustomers)) {
    $aCustomersSelect = [];
    foreach ($aCustomers as $oCustomers) {
        $aCustomersSelect[$oCustomers->getCustomerLogin()] = $oCustomers->getCustomerFullName();
    }
}
$smarty->assign("aCustomers", $aCustomersSelect);
$smarty->assign("aSecureDataLogins", $aUsersInSecureData);
?>
