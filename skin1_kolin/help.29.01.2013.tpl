{* $Id: help.tpl,v 1.20 2005/11/17 06:55:36 max Exp $ *}
{capture name=menu}

{if $login eq "" && ($usertype eq "P" || $usertype eq "A")}
<a href="error_message.php?antibot_error" class="VertMenuItems">Authentication</a><br />
{/if}

<a href="help.php?section=contactus&amp;mode=update" class="VertMenuItems">{$lng.lbl_contact_us}</a><br />
{include file="pages_menu.tpl"}
{/capture}
{ include file="menu.tpl" dingbats="dingbats_help.gif" menu_title=$lng.lbl_help menu_content=$smarty.capture.menu link_href="help.php"}
