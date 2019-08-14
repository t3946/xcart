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
    public static function trn($trn, $dict = 'main')
    {
        return Translate::getInstance()->t($dict, $trn);
    }


    /**
     * @name t
     * @kind function
     * @return string
     */
    public static function t_func($params)
    {
        $trn = reset($params);
        $dict = empty($params['dict']) ? 'main' : $params['dict'];

        return self::trn($trn, $dict);
    }
}