<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.01.2017
 * Time: 15:15
 */

namespace Xcart\App\Controller;

use Xcart\App\Traits\SmartyRenderTrait;

class AdminController extends Controller
{
    use SmartyRenderTrait;

    public function renderInternal($view, $params)
    {
        return $this->renderSmarty("admin/home.tpl", [
            'single_mode' => true,
            'main'        => 'raw_html',
            'content'     =>  $this->render($view, $params),
        ]);
    }

}