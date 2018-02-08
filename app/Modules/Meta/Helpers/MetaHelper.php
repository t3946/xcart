<?php

namespace Modules\Meta\Helpers;

use Modules\Meta\Components\MetaTrait;
use Modules\Meta\Models\Meta;
use Modules\Meta\Models\MetaTemplate;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\ErrorHandler;
use Xcart\App\Main\Xcart;
use Xcart\App\Traits\RenderTrait;

class MetaHelper
{
    use RenderTrait;

    /**
     * @param \Modules\Meta\Components\MetaTrait $controller
     * @param null $canonical
     */
    public static function getMeta($controller, $canonical = null)
    {
        if ($controller instanceof ErrorHandler) {
            return;
        }

        $uri = Xcart::app()->request->getUrl();
        $meta = self::fetchMeta($uri);
        if ($meta === null && ($pos = strpos($uri, '?')) !== false) {
            // Remove query params from uri
            $meta = self::fetchMeta(substr($uri, 0, $pos));
        }

        $site = Xcart::app()->getModule('Sites')->getSite();

        if ($meta) {
            echo self::renderTemplate('meta/meta_helper.tpl', [
                'title' => self::formatTitle($controller, $meta->title, $site, $meta),
                'canonical' => $canonical,
                'description' => $meta->description,
                'site' => $site
            ]);
        }
        elseif ($controller && $metaTemplate = MetaTemplate::objects()->filter(['code' => $controller->getMetaTemplate()])->limit(1)->get()) {
            $metaTemplate->params = $controller->getMetaTemplateParams();

            echo self::renderTemplate('meta/meta_helper.tpl', [
                'title' => self::cleanString( self::formatTitle($controller, $metaTemplate->renderTitle()) ),
                'canonical' => $canonical,
                'description' => self::cleanString( $metaTemplate->renderDescription() ),
                'site' => $site
            ]);
        }
        else if ($controller) {
            echo self::renderTemplate('meta/meta_helper.tpl', [
                'title' => self::formatTitle($controller, null, $site),
                'canonical' => $canonical,
                'description' => $controller->getDescription(),
                'site' => $site
            ]);
        }
    }

    /**
     * @param MetaTrait $controller
     * @param null $title
     * @param SiteModel|null $site
     * @param Meta $metaModel
     * @return string
     */
    protected static function formatTitle($controller, $title = null, $site = null, $metaModel = null)
    {
        $data = [];

        if ($metaModel && $metaModel->is_custom) {
            $data = [];
        }

        if ($title) {
            $data[] = $title;
        }
        else if ($controller && $controller->title) {
            foreach ($controller->title as $title) {
                $data[] = $title;
            }
        }

        $data = array_reverse($data);

        if ($site) {
            $data[] = $site->getName();
        }

        return implode(' - ', $data);
    }

    protected static function cleanString($str)
    {
        $t = preg_replace("/(\r?\n){2,}/", "", $str);
        $t = preg_replace("/(\s+)/", " ", $t);
        return trim( $t );
    }

    protected static function fetchMeta($uri)
    {
        $qs = Meta::objects()->filter(['url' => $uri]);
        if (Xcart::app()->getModule('Meta')->onSite) {
            $qs = $qs->currentSite();
        }
        return $qs->limit(1)->get();
    }
} 