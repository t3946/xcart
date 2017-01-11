<?php
namespace Xcart\App\Traits;

trait RenderTrait
{
    /**
     * @param $view
     * @param array|null $params
     *
     * @return string
     */
    public function view($view, array $params = null)
    {
        $render = \Templater::getInstance();

        if (!empty($params)) {
            foreach ($params as $name => $param) {
                $render->assign($name, $param);
            }
        }

        return func_display($view, $render, false);
    }
}