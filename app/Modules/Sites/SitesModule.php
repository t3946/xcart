<?php
namespace Modules\Sites;

use Modules\Sites\Helpers\CurrentSiteHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Helpers\Collection;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;

class SitesModule extends Module
{
//    use AdminTrait;

    public $defaultStore = 'AR';
    public $modelClass = 'Modules\Sites\Models\SiteModel';

    /**
     * @var \Modules\Sites\Models\SiteModel
     */
    private $_site;
    private $_selected_site;
    private $_default_site;

    /**
     * @var \Modules\Sites\Models\SiteConfigModel
     */
    private $_config = [];

    public function setSite($model)
    {
        if ($model) {
            $this->_site = $model;
        }
    }

    /**
     * @return \Modules\Sites\Models\SiteModel|null
     * @throws \Exception
     */
    public function getSite($default = true)
    {
        if (!$this->_site && Xcart::app()->getIsWebMode() && !$this->_default_site) { //@TODO: remove for future
            $this->setSite(CurrentSiteHelper::check(Xcart::app()->request));
        }

        if (!$this->_site) {
            $this->initDefaultSite();
        }

        if ($default) {
            return $this->_site ?: $this->_default_site;
        }

        return $this->_site;
    }

    public function setSelectedSite($site)
    {
        if ($site) {
            $this->_selected_site = $site;
            Xcart::app()->request->session->add('current_storefront', $site->pk);
        }
    }

    public function getSelectedSite()
    {
        if (!$this->_selected_site)
        {
            if ( $sf_id = Xcart::app()->request->session->get('current_storefront') ) {
                $this->setSelectedSite(SiteModel::objects()->get(['pk' => $sf_id]));
            }
            else {
               $this->setSelectedSite($this->getSite());
            }
        }

        return $this->_selected_site;
    }

    public function getSiteConfig()
    {
        $key = $this->getSite()->storefrontid;
        if (empty($this->_config[$key])) {
            $this->_config[$key] = new Collection();

            foreach ($this->getSite()->config->all() as $item) {
                $this->_config[$key][$item->name] = $item;
            }
        }

        return $this->_config[$key];
    }

    public function initDefaultSite()
    {
        /** @var SiteModel $model */
        if (!$this->_site && $model = SiteModel::objects()->get(['code' => $this->defaultStore])) {
            $this->_default_site = $model;
        }
        else {
            throw new \Exception("Default site not found for store '{$this->defaultStore}'");
        }
    }


    public static function onApplicationRun()
    {
        $renderer = Xcart::app()->template->getRenderer();

        $renderer->addAccessorCallback('getSiteConfig', function(){
            return Xcart::app()->getModule('Sites')->getSiteConfig();
        });

        $renderer->addAccessorCallback('getSite', function(){
            return Xcart::app()->getModule('Sites')->getSite();
        });
    }
}