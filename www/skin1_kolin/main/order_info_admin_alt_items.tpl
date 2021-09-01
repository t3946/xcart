{if $order.alt_products ne ""}
<form name="alt_items_update_form" action="order.php?orderid={$order.orderid}&tab=y#main_order_tabs-alt_items" method="POST">
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="mode" value="alt_items_update" />

<table cellpadding="3" cellspacing="1" width="100%">
<tr class="TableHead">
  <td width="7%">POS</td>
  <td width="17%">{$lng.lbl_sku}</td>
  <td width="*">{$lng.lbl_product}</td>
  <td width="7%">{$lng.lbl_price}</td>
  <td width="7%">Delete</td>
</tr>
{foreach from=$order.alt_products item=v key=k}
<tr>
<td>
<input type="text" name="all_alt_items[{$v.productcode}][orderby]" value="{$v.orderby}" size="3" />
</td>
<td>
<a style="color: blue;" href="product_modify.php?productid={$v.productid}&sf={$v.sfid}" target="_blank">{$v.productcode}</a>
<input type="hidden" name="all_alt_items[{$v.productcode}][productcode]" value="{$v.productcode}">
</td>
<td><a href="{$v.url}" target="_blank" style="color: blue;">{$v.product}</a></td>
<td>{$v.price}</td>
<td>
<input type="checkbox" name="alt_items_del[{$v.productcode}]" value="Y" />
</td>
</tr>
{/foreach}
</table>
<input type="submit" name="update_alt_items" value="Update" />
</form>
<br />
<br />
{/if}

<form name="alt_items_form" action="order.php?orderid={$order.orderid}&tab=y#main_order_tabs-alt_items" method="POST">
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="mode" value="alt_items_add" />
  <table >
    <tr id="alt_items_add_tr">
      <td id="alt_items_add_box_0"><span style="color: #550000;">SKU:</span>
        <input type="text" name="alt_items_add[]" value="" />
      </td>
      <td>{include file="buttons/multirow_add.tpl" mark="alt_items_add"}</td>
    </tr>
    
    <tr>
      <td colspan="3">
        <input type="submit" name="submit_alt_items" value="Add alt items" />
      </td>
    </tr>
  </table>

</form>
