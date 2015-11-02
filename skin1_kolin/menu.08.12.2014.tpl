<table cellspacing="1" width="100%" class="VertMenuBorder">
<tr>
<td class="VertMenuTitle">
<table cellspacing="0" cellpadding="0" width="100%"><tr>
<td></td>
<td width="100%">{if $link_href}<a href="{$link_href}">{/if}<font class="VertMenuTitle">{$menu_title}</font>{if $link_href}</a>{/if}</td>
</tr></table>
</td>
</tr>
<tr> 
<td class="VertMenuBox">
<table cellpadding="{$cellpadding|default:"5"}" cellspacing="0" width="100%">
<tr><td>{$menu_content}</td></tr>
</table>
</td></tr>
</table>
