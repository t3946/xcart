{* $Id: signin_admin_notification.tpl,v 1.10 2006/03/31 05:51:43 svowl Exp $ *}

Dear {$userinfo.firstname|capitalize:true},

{$lng.lbl_re_signin_title}

With Best Regards,

Sergey Vorozhtsov
CustServ@ArtistSupplySource.com
Artist Supply Source, Inc.
http://www.ArtistSupplySource.com/
Toll Free: 1-800-929-2431 (orders and customer service only)
Tel. (613) 544-2402
Fax (813) 944-4516


On {$userinfo.first_login|date_format:$config.Appearance.datetime_format}, {$userinfo.email} wrote:
> {$lng.eml_mail_header|substitute:"company":$config.Company.company_name}
> -------------------------------------------------------------------
> 
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}
> {$lng.eml_signin_admin_notification}
> 
> {$lng.lbl_profile_details}:
> ---------------------
{include file="mail/re_profile_data.tpl"}
> 
> --
> {$lng.eml_re_signature}
> 
> {$config.Company.company_name}
{if $config.Company.company_phone}> {$lng.lbl_phone|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_phone}
{/if}
{if $config.Company.company_fax}> {$lng.lbl_fax|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_fax}
{/if}
{if $config.Company.company_website}> {$lng.lbl_url|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$config.Company.company_website}
{/if}
