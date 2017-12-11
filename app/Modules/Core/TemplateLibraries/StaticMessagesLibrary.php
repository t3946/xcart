<?php
namespace Modules\Core\TemplateLibraries;


use Mindy\QueryBuilder\Q\QOr;
use Modules\Core\Models\StaticNotificationModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Template\TemplateLibrary;
use Xcart\App\Traits\RenderTrait;

class StaticMessagesLibrary extends TemplateLibrary
{
    use RenderTrait;
    /**
     * @name render_static_notifications
     * @kind function
     * @return string
     */
    public static function renderStaticMessages($params)
    {
        $template = isset($params['template']) ? $params['template'] : 'base/_notifications.tpl';

        $qs = StaticNotificationModel::objects()->filter([
            'active' => true,
            new QOr(['start_at__isnull' => true, 'start_at__lte' => new \DateTime()]),
            new QOr(['end_at__isnull' => true, 'end_at__gte' => new \DateTime()]),
        ]);

        if ($idx = Xcart::app()->request->cookie->get('notification_hide_idx')) {

            $idx = json_decode($idx);
            $idx = array_unique($idx);

            if ($idx && is_array($idx)) {
                $qs->exclude(['pk__in' => $idx]);
            }
        }

        $models = $qs->all();

        if ($models) {
            return self::renderTemplate($template, [
                'models' => $models
            ]);
        }
    }
}