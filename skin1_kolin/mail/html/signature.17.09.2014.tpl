{* $Id: signature.tpl,v 1.5 2005/12/02 07:25:36 max Exp $ *}
<hr size="1" noshade="noshade" />
{if $show_s3stores_site_in_invoice eq "Y"}
<p><font size="2">
S3 Stores, Inc.<br />
{$lng.lbl_phone}: {$config.Company.company_phone}<br />
{$lng.lbl_fax}:   {$config.Company.company_fax}<br />
{$lng.lbl_url}:   <a href="http://www.s3stores.com" target=_new>http://www.s3stores.com</a>
</font></p>
{else}
{$lng.eml_signature}
<p><font size="2">
{$sf_info.config.Company.company_name|default:$config.Company.company_name}, {$lng.lbl_division_of} {$config.Company.operating_company_name}<br />
{$lng.lbl_phone}: {$config.Company.company_phone}<br />
{$lng.lbl_fax}:   {$config.Company.company_fax}<br />
{$lng.lbl_url}:   <a href="{$sf_info.config.Company.company_website|default:$http_location}/" target=_new>{$sf_info.config.Company.company_website|default:$config.Company.company_website}</a>
</font></p>
{/if}
