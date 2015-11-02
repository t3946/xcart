{* $Id: special_offers_order_bonuses.tpl,v 1.5 2005/11/17 06:55:38 max Exp $ *}
{if $bonuses.points ne 0 or $bonuses.memberships ne ""}

<br />
<table cellspacing="0" cellpadding="0" border="0" align="left">
<tr>
  <td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;">{$lng.lbl_sp_order_bonuses}</font></td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" /></td>
</tr>
<tr>
  <td colspan="2"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" /></td>
</tr>
<tr valign="top">
  <td>
  <table cellspacing="5" cellpadding="0" border="0">
{if $bonuses.points ne 0}
    <tr valign="top">
      <td align="right"><b>{$lng.lbl_sp_customer_bonus_points}:</b></td>
      <td>{$bonuses.points}</td>
    </tr>
{/if}
{if $bonuses.memberships ne ""}
    <tr valign="top">
      <td align="right"><b>{$lng.lbl_sp_customer_bonus_memberships}:</b></td>
      <td>
{foreach name=memberships from=$bonuses.memberships item=membership}
{$membership}{if $smarty.foreach.memberships.last ne "1"}, {/if}
{/foreach}
      </td>
    </tr>
{/if}
  </table>
  </td>
</tr>
</table>
{/if}
