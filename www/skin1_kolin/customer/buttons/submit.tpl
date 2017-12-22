{if $button_title eq ''}
{include file="buttons/button.tpl" button_title=$lng.lbl_submit}
{else}
{include file="buttons/button.tpl" button_title=$button_title}
{/if}
