<?php

use Modules\Core\Models\GlobalConfigModel;
use Modules\Sites\Models\SiteConfigModel;
use Modules\Sites\Models\SiteModel;

global $smarty;

//if ($_POST['store']

$aModels = GlobalConfigModel::objects()
    ->filter(['category' => 'thankyou_for_order'])
    ->order('orderby')
    ->all();

$aSiteModels = SiteConfigModel::objects()
    ->filter(['category' => 'thankyou_for_order'])
    ->order('orderby')
    ->all();

$sites = SiteModel::objects()->order(['domain'])->all();
$storefrontsConfig = [];
if ($aSiteModels) {
    foreach ($aSiteModels as $siteModel) {
        if (empty($storefrontsConfig[$siteModel->storefrontid])) {
            $storefrontsConfig[$siteModel->storefrontid]['model'] = SiteModel::objects()->get(['storefrontid' => $siteModel->storefrontid]);
        }
        $storefrontsConfig[$siteModel->storefrontid]['config'][] = $siteModel;
    }
}

$smarty->assign('global_config', $aModels);
$smarty->assign('site_config', $storefrontsConfig);
$smarty->assign('sites', $sites);
