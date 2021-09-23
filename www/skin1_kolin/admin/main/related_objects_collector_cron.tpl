<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Related_objects_collector_cron">
    <input type="hidden" name="mode" value="">

<table cellpadding="3" cellspacing="1" width="100%">

{*
 <tr>
  <td width="40%">cron_pc.php marked as launched:</td>
  <td>{if $config.cron_pc_launched eq "Y"}Yes{else}No{/if}</td>
 </tr>
*}

 <tr>
  <td colspan="2">
Collecting period backward <input type="text" name="collecting_period_backward_months" value="{$related_objects_collector.collecting_period_backward_months}" size="3" /> months
  </td>
 </tr>

 <tr><td colspan="2">Analyse sessions with events selected:</td></tr>

 <tr>
  <td colspan="2">
<input type="checkbox" name="add_to_cart" value="Y"{if $related_objects_collector.add_to_cart eq "Y"} checked="checked"{/if} /> Add to cart
  </td>
 </tr>

 <tr>
  <td colspan="2">
<input type="checkbox" name="order_submit" value="Y"{if $related_objects_collector.order_submit eq "Y"} checked="checked"{/if} /> Order submit
  </td>
 </tr>

 <tr>
  <td colspan="2">
<input type="checkbox" name="search" value="Y"{if $related_objects_collector.search eq "Y"} checked="checked"{/if} /> Search
  </td>
 </tr>

 <tr>
  <td colspan="2">
<input type="checkbox" name="checkout" value="Y"{if $related_objects_collector.checkout eq "Y"} checked="checked"{/if} /> Checkout
  </td>
 </tr>

 <tr>
  <td colspan="2">
<input type="checkbox" name="mobile" value="Y"{if $related_objects_collector.mobile eq "Y"} checked="checked"{/if} /> Mobile
  </td>
 </tr>

</table>

<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />

</form>
