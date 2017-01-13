<?php
namespace Xcart\App\Traits;

trait RenderTrait
{
    /**
     * @param $template
     * @param array|null $params
     *
     * @return string
     */
    public function render($template, array $params = [])
    {
        $render = \Templater::getInstance();

        if (!empty($params)) {
            foreach ($params as $name => $param) {
                $render->assign($name, $param);
            }
        }

        return func_display($template, $render, false);
    }
}