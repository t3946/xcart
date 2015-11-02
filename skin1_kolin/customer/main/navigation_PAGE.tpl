{* $Id: navigation.tpl,v 1.16.2.1 2006/06/16 10:47:41 max Exp $ *}

{if $cidev_new_navigation eq "Y"}

{assign var="navigation_script" value=$navigation_script|amp}
{if $total_pages gt 2}

{if $featured ne "Y"}
<div class="b-paginator_new" >
<B>Pages</B>&nbsp;&nbsp;&nbsp;
{section name=page loop=$total_pages start=$start_page}
{if %page.first%}
{if $navigation_page gt 1}


        {assign var="cidev_navigation_script_1" value="$navigation_script"}
        {math equation="page-1" page=$navigation_page assign="cidev_page"}

        {if $cidev_page gt "1"}
	        {if $clean_url_data.resource_type eq "K"}
        	        {assign var="cidev_navigation_script_1" value="$cidev_navigation_script_1?page=$cidev_page"}
	        {else}
	                {assign var="cidev_navigation_script_1" value="$cidev_navigation_script_1&amp;page=$cidev_page"}
		{/if}
        {/if}

        {if $main eq "catalog" && $current_category.category eq "" && $cidev_page eq "1"}
{*
		{if $e_products_found eq "Y"}
	                {assign var="cidev_navigation_script_1" value="/?mode=e_search"}
		{else}
	                {assign var="cidev_navigation_script_1" value="/"}
		{/if}
*}


                {if $clean_url_data.resource_type eq "K"}

                {else}
                        {assign var="cidev_navigation_script_1" value="/"}
                {/if}


        {/if}

        {assign var="cidev_navigation_script_1" value=$cidev_navigation_script_1|replace:'.php&amp;':'.php?'}


        <a href="{$cidev_navigation_script_1}">&larr; previous</a>
{/if}
{/if}

{if %page.last%}
{math equation="pages-1" pages=$total_pages assign="total_pages_minus"}
{if $navigation_page lt $total_super_pages*$navigation_max_pages}

        {assign var="cidev_navigation_script_3" value="$navigation_script"}
        {math equation="page+1" page=$navigation_page assign="cidev_page"}

	{if $clean_url_data.resource_type eq "K"}
	        {assign var="cidev_navigation_script_3" value="$cidev_navigation_script_3?page=$cidev_page"}
	{else}
	        {assign var="cidev_navigation_script_3" value="$cidev_navigation_script_3&amp;page=$cidev_page"}
	{/if}
        {assign var="cidev_navigation_script_3" value=$cidev_navigation_script_3|replace:'.php&amp;':'.php?'}

	{if $navigation_page ne $total_pages_minus}
         &nbsp;&nbsp;&nbsp;<a href="{$cidev_navigation_script_3}">next &rarr;</a> 
	{/if}
{/if}
{/if}
{/section}
</div>
{/if}

<div class="b-paginator" {if $featured eq "Y"}style="text-align:left;"{/if}>
 <div class="b-paginator-cell type_content" {if $featured eq "Y"}style="margin-left: 0px;"{/if}>
  <div class="b-paginator-cell-scrollbar">
   <div class="b-paginator-cell-scrollbar-h js-paginator-pages">

{if $featured eq "Y"}
<font style="font-size: 18px; font-weight: bold;">Pages</font>&nbsp;&nbsp;&nbsp;&nbsp;
{/if}


{section name=page loop=$total_pages start=$start_page}

{if %page.index% eq $navigation_page}
	<span class="b-paginator-item g-current js-paginator-page-current" {* if $featured eq "Y"}style="width:24px;"{/if *}>{%page.index%}</span>
{else}
{if %page.index% ge 100}
{assign var="suffix" value="Wide"}
{else}
{assign var="suffix" value=""}
{/if}


        {assign var="cidev_navigation_script_2" value="$navigation_script"}
        {assign var="cidev_page" value=%page.index%}
        {if $cidev_page gt 1}
	        {if $clean_url_data.resource_type eq "K"}
        	        {assign var="cidev_navigation_script_2" value="$cidev_navigation_script_2?page=$cidev_page"}
	        {else}
        	        {assign var="cidev_navigation_script_2" value="$cidev_navigation_script_2&amp;page=$cidev_page"}
		{/if}
        {/if}

        {if $main eq "catalog" && $current_category.category eq "" && $cidev_page eq "1"}

{*
		{if $e_products_found eq "Y"}
	                {assign var="cidev_navigation_script_2" value="/?mode=e_search"}
		{else}
	                {assign var="cidev_navigation_script_2" value="/"}
		{/if}
*}

                {if $clean_url_data.resource_type eq "K"}

                {else}
                        {assign var="cidev_navigation_script_2" value="/"}
                {/if}

        {/if}

        {assign var="cidev_navigation_script_2" value=$cidev_navigation_script_2|replace:'.php&amp;':'.php?'}

        {assign var="cidev_navigation_script_2" value=$cidev_navigation_script_2|replace:'?&amp;':'?'}

	<a class="b-paginator-item" href="{$cidev_navigation_script_2}" {* if $featured eq "Y"}style="width:24px;"{/if *}>{%page.index%}</a>

