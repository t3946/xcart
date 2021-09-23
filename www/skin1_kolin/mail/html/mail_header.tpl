{* $Id: mail_header.tpl,v 1.6 2006/04/03 07:07:48 svowl Exp $ *}
<p /><font size="2">
{if $sf_info}
    {assign var="location" value=$sf_info.config.Company.company_website}
    {assign var="company_name" value=$sf_info.config.Company.company_name}
{else}
    {assign var="location" value=$http_location}
    {assign var="company_name" value=$config.Company.company_name}
{/if}
{assign var="link" value="<a href=\"`$location`/\" target=\"_new\">`$company_name`</a>"}
{$lng.eml_mail_header|substitute:"company":$link}
</font>

