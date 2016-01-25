
{capture name=menu_2}

{if $config.General.root_categories eq "Y"}

  {assign var="show_category_filter" value="N"}
  {if $active_modules.CIDEV_Best_Search_Filter ne "" && (($current_category.categoryid gt 0 && $current_category.main_order_by le 500) || $brandid gt 0)}
	{assign var="show_category_filter" value="Y"}
  {/if}

  {if $show_category_filter eq "Y"}
    {if $cidev_subcategories_products_count ne ""}
	<table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
	{foreach from=$subcategories item=subcat}
	 {foreach from=$cidev_subcategories_products_count item=v}
	  {if $v.categoryid eq $subcat.categoryid && $v.count_products gt 0 && $v.supplemental_category eq "Y"}
	<tr>
	<td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;"><font class="CategoriesList"><a class="VertMenuItems" href="/home.php?cat={ $subcat.categoryid }">{ $subcat.category|escape }</font></a> ({$v.count_products})</td>
	</tr>
	  {/if}
	 {/foreach}
	{/foreach}
	</table>
    {/if}


  {else}

	<table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
	{foreach from=$categories item=c}
	{if $c.order_by ge 0 && $c.order_by le 500 && ($c.product_count gt 0 || $c.global_product_count gt 0)}
	<tr>
	<td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;">
	    {if $c.categoryid eq ''}
        	<font class="CategoriesList">
	            <a href="/home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
	        </font>
        	<br />
	    {elseif $c.supplemental_category eq "Y"}
        	<font class="CategoriesList">{if $c.categoryid ne $smarty.get.cat}<a href="/home.php?cat={$c.categoryid}" class="VertMenuItems">{/if}{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}{if $c.categoryid ne $smarty.get.cat}</a>{/if}</font><br />
	    {/if}
	</td>
	</tr>
	{/if}
	{/foreach}
	</table>
  {/if}

{else} {foreach from=$subcategories item=c key=catid}
{if $c.order_by ge 0 && $c.order_by le 500 && $c.supplemental_category eq "Y"}
    {if $c.categoryid eq ''}
        <font class="CategoriesList">
            <a href="/home.php?scatid={$c.scatid}&amp;keyphrase={$c.keyphrase}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a>
        </font>
        <br />
    {else}
        <font class="CategoriesList"><a href="/home.php?cat={$catid}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a></font><br />
    {/if}
{/if}
{/foreach}
{/if}


{/capture}
{ include file="menu.tpl" menu_title="Supplemental categories" menu_content=$smarty.capture.menu_2 cellpadding=$fc_cellpadding}





