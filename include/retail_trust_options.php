<?php
if ($REQUEST_METHOD == 'POST') {
    if (!empty($retail_trust_order_status)) {
        db_query("UPDATE $sql_tbl[config] SET value='$retail_trust_order_status' WHERE name='retail_trust_order_status'");
    }
}
$selected_status = $config['retail_trust_order_status'];

$status_types = array(
    'CB' => func_get_langvar_by_name('lbl_cust_bus_payment_status'),
    'DC' => func_get_langvar_by_name('lbl_distr_cust_shipping_status'),
    'BD' => func_get_langvar_by_name('lbl_bus_distr_payment_status'),
    'RU' => 'REF TO US status',
    'PO' => 'Check transit status',
);

$smarty->assign('status_types', $status_types);

$smarty->assign('status', $selected_status);