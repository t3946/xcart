{*
/**
 * Module configuration template
 *
 * @copyright   Copyright (c) 2001-2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license     http://www.x-cart.com/software_license_agreement.html X-Cart license agreement
 * @author      Slam <slam@x-cart.com>
 * @category    X-Cart
 * @package     Modules
 * @subpackage  XML Sitemap
 * @version     $Id$
 * @since       4.4.0
 */
*}

{* Generate *}
{include file="main/subheader.tpl" title=$lng.xmlmap_generate_section}
<form name="xmlmap_generate" method="post" action="{$smarty.server.REQUEST_URI|escape}">
<input type="hidden" name="xmlmap[config]" value="generate" />
{assign var="xseo_xmlmap_url" value=`$http_location`/`$config.XML_Sitemap.filename`}
{$lng.xmlmap_generate_note|substitute:"url":$xseo_xmlmap_url}
<br /><br />
<input type="submit" value="{$lng.lbl_go|strip_tags:false|escape}" />
</form>
<br /><br />

{* Extra URLs list *}
{if $xmlmap_extra ne ''}
{include file="main/subheader.tpl" title=$lng.xmlmap_extraurls_section}
<form name="xmlmap_delurls" method="post" action="{$smarty.server.REQUEST_URI|escape}">
<input type="hidden" name="xmlmap[config]" value="delurls" />
<table width="100%">
<tr class="TableHead">
<td>&nbsp;</td>
<td width="100%">{$lng.lbl_page_url}</td>
</tr>
{foreach from=$xmlmap_extra item="url"}
<tr>
<td>
<input type="checkbox" value="{$url.id}" name="xmlmap[del_extra][]" /></td>
<td><a href="{$url.url}" target="_blank">{$url.url|truncate:55:"..."}</a></td>
</tr>
{/foreach}
<tr>
<td>
<input type="submit" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" />
</td>
</tr>
</table>
</form>
<br /><br />
{/if}

{* Add Extra URL *}
{include file="main/subheader.tpl" title=$lng.xmlmap_addurl_section}
<form name="xmlmap_addurl" method="post" action="{$smarty.server.REQUEST_URI|escape}">
<input type="hidden" name="xmlmap[config]" value="addurl" />
<table width="100%">
<tr class="TableHead">
<td width="100%">{$lng.lbl_page_url}</td>
</tr>
<tr>
<td>
<input style="width : 99%;" type="text" size="55" name="xmlmap[url]" />
</td>
</tr>
<tr>
<td>
<input type="submit" value="{$lng.lbl_add|strip_tags:false|escape}" />
</td>
</tr>
</table>
</form>