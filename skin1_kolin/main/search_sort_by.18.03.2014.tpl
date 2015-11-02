{* $Id: search_sort_by.tpl,v 1.5.2.1 2006/06/16 10:47:41 max Exp $ *}
{if $url eq '' && $navigation_script ne ''}{assign var="url" value=$navigation_script|replace:"&":"&amp;"|cat:"&amp;"}{elseif $url ne ''}{assign var="url" value=$url|amp}{/if}
<table cellspacing="0" cellpadding="0">
<tr>
	<td class="Green2"><font size=2>{$lng.lbl_sort_by}:</font> &nbsp;&nbsp;&nbsp;</td>
{foreach from=$sort_fields key=name item=field}
	{assign var="cur_url" value=$url|cat:"sort="|cat:$name|cat:"&amp;sort_direction="}


	{if $usertype eq "C"}
		{assign var="cur_url" value=$cur_url|replace:'&amp;page=1&amp;':'&amp;'}
		{assign var="cur_url" value=$cur_url|replace:'.php?page=1&amp;':'.php?'}

		{if $main eq "catalog"}
	                {assign var="cur_url" value=$cur_url|replace:'&amp;path=alt&amp;':'&amp;'}
        	        {assign var="cur_url" value=$cur_url|replace:'.php?path=alt&amp;':'.php?'}
		{/if}

	{/if}


	{if $name eq $selected}
	<td>&nbsp;<a class="VertMenuItems" href="{$cur_url}{if $direction eq 1}0{else}1{/if}" title="{$lng.lbl_sort_by|escape}: {$field}"><img src="{$ImagesDir}/{if $direction}darrow.gif{else}uarrow.gif{/if}" class="VertMenuItems" alt="{$lng.lbl_sort_direction|escape}" /></a></td>
	{/if}
	<td class="VertMenuItems"> &nbsp;<a class="VertMenuItems" href="{$cur_url}{if $name eq $selected}{if $direction eq 1}0{else}1{/if}{else}{$direction}{/if}" title="{$lng.lbl_sort_by|escape}: {$field}">{if $name eq $selected}<b>{/if}<font size=2>{$field}</font>{if $name eq $selected}</b>{/if}</a>&nbsp;&nbsp;</td>
{/foreach}
</tr>
</table>
