<?php
namespace Modules\Meta\Middleware;

use Modules\Meta\Models\Meta;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

class MetaMiddleware extends Middleware
{
    public function processRequest($request)
    {
        /** @var SiteModel $site_model */
        $site_model = Xcart::app()->getModule('Sites')->getSite();
        if ($meta = Meta::objects()->filter(['url' => $request->getUrl(), 'site_code' => $site_model->code])->limit(1)->get()) {
            $metaInfo = [
                'title' => $meta->title,
                'keywords' => $meta->keywords,
                'description' => $meta->description,
                'canonical' => $meta->url
            ];

            $controller = $this;

            foreach($metaInfo as $key => $value) {
                $controller->set{ucfirst($key)} = $value;
            }
        }
    }
}
