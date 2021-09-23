<?php
namespace Xcart\App\Traits;

use Modules\Core\TemplateLibraries\CommonLibrary;
use Xcart\App\Main\Xcart;

trait SmartyRenderTrait
{
    /**
     * @param $template
     * @param array|null $params
     *
     * @return string
     */
    public function renderSmarty($template, array $params = [])
    {
        $render = \Templater::getInstance();

        if (!empty($params)) {
            foreach ($params as $name => $param) {
                $render->assign($name, $param);
            }
        }

        $render->assign('breadcrumbs', CommonLibrary::renderBreadcrumbs(['template' => 'admin/_breadcrumbs.tpl']));

        Xcart::app()->errorHandler->errHandler = false;

        return func_display($template, $render, false);
    }
}