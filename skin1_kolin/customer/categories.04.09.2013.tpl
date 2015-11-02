{* $Id: categories.tpl,v 1.26 2005/11/17 06:55:37 max Exp $ *}
{if $active_modules.Fancy_Categories ne ""}
{capture name=menu}
{include file="modules/Fancy_Categories/categories.tpl" cat_start=0 cat_end=500}
{assign var="fc_cellpadding" value="0"}
{/capture}
{ include file="menu.tpl" menu_title=$lng.lbl_category_title menu_content=$smarty.capture.menu cellpadding=$fc_cellpadding}
{else}

{if $config.General.root_categories eq "Y"}
<table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
{foreach from=$categories item=c}
{if $c.order_by ge 0 && $c.order_by le 500}
<tr>
<td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;">
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList">{if $c.categoryid ne $smarty.get.cat}<a href="home.php?cat={$c.categoryid}" class="VertMenuItems">{/if}{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}{if $c.categoryid ne $smarty.get.cat}</a>{/if}</font><br />
    {/if}
</td>
</tr>
{/if}
{/foreach}
</table>


{else} {foreach from=$subcategories item=c key=catid}
{if $c.order_by ge 0 && $c.order_by le 500}
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList"><a href="home.php?cat={$catid}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a></font><br />
    {/if}
{/if}
{/foreach}
{/if}

{/if}

{capture name=menu}
{if $active_modules.Fancy_Categories ne ""}
{include file="modules/Fancy_Categories/categories.tpl" cat_start=501 cat_end=50000}
{assign var="fc_cellpadding" value="0"}
{else}
{if $config.General.root_categories eq "Y"}
{foreach from=$categories item=c}
{if $c.order_by gt 500}
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList">{if $c.categoryid ne $smarty.get.cat}<a href="home.php?cat={$c.categoryid}" class="VertMenuItems">{/if}{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}{if $c.categoryid ne $smarty.get.cat}</a>{/if}</font><br />
    {/if}
{/if}
{/foreach}
{else} {foreach from=$subcategories item=c key=catid}
{if $c.order_by gt 500}
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList"><a href="home.php?cat={$catid}" class="VertMenuItems">{$c.category}</a></font><br />
    {/if}
{/if}
{/foreach}
{/if}
{/if}
{/capture}
<br />
{if $smarty.capture.menu ne ""}
{ include file="menu.tpl" menu_title=$lng.lbl_information menu_content=$smarty.capture.menu cellpadding=$fc_cellpadding}
{/if}
