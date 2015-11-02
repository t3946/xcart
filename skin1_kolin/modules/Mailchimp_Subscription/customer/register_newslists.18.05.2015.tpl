{*
$Id: register_newslists.tpl,v 1.1 2010/05/21 08:32:45 joy Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $active_modules.Mailchimp_Subscription}

{if $hide_header eq ""}
<tr>
<td height="20" colspan="3"><b>{$lng.lbl_newsletter}</b><hr size="1" noshade="noshade" /></td>
</tr>
{/if}
{*
<tr>
<td colspan="3">{$lng.lbl_newsletter_signup_text}</td>
</tr>
*}
<tr>
<td align="right" valign="top" style="padding-top: 7px;">
{$lng.lbl_newsletter_signup_text}
</td>
<td>&nbsp;</td>
<td>
<table border="0" cellpadding="0" cellspacing="0">

{section name=idx loop=$mc_newslists}
{assign var="mc_list_id" value=$mc_newslists[idx].mc_list_id}
<tr>
<td>            
<input type="checkbox" name="mailchimp_subscription[{$mc_list_id}]" {if $mailchimp_subscription[$mc_list_id] ne ""}checked{/if} />
</td>
<td>{$mc_newslists[idx].name}</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><i>{$mc_newslists[idx].descr}</i></td>
</tr>
{/section}

</table>
</td>
</tr>

{/if}