{/if}
{/section}



   </div>
  </div>
 </div>
</div>

{/if}


{else}




{assign var="navigation_script" value=$navigation_script|amp}
{if $total_pages gt 2}
<table cellpadding="0">
<tr>
	<td class="NavigationTitle">{$lng.lbl_result_pages}:</td>
{if $current_super_page gt 1}


	{assign var="cidev_navigation_script_0" value="$navigation_script"}
	{math equation="page-1" page=$start_page assign="cidev_page"}
	{if $cidev_page gt "1" || $usertype eq "A" || $usertype eq "P"}
                {assign var="cidev_navigation_script_0" value="$cidev_navigation_script_0&amp;page=$cidev_page"}  
        {/if}
        {assign var="cidev_navigation_script_0" value=$cidev_navigation_script_0|replace:'.php&amp;':'.php?'}


	<td><a href="{$cidev_navigation_script_0}"><img src="{$ImagesDir}/larrow_2.gif" class="NavigationArrow" alt="{$lng.lbl_prev_group_pages|escape}" /></a></td>
{/if}
{section name=page loop=$total_pages start=$start_page}
{if %page.first%}
{if $navigation_page gt 1}


	{assign var="cidev_navigation_script_1" value="$navigation_script"}
	{math equation="page-1" page=$navigation_page assign="cidev_page"}
	{if $cidev_page gt "1" || $usertype eq "A" || $usertype eq "P"}
		{assign var="cidev_navigation_script_1" value="$cidev_navigation_script_1&amp;page=$cidev_page"}	
	{/if}

	{if $main eq "catalog" && $current_category.category eq "" && $cidev_page eq "1"}
		{assign var="cidev_navigation_script_1" value="/"}
	{/if}

	{assign var="cidev_navigation_script_1" value=$cidev_navigation_script_1|replace:'.php&amp;':'.php?'}


	<td valign="middle"><a href="{$cidev_navigation_script_1}"><img src="{$ImagesDir}/larrow.gif" class="NavigationArrow" alt="{$lng.lbl_prev_page|escape}" /></a>&nbsp;</td>
{/if}
{/if}

{if %page.index% eq $navigation_page}
	<td class="NavigationCellSel" title="{$lng.lbl_current_page|escape}: #{%page.index%}">{%page.index%}</td>
{else}
{if %page.index% ge 100}
{assign var="suffix" value="Wide"}
{else}
{assign var="suffix" value=""}
{/if}


        {assign var="cidev_navigation_script_2" value="$navigation_script"}
	{assign var="cidev_page" value=%page.index%}
	{if $cidev_page gt 1 || $usertype eq "A" || $usertype eq "P"}
                {assign var="cidev_navigation_script_2" value="$cidev_navigation_script_2&amp;page=$cidev_page"}
        {/if}

	{if $main eq "catalog" && $current_category.category eq "" && $cidev_page eq "1"}
		{assign var="cidev_navigation_script_2" value="/"}
	{/if}

        {assign var="cidev_navigation_script_2" value=$cidev_navigation_script_2|replace:'.php&amp;':'.php?'}


	<td><a href="{$cidev_navigation_script_2}" title="{$lng.lbl_page|escape} #{%page.index%}" class="NavigationCell{$suffix}">{%page.index%}</a><img src="{$ImagesDir}/spacer.gif" alt="" /></td>
{/if}
{if %page.last%}
{math equation="pages-1" pages=$total_pages assign="total_pages_minus"}
{if $navigation_page lt $total_super_pages*$config.Appearance.max_nav_pages}

	
	{assign var="cidev_navigation_script_3" value="$navigation_script"}
	{math equation="page+1" page=$navigation_page assign="cidev_page"} 
        {assign var="cidev_navigation_script_3" value="$cidev_navigation_script_3&amp;page=$cidev_page"}  
	{assign var="cidev_navigation_script_3" value=$cidev_navigation_script_3|replace:'.php&amp;':'.php?'}
	

	<td valign="middle">&nbsp;<a href="{$cidev_navigation_script_3}"><img src="{$ImagesDir}/rarrow.gif" class="NavigationArrow" alt="{$lng.lbl_next_page|escape}" /></a></td>
{/if}
{/if}
{/section}
{if $current_super_page lt $total_super_pages}

	
	{assign var="cidev_navigation_script_4" value="$navigation_script"}
	{math equation="page+1" page=$total_pages_minus assign="cidev_page"}
        {assign var="cidev_navigation_script_4" value="$cidev_navigation_script_4&amp;page=$cidev_page"}        
        {assign var="cidev_navigation_script_4" value=$cidev_navigation_script_4|replace:'.php&amp;':'.php?'}


	<td><a href="{$cidev_navigation_script_4}"><img src="{$ImagesDir}/rarrow_2.gif" class="NavigationArrow" alt="{$lng.lbl_next_group_pages|escape}" /></a></td>
{/if}
</tr>
</table>
<p />
{/if}

{/if}
