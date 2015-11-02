{* $Id: navigation.tpl,v 1.16.2.1 2006/06/16 10:47:41 max Exp $ *}
{assign var="navigation_script" value=$navigation_script|amp}
{if $total_pages gt 2}
<table cellpadding="0">
<tr>
	<td class="NavigationTitle">{$lng.lbl_result_pages}:</td>
{if $current_super_page gt 1}


	{assign var="cidev_navigation_script_0" value="$navigation_script"}
	{math equation="page-1" page=$start_page assign="cidev_page"}
	{if $cidev_page gt "1"}
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
	{if $cidev_page gt "1"}
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
	{if $cidev_page gt 1}
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
