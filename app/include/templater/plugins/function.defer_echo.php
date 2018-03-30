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

        if (in_array($params['type'], ['css','js']) &&!empty($a[$params['type']])) {

            $arr = "['" . implode("','",$a[$params['type']]) . "'];";

            echo <<<INLINE
var {$params['type']} = {$arr} 
INLINE;
        }

        switch ($params['type']) {
            case 'css' :
                echo \Fenom\Modifier::strip(<<<INLINE
                    var loadDeferredStyles = function() {
                        if (typeof css !== 'undefined' && css.length) {
                            while (css.length) {
                                var l = document.createElement('link');
                                l.rel = 'stylesheet';
                                l.href = css.shift();
                                var h = document.getElementsByTagName('head')[0];
                                h.parentNode.insertBefore(l, h);
                            }
                        }
                    };
                    var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
                        window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
                    if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 40); });
                    else window.addEventListener('load', loadDeferredStyles);                    
INLINE
,false);
                break;
            case 'js' :
                echo \Fenom\Modifier::strip(<<<INLINE
                     if (typeof js !== 'undefined' && js.length) {
                        while(js.length) {
                            var j = document.createElement("script");
                            j.type = "text/javascript";
                            if (js.length === 1) {
                                j.onload=function(){
                                    afterJSLoaded();
                                };
                            }
                            j.src = js.shift();
                            document.body.appendChild(j);
                        }
                    }
INLINE
,false);
                break;
            case 'js_inline' :
                if ($a[$params['type']]) {
                    $res = null;
                    foreach ($a[$params['type']] as $src) {
                        $res .= $src;
                    }
                    echo \Fenom\Modifier::strip($res, false);
                }

                break;
        }
    }
}