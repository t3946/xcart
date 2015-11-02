{if $new_button_name ne ""}
{include file="buttons/button.tpl" button_title=$new_button_name href=$href title=$title style=$style b=$b b_size=$b_size}
{else}
{include file="buttons/button.tpl" button_title=$lng.lbl_submit href=$href title=$title style=$style b=$b}
{/if}
