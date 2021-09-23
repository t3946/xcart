{* $Id: dialog.tpl,v 1.25 2005/12/20 08:50:49 max Exp $ *}
{if $printable ne ''}
{include file="dialog_printable.tpl"}
{else}
<table cellspacing="0" {$extra}>
<tr><td class="DialogBox" valign="{$valign|default:"top"}">{$content}
</td></tr>
</table>
{/if}
