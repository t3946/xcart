<?php

function l($data = null, $title = ""){


 $tmp_cart_dir = realpath(dirname(__FILE__));
 if (substr($tmp_cart_dir, -1) == "/")
         $tmp_cart_dir = substr($tmp_cart_dir, 0, -1);

 $tmp_cart_log = $tmp_cart_dir . "/logs.txt";

    if (!empty($data) || !empty($title)) {
        error_log(($title?" $title ":" ").print_r($data,true)."\n", 3, $tmp_cart_log);
    } else {
        //$file = pathinfo(__FILE__);
        //$root = $file['dirname'];
        //$dt = debug_backtrace();
        //$str = '';
        //foreach ($dt as $i => $val) {
            //$str .= "  " . str_replace($root, '', $val[file]);
            //$args = '';
            //foreach ($val['args'] as $j => $val2) {
            //    $args .= "\n    [$j] => " . print_r($val2, true);
            //}
            //$str .= ":$val[line] fun:$val[function]()$args \n";
        //}
        //error_log("Trace: \n$str", 3, $tmp_cart_log);
    }
}

?>
