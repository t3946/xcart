{* Id: product_report_subj.tpl, 1.0, 2011.06.20 17:26, kate *}
{config_load file="$skin_config"}{$config.Company.company_name}: {$start_date|default:$smarty.now|date_format:"%e-%b-%G"} {$lng.lbl_product_management_report|capitalize}
