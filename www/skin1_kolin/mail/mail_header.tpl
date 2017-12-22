{* $Id: mail_header.tpl,v 1.2 2006/03/31 05:51:43 svowl Exp $ *}
{if $sf_info}
{$lng.eml_mail_header|substitute:"company":$sf_info.config.Company.company_name}
{else}
{$lng.eml_mail_header|substitute:"company":$config.Company.company_name}
{/if}
-------------------------------------------------------------------

