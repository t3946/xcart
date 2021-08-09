<?php

namespace Modules\Core\Controllers\Api;

use Modules\Core\Models\GlobalConfigModel;
use Modules\Core\Models\LanguageModel;
use Modules\Core\Models\ModuleModel;
use Xcart\App\Controller\Controller;

class GeneralSettingsController extends Controller
{
	public function getAllConfig()
	{
		$ar_result = [];
		$config = GlobalConfigModel::objects()
			->exclude(['category__in' => ['UPS_OnLine_Tools', 'Taxes', '', 'Search_All']])
			->group(['category'])
			->valuesList(['category'], true);
		foreach ($config as $key => $value) {
			if ($value === 'currently_assigned_to_statuses') {
				unset($config[$key]);
				continue;
			}
			if ($value === 'PBX_options' && !empty($membership_code)) {
				unset($config[$key]);
			}
		}
		$new_config = array_map(fn($el) => "option_title_{$el}", $config);
		$langs = LanguageModel::objects()->filter(['name__in' => $new_config, 'code' => 'US'])->valuesList(['value', 'name']);
		$disable_modules = ModuleModel::objects()->filter(['active' => 'N'])->valuesList(['module_name'], true);
		$active_modules = ModuleModel::objects()->filter(['active' => 'Y'])->valuesList(['module_name'], true);
		foreach ($langs as $lang) {
			$base_name = str_replace('option_title_', '', $lang['name']);
			if (!in_array($base_name, $disable_modules) && !in_array($base_name, $active_modules)) {
				$ar_base = [
					'isNew' => false,
					'lang' => $lang['value'],
					'name' => $base_name,
					'url' => "configuration.php?option={$base_name}"
				];
				switch ($base_name) {
					case 'Fraud_check':
						$ar_base['isNew'] = true;
						$ar_base['url'] = '/admin/list/Core/GeneralSettingsAdmin/Fraud_check';
						break;
					case 'Templates_OrderRelatedMessages':
						$ar_base['url'] = '/admin/list/Forms/TemplatesAdmin';
						break;
				}
				$ar_result['config'][] = $ar_base;
			}
		}
		$ar_result['module'] = $this->getModulesInfo($active_modules) ?? [];
		$this->jsonResponse($ar_result);
	}
	public function getModulesInfo(array $active_modules)
	{
		$ar_module = [];
		$opt_act_module = array_map(fn($el) => "module_name_{$el}", $active_modules);
		$langs_module = LanguageModel::objects()
			->filter(['name__in' => $opt_act_module, 'topic' => 'Modules', 'code' => 'US'])
			->valuesList(['value, name']);
		foreach ($langs_module as $lang) {
			$base_name = str_replace('module_name_', '', $lang['name']);
			$ar_module[] = [
				'url' => "configuration.php?option={$base_name}",
				'lang' => $lang['value'],
				'name' => $base_name,
			];
		}
		return $ar_module;
	}
}