<?php

use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\Q\QOrNot;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Sites\Models\SiteConfigModel;
use Modules\Sites\Models\SiteModel;

global $smarty;
if (!empty($_POST['template_submit'])) {
    if ($_POST['storefront_template'] && is_array($_POST['storefront_template'])) {
        $edited_stores = [];
        foreach ($_POST['storefront_template'] as $key => $store) {
            switch ($store) {
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
                        $m->value = stripcslashes($_POST['thank_you_subject'][$key]);
                        $m->save();
                    }
                    if ($m = GlobalConfigModel::objects()->filter(['name' => 'thank_you_message_body'])->get()) {
                        $m->value = stripcslashes($_POST['thank_you_message_body'][$key]);
                        $m->save();
                    }
                    break;
                case "":
                    break;
                default :
                    $params = [
                        'storefrontid' => $store,
                        'category' => 'thankyou_for_order',
                        'type' => 'text'
                        ];
                    $m = SiteConfigModel::objects()->filter(['name' => 'thank_you_days', 'storefrontid' => $store])->get();
                    if (!$m) {
                        $m = new SiteConfigModel(array_merge([
                            'name' => 'thank_you_days',
                            'orderby' => 0,
                            'comment' => 'How many days is the letter should be sent after tracking number is entered'
                        ], $params));
                    }
                    $m->value = $_POST['thank_you_days'][$key];
                    $m->save();

                    $m = SiteConfigModel::objects()->filter(['name' => 'thank_you_from', 'storefrontid' => $store])->get();
                    if (!$m) {
                        $m = new SiteConfigModel(array_merge([
                            'name' => 'thank_you_from',
                            'orderby' => 10,
                            'comment' => 'From'
                        ], $params));
                    }
                    $m->value = $_POST['thank_you_from'][$key];
                    $m->save();

                    $m = SiteConfigModel::objects()->filter(['name' => 'thank_you_subject', 'storefrontid' => $store])->get();
                    if (!$m) {
                        $m = new SiteConfigModel(array_merge([
                            'name' => 'thank_you_subject',
                            'orderby' => 20,
                            'comment' => 'Email subject line'
                        ], $params));
                    }
                    $m->value = stripcslashes($_POST['thank_you_subject'][$key]);
                    $m->save();

                    $m = SiteConfigModel::objects()->filter(['name' => 'thank_you_message_body', 'storefrontid' => $store])->get();
                    if (!$m) {
                        $m = new SiteConfigModel([
                            'name' => 'thank_you_message_body',
                            'storefrontid' => $store,
                            'category' => 'thankyou_for_order',
                            'type' => 'textarea',
                            'orderby' => 30,
                            'comment' => 'Message body'
                            ]);
                    }
                    $m->value = stripcslashes($_POST['thank_you_message_body'][$key]);
                    $m->save();

                    $edited_stores[] = $store;
                    break;
            }
        }

        $delete_param = ['category' => 'thankyou_for_order'];
        if (!empty($edited_stores)) {
            $delete_param[] = new QAndNot(['storefrontid__in' => $edited_stores]);
        }
        SiteConfigModel::objects()->delete($delete_param);
    }
    func_header_location('/admin/configuration.php?option=thankyou_for_order');
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
