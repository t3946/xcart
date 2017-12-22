{* $Id: pages_menu.tpl,v 1.5 2005/11/17 06:55:36 max Exp $ *}
{section name=pg loop=$pages_menu}
{if $smarty.get.pageid ne $pages_menu[pg].pageid}<a href="pages.php?pageid={$pages_menu[pg].pageid}" class="VertMenuItems">{else}<font class="VertMenuItems">{/if}{$pages_menu[pg].title}{if $smarty.get.pageid ne $pages_menu[pg].pageid}</a>{else}</font>{/if}<br />
{/section}
