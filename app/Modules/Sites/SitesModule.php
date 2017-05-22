<?php
namespace Modules\Sites;

use Mindy\QueryBuilder\Q\QOr;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Module\Module;

class SitesModule extends Module
{

    public $defaultStore = 'AR';
    public $modelClass = 'Modules\Sites\Models\SiteModel';

    /**
     * @var \Modules\Sites\Models\Site
     */
    private $_site;

    public function setSite(SiteModel $model)
    {
        $this->_site = $model;
    }

    public function getSite()
    {
        if (!$this->_site) {
            $this->initDefaultSite();
        }

        return $this->_site;
    }

    public function initDefaultSite()
    {
        if ($model = SiteModel::objects()->get(new QOr(['code' => $this->defaultStore, 'storefrontid' => $this->defaultStore])))
        {
            $this->setSite($model);
        }
        else {
            throw new \Exception("Default site not found for store '{$this->defaultStore}'");
        }
    }
}