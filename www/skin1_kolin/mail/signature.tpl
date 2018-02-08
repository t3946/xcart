{* $Id: signature.tpl,v 1.11 2005/10/05 11:06:09 max Exp $ *}
--
{if $show_s3stores_site_in_invoice eq "Y"}
S3 Stores, Inc.
{if $config.Company.company_phone}{$lng.lbl_phone|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_phone}
{/if}
{if $config.Company.company_fax}{$lng.lbl_fax|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_fax}
{/if}
{if $config.Company.company_website}{$lng.lbl_url|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}http://www.s3stores.com
{/if}
{else}
{$lng.eml_signature}

{$sf_info.config.Company.company_name|default:$config.Company.company_name}, {$lng.lbl_division_of} {$config.Company.operating_company_name}
{if $config.Company.company_phone}{$lng.lbl_phone|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_phone}
{/if}
{if $config.Company.company_fax}{$lng.lbl_fax|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_fax}
{/if}
{if $order_storefront_info.domain ne ""}
{$lng.lbl_url|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}http://{$order_storefront_info.domain}
{else}
{if $sf_info.config.Company.company_website}{$lng.lbl_url|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$sf_info.config.Company.company_website}
{else}
{if $config.Company.company_website}{$lng.lbl_url|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_website}
{/if}
{/if}
{/if}
{/if}
