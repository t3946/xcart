<?php

namespace Modules\Core\Middleware;

use Modules\Core\TemplateLibraries\HumanizeLibrary;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

class StatisticMiddleware extends Middleware
{

    private $start;

    public function processRequest($request)
    {
        if (!Cli::isCli() || !$request->getIsAjax()) {
            $this->start = time();
        }
    }

    public function processView($request, &$output)
    {
        if (!Cli::isCli() || !$request->getIsAjax()) {
            $cq = Xcart::app()->db->getConnection()->getCountQueries();
            $memory = HumanizeLibrary::humanizeSize(memory_get_usage());


            $output .= <<<HTML
<section class="statistic">
    <div class="row">
        <div class="columns large-3">
            Memory used: {$memory}
        </div>
        <div class="columns large-3">
            Query count: {$cq}
        </div>
    </div>
</section>
HTML;
        }
    }
}
