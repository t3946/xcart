{* $Id: show_status.tpl,v 1.3 2005/11/17 06:55:56 max Exp $ *}

{math equation=$condition assign="tmp_result"}
{if $tmp_result}
<font color="green">{$label_true}</font>
{else}
<font color="red">{$label_false}</font>
{/if}
