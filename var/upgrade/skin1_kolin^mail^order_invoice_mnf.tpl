{* order_invoice_mnf.tpl, random *}
{if $customer ne ''}{assign var="_userinfo" value=$customer}{else}{assign var="_userinfo" value=$userinfo}{/if}
{config_load file="$skin_config"}
{assign var="max_truncate" value=30}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}
{$lng.lbl_order_id|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.order_prefix}{$order.orderid}
{$lng.lbl_order_date|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.date|date_format:$config.Appearance.datetime_format}

{$lng.lbl_customer_info}:
---------------------
{if $_userinfo.default_fields.firstname}{$lng.lbl_first_name|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.firstname}
{/if}
{if $_userinfo.default_fields.lastname}{$lng.lbl_last_name|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.lastname}
{/if}
{if $_userinfo.default_fields.company}{$lng.lbl_company|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.company}
{/if}
{if $_userinfo.default_fields.tax_number}{$lng.lbl_tax_number|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.tax_number}
{/if}
{if $_userinfo.default_fields.phone}{$lng.lbl_phone|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.phone}
{/if}
{if $_userinfo.default_fields.fax}{$lng.lbl_fax|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.fax}
{/if}
{if $_userinfo.default_fields.email}{$lng.lbl_email|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.email}
{/if}
{if $_userinfo.default_fields.url}{$lng.lbl_url|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.url}
{/if}
{foreach from=$_userinfo.additional_fields item=v}{if $v.section eq 'P' || $v.section eq 'C'}
{$v.title|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.value}
{/if}{/foreach}

{$lng.lbl_shipping_address}:
-----------------
{if $_userinfo.default_fields.s_firstname}{$lng.lbl_first_name|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_firstname}
{/if}
{if $_userinfo.default_fields.s_lastname}{$lng.lbl_last_name|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_lastname}
{/if}
{foreach from=$_userinfo.additional_fields item=v}{if $v.section eq 'S'}
{$v.title|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.value}
{/if}{/foreach}{assign var="is_header" value=""}
{if $_userinfo.default_fields.s_address}{$lng.lbl_address|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_address}
{/if}
{if $_userinfo.default_fields.s_address_2}{$lng.lbl_address_2|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_address_2}
{/if}
{if $_userinfo.default_fields.s_city}{$lng.lbl_city|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_city}
{/if}
{if $_userinfo.default_fields.s_county}{if $config.General.use_counties eq "Y"}{$lng.lbl_county|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_countyname}{/if}
{/if}
{if $_userinfo.default_fields.s_state}{$lng.lbl_state|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_statename}
{/if}
{if $_userinfo.default_fields.s_country}{$lng.lbl_country|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_countryname}
{/if}
{if $_userinfo.default_fields.s_zipcode}{$lng.lbl_zip_code|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$order.s_zipcode}
{/if}
{foreach from=$_userinfo.additional_fields item=v}{if $v.section eq 'A'}
{if $is_header ne 'Y'}

{$lng.lbl_additional_information}:
-----------------
{assign var="is_header" value="Y"}{/if}
{$v.title|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.value}
{/if}{/foreach} 

{$lng.lbl_products_ordered}:
-----------------

{foreach from=$order.shipping_groups item=v key=k}
{if $k eq $manufacturerid}
{if $show_shipping eq 'Y'}
{$lng.lbl_delivery|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space} {$v.shipping|trademark:""}

{/if}
{section name=prod_num loop=$v.products}
{$lng.lbl_sku|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.products[prod_num].productcode}
{$lng.lbl_product|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.products[prod_num].product}
{$lng.lbl_quantity|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.products[prod_num].amount}
{if $v.products[prod_num].product_options ne ""}
{$lng.lbl_selected_options}:
{include file="modules/Product_Options/display_options.tpl" options=$v.products[prod_num].product_options options_txt=$v.products[prod_num].product_options_txt is_plain="Y"}
{/if}
{/section}
{/if}
{/foreach}


{*if $order.customer_notes ne ""}
{$lng.lbl_customer_notes}:
------------------------
{$order.customer_notes}
{/if*}

