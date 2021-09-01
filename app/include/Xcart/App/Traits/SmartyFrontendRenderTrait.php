<?php
namespace Xcart\App\Traits;

trait SmartyFrontendRenderTrait
{
    use SmartyRenderTrait;

    public function renderInSmarty($view, array $params = [])
    {
        return $this->renderSmarty("customer/home.tpl", [
            'single_mode' => true,
            'main'        => 'raw_html',
            'content'     =>  $this->render($view, $params),
        ]);
    }
}