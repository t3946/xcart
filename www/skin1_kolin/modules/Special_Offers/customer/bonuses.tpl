{* $Id: bonuses.tpl,v 1.5 2005/12/07 14:07:31 max Exp $ *}
{if $mode eq "points"}
{assign var="dialog_title" value=$lng.lbl_sp_convert_points}
{else}
{assign var="dialog_title" value=$lng.lbl_sp_my_bonuses}
{/if}
{$lng.txt_sp_my_bonuses_desc}
<br />
<br />
{capture name="dialog"}

{if $bonus eq "" or ($bonus.points le "0" and $bonus.memberships eq "")}
<br />
{$lng.lbl_sp_no_bonuses_earned}
<br />
<br />

<div align="center">{include file="buttons/button.tpl" button_title=$lng.lbl_sp_learn_more href="offers.php"}</div>

{else}{* $bonus eq "" *}
{if $mode eq "points"}
{include file="modules/Special_Offers/customer/bonuses_points2gc.tpl"}
{else}

<div align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_sp_learn_more href="offers.php"}</div>

{include file="modules/Special_Offers/customer/bonuses_view.tpl"}
{/if}
{/if}{* $bonus eq "" *}

{/capture}
{include file="dialog.tpl" title=$dialog_title content=$smarty.capture.dialog extra='width="100%"'}
