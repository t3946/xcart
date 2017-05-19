<?php

use Modules\Core\Models\GlobalConfigModel;
use Modules\Sites\Models\SiteConfigModel;
use Modules\Sites\Models\SiteModel;

global $smarty;
if (!empty($_POST['template_submit'])) {
    if ($_POST['storefront'] && is_array($_POST['storefront'])) {
        foreach ($_POST['storefront'] as $key => $store) {
            /*switch ($store) {
                case -1:
                    if ($m = GlobalConfigModel::objects()->filter(['name' => 'thank_you_days'])->get()) {
                        $m->value = $_POST['thank_you_days'][$key];
                        $m->save();
                    }
                    if ($m = GlobalConfigModel::objects()->filter(['name' => 'thank_you_from'])->get()) {
                        $m->value = $_POST['thank_you_from'][$key];
                        $m->save();
                    }
                    if ($m = GlobalConfigModel::objects()->filter(['name' => 'thank_you_subject'])->get()) {
                        $m->value = $_POST['thank_you_subject'][$key];
                        $m->save();
                    }
                    if ($m = GlobalConfigModel::objects()->filter(['name' => 'thank_you_message_body'])->get()) {
                        $m->value = $_POST['thank_you_message_body'][$key];
                        $m->save();
                    }
                    break;
                default :

                    break;
            }*/
        }

    } var_dump($_POST);
}

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
