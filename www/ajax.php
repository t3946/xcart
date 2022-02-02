<?php
require "./auth.php";
global $config, $ajax_action;


switch ($ajax_action) {
    case 'add_cart_group':
        if (isset($_POST['products'])) {
            $res = [];
            foreach ($_POST['products'] as $product_id => $product_info) {
                $action = "cart.php";
                $is_group = true;
                $productid = $product_id;
                $amount = $product_info['quantity'];
                $product_options = isset($product_info['options']) ? $product_info['options'] : null;
                include "ajax_add_to_cart.php";
                $res[] = $return;
                if (isset($return['error']) && $return['error'] == 'Y') {
                    break;
                }
            }
        }
        print(json_encode(end($res)));
        break;

}