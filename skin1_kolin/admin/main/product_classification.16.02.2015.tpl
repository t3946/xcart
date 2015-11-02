<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Product_classification">
    <input type="hidden" name="mode" value="">

<table cellpadding="3" cellspacing="1" width="100%">

 <tr>
  <td width="40%">cron_pc.php marked as launched:</td>
  <td>{if $config.cron_pc_launched eq "Y"}Yes{else}No{/if}</td>
 </tr>

 <tr>
  <td width="40%">Allow operator to skip products:</td>
  <td>
<input type="checkbox" name="allow_skip_products" value="Y"{if $pc_options.allow_skip_products eq "Y"} checked="checked"{/if} />
  </td>
</tr>

 <tr>
  <td width="40%">Maximum number of autoclassify product per turn:</td>
  <td>
<input type="text" name="maximum_number_of_autoclassify_product_per_turn" value="{$pc_options.maximum_number_of_autoclassify_product_per_turn}" size="5" />
  </td>
 </tr>

 <tr>
  <td>Minimum number of autoclassify product per turn:</td>
  <td>
<input type="text" name="minimum_number_of_autoclassify_product_per_turn" value="{$pc_options.minimum_number_of_autoclassify_product_per_turn}" size="5" />
  </td>
 </tr>

 <tr>
  <td>Stop words:</td>
  <td>
<textarea cols="45" rows="2" name="stop_words" style="width: 90%;" />{$pc_options.stop_words}</textarea>
  </td>
 </tr>

 <tr>
  <td>Excluded char sequences:</td>
  <td>
<textarea cols="45" rows="2" name="excluded_char_sequences" style="width: 90%;" />{$pc_options.excluded_char_sequences}</textarea>
  </td>
 </tr>

 <tr>
  <td>Recalc if approval rate &lt;</td>
  <td>
<input type="text" name="recalc_if_approval_rate" value="{$pc_options.recalc_if_approval_rate}" size="5" />%
  </td>
 </tr>

 <tr>
  <td>Amount of products for autoclassify queue:</td>
  <td>
<input type="text" name="amount_of_products_for_autoclassify_queue" value="{$pc_options.amount_of_products_for_autoclassify_queue}" size="5" />
  </td>
 </tr>

 <tr>
  <td>classification_approval_rate:</td>
  <td>
<input disabled="disabled" type="text" name="classification_approval_rate" value="{$pc_options.classification_approval_rate}" size="5" />
  </td>
 </tr>

</table>

<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />

</form>
