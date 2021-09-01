<?php
namespace Modules\Core\TemplateLibraries;

use Xcart\App\Template\TemplateLibrary;
use Xcart\App\Translate\Translate;

class TranslateLibrary extends TemplateLibrary
{
    /**
     * @name t
     * @kind accessorFunction
     * @return string
     */
    public static function trn($trn, $m = null, $n = null, $dict = 'main')
    {
        if ($n !== null) {
            $param = ['%count%' => $n];
        };
        if ($m !== null) {
            $trn .= '|' . $m;
        }
        return Translate::getInstance()->t($dict, $trn, $param ?? []);
    }


    /**
     * @name t
     * @kind function
     * @return string
     */
    public static function t_func($params)
    {
        [$trn, $m, $n] = $params;
        $dict = empty($params['dict']) ? 'main' : $params['dict'];

        return self::trn($trn, $m, $n, $dict);
    }
}