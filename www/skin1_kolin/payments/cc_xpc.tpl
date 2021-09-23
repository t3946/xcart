{*
$Id: cc_xpc.tpl,v 1.1 2010/05/21 08:32:53 joy Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
<h1>X-Payments payment methods</h1>

<br />
<br />

{capture name=dialog}

<img src="{$ImagesDir}/xpc_logo.png" width="130" height="55" alt="X-Payments logo" />

<br />
<br />
<br />

{$lng.txt_xpc_pm_config_note}

<br />
<br />

<table cellpadding="5" cellspacing="1" border="0">

  <tr class="TableHead">
    <td>Payment method</td>
    <td>Payment method ID</td>
    <td>Auth</td>
    <td>Capture</td>
    <td>Void</td>
    <td>Refund</td>
  </tr>

  {foreach from=$cc_processors item=pm}
  <tr{cycle values=', class="TableSubHead"'}>
    <td>{$pm.module_name}</td>
    <td>{$pm.param01}</td>
    <td>{if $pm.has_preauth eq "Y"}{$lng.lbl_yes}{else}{$lng.lbl_no}{/if}</td>
    <td>{if $pm.param02 eq "Y"}{$lng.lbl_yes}{else}{$lng.lbl_no}{/if}</td>
    <td>{if $pm.param03 eq "Y"}{$lng.lbl_yes}{else}{$lng.lbl_no}{/if}</td>
    <td>{if $pm.is_refund eq "Y"}{$lng.lbl_yes}{else}{$lng.lbl_no}{/if}</td>
  </tr>
  {/foreach}

</table>

<br />
<br />

{$lng.txt_xpc_pm_config_note_2}

<br />
<br />

<a href="configuration.php?option=XPayments_Connector">{$lng.lbl_xpc_xpayments_connector_settings}</a>

<br />
<br />

{/capture}
{include file="dialog.tpl" title=$lng.lbl_cc_settings content=$smarty.capture.dialog extra='width="100%"'}
