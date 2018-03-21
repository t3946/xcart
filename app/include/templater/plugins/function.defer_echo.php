<?php

/**
 * @param $params
 * @param Templater $smarty
 */
function smarty_function_defer_echo($params, &$smarty)
{
    global $defer_array;

    $params['type'] = $params['type'] ?: 'js';
    if ($params['type']) {

        $a = $defer_array ?: [];

        if (!empty($a[$params['type']])) {

            $arr = "['" . implode("','",$a[$params['type']]) . "']";

            echo <<<INLINE
                var {$params['type']} = {$arr} 
INLINE;
        }
    }
}