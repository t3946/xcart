<?php
namespace Modules\Reports;

use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class ReportsModule extends Module
{
    public static function getAdminMenu()
    {
        $user = Xcart::app()->user;
        $router = Xcart::app()->router;

        return [
            [
                'icon' => 'fa fa-file-text-o',
                'name' => 'Order reports',
                'route' => $router->url('reports:index'),
                'items' => ($user && $user->getIsSuperuser()) ? [
                    [
                        'icon' => 'fa fa-pencil-square-o',
                        'name' => 'Reports settings',
                        'route' => $router->url('reports:admin_reports'),
                    ],
                ] : false,
            ],
        ];
    }

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();
        $template->addModifier('west_style', function($price)
        {
            $res = $price;
            if (!empty($price)) {
                if (floatval($price) < 0) {
                    $res = "(".ltrim($price, '-').")";
                }
            }
            return $res;
        });

        $template->addModifier('aggregate_function', function($value, $agg_func)
        {
            $res = '';
            if (function_exists($agg_func)) {
                $res = $agg_func($value);
            } else {
                if ($agg_func == 'array_avg' && is_array($value)) {
                    $res = array_sum($value) / count($value);
                }
            }
            return $res;
        });
    }
}