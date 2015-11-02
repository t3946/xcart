<br />
{capture name=dialog}
 <form name="amz_form" action="amazon_specific_details.php" method="POST">
  <input type="hidden" name="mode" value="update" id="mode" />
  <input type="hidden" name="productid" value="{$amazon_specific_details.productid}" />
  <input type="hidden" name="id" value="{$amazon_specific_details.id}" />

  <table width="100%">

<tr>
<td width="25%">amazon_product</td>
<td width="*"><input type="text" name="amazon_product" value="{$amazon_specific_details.amazon_product}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_bulletpoint1</td>
<td width="*"><input type="text" name="amazon_bulletpoint1" value="{$amazon_specific_details.amazon_bulletpoint1}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_bulletpoint2</td>
<td width="*"><input type="text" name="amazon_bulletpoint2" value="{$amazon_specific_details.amazon_bulletpoint2}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_bulletpoint3</td>
<td width="*"><input type="text" name="amazon_bulletpoint3" value="{$amazon_specific_details.amazon_bulletpoint3}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_bulletpoint4</td>
<td width="*"><input type="text" name="amazon_bulletpoint4" value="{$amazon_specific_details.amazon_bulletpoint4}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_bulletpoint5</td>
<td width="*"><input type="text" name="amazon_bulletpoint5" value="{$amazon_specific_details.amazon_bulletpoint5}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_searchterms1</td>
<td width="*"><input type="text" name="amazon_searchterms1" value="{$amazon_specific_details.amazon_searchterms1}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_searchterms2</td>
<td width="*"><input type="text" name="amazon_searchterms2" value="{$amazon_specific_details.amazon_searchterms2}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_searchterms3</td>
<td width="*"><input type="text" name="amazon_searchterms3" value="{$amazon_specific_details.amazon_searchterms3}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_searchterms4</td>
<td width="*"><input type="text" name="amazon_searchterms4" value="{$amazon_specific_details.amazon_searchterms4}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_searchterms5</td>
<td width="*"><input type="text" name="amazon_searchterms5" value="{$amazon_specific_details.amazon_searchterms5}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_product_type</td>
<td width="*"><input type="text" name="amazon_product_type" value="{$amazon_specific_details.amazon_product_type}" style="width: 95%;" /></td>
</tr>

<tr>
<td width="25%">amazon_category_item_type</td>
<td width="*"><input type="text" name="amazon_category_item_type" value="{$amazon_specific_details.amazon_category_item_type}" style="width: 95%;" /></td>
</tr>

  </table>

  <input type="submit" name="submit" value="submit" />

 </form>
{/capture}
{include file="dialog.tpl" title="$product" content=$smarty.capture.dialog extra='width="100%"'}
