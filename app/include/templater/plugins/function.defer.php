<?php

/**
 * @param $params
 * @param Templater $smarty
 */
function smarty_function_defer($params, &$smarty)
{
    global $defer_array;

    $params['type'] = $params['type'] ?: 'js';

    if ($params['file']) {
        $defer_array[$params['type']][] = $params['file'];
        $defer_array[$params['type']] = array_unique($defer_array[$params['type']]);
    }
}