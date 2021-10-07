<?php

namespace Modules\Core\Controllers\Api;

use Modules\Core\Models\GlobalConfigModel;
use Modules\Core\Models\LanguageModel;
use Modules\Core\Models\ModuleModel;
use Modules\Forms\Admin\TemplatesAdmin;
use Modules\Forms\FormsModule;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class GeneralSettingsController extends Controller
{
    public function getAllConfig()
    {
        $options = GlobalConfigModel::objects()
            ->exclude(['category__in' => ['UPS_OnLine_Tools', 'Taxes', '', 'Search_All']])
            ->group(['category'])
            ->valuesList(['category'], true);

        if (!empty($options) && is_array($options)) {
            foreach ($options as $key => $catname) {
                switch ($catname) {
                    case 'currently_assigned_to_statuses':
                        unset($options[$key]);
                        continue 2;
                    case 'PBX_options':
                        if (!empty($membership_code)) {
                            unset($options[$key]);
                        }
                        break;
                }
            }
            $options_val = array_map(fn($el) => "option_title_{$el}", array_values($options));
            $ar_lang_opt = LanguageModel::objects()->filter(['code' => 'US', 'name__in' => $options_val])->valuesList(['value', 'name']);
            $ar_name_opt = [];
            foreach ($ar_lang_opt as $lang) {
                $base_name = str_replace('option_title_', '', $lang['name']);
                $ar_name_opt[$base_name] = $lang['value'];
            }
            asort($ar_name_opt);

            $options = array_keys($ar_name_opt);
            $options = array_values($options);
        }

        $disabled_modules = ModuleModel::objects()->filter(['active' => 'N'])->valuesList(['module_name'], true);
        $active_modules = ModuleModel::objects()->filter(['active' => 'Y'])->valuesList(['module_name'], true);
        if (!empty($disabled_modules)) {
            foreach ($disabled_modules as $d_module) {
                if (in_array($d_module, $options)) {
                    $del_opt_key = array_search($d_module, $options);
                    unset($options[$del_opt_key]);
                }
            }
        }

        array_splice($options, (int)array_search('Logging', array_values($options)) + 1, 0, 'Filter_Presets');
        $result = ['status' => false];

        if (is_array($options)) {
            foreach ($options as $catname) {
                switch ($catname) {
                    case 'Templates_OrderRelatedMessages':
                        $option_settings = [
                            'link' => Xcart::app()->router->url('admin:list', [
                                'module' => FormsModule::getName(),
                                'admin' => TemplatesAdmin::classNameShort(),
                            ]),
                            'isNew' => false,
                            'lang' => $ar_name_opt[$catname] ?? ''
                        ];
                        break;
                    case 'Fraud_check':
                        $option_settings = [
                            'link' => '/admin/list/Core/GeneralSettingsAdmin/Fraud_check',
                            'isNew' => true,
                            'lang' => $ar_name_opt[$catname] ?? ''
                        ];
                        break;
                    default:
                        $option_settings = [
                            'link' => "configuration.php?option=$catname",
                            'lang' => $ar_name_opt[$catname] ?? '',
                            'isNew' => false,
                        ];
                        break;
                }
                if (!in_array($catname, $active_modules)) {
                    $result['options'][] = $option_settings;

                } else {
                    if ($catname !== 'Multiple_Storefronts') {
                        $result['modules'][] = $option_settings;
                    }
                }
            }
            $result['status'] = true;
        }
        $this->jsonResponse($result);

    }
}