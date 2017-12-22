<table cellspacing="1" width="100%" class="VertMenuBorder">
<tr>
<td class="VertMenuTitle">
<table cellspacing="0" cellpadding="0" width="100%"><tr>
<td></td>
<td width="100%" valign="middle">{if $link_href}<a href="{$link_href}" {if $id_expand ne ""}onclick="javascript: $('#{$id_expand}').toggle();"{/if}>{/if}<font class="VertMenuTitle" {if $id_expand ne ""}style="color: #0033cc;"{/if}>{$menu_title}</font>{if $link_href}</a>{/if} {if $id_expand ne ""}<img src="{$ImagesDir}/br_down.png" alt="" onclick="javascript: $('#{$id_expand}').toggle();" />{/if}</td>
</tr></table>
</td>
</tr>
<tr {if $id_expand ne ""}id="{$id_expand}"{/if} {if $content_hide eq "Y"}style="display: none;"{/if}>
<td class="VertMenuBox">
<table cellpadding="{$cellpadding|default:"5"}" cellspacing="0" width="100%">
<tr><td>{$menu_content}</td></tr>
</table>
</td></tr>
</table>
