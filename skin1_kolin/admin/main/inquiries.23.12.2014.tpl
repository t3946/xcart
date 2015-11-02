{if $page_name ne ""}
{include file="page_title.tpl" title=$page_name}
{/if}
<br />

{if $total_items gt "0"}
{$lng.txt_N_results_found|substitute:"items":$total_items}<br />
{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
{else}
{$lng.txt_N_results_found|substitute:"items":0}
{/if}

{capture name=dialog}

 {if $inquiries ne ""}

  {include file="customer/main/navigation.tpl"}

  <form name="inquiryform1" action="inquiries.php" method="POST">
  {if $inq_type_id ne ""}
  <input type="hidden" name="inq_type_id" value="{$inq_type_id}">
  {elseif $inq_tag_id ne ""}
  <input type="hidden" name="inq_tag_id" value="{$inq_tag_id}">
  {/if}
  <input type="hidden" name="mode" value="update" id="mode">

  <input type="hidden" name="page" value="{$navigation_page}">

  <input type="hidden" name="del_inq_id__inq_tag_id" value="" id="del_inq_id__inq_tag_id">

  <table cellpadding="3" cellspacing="1" width="100%">
  <tr class='TableSubHead'>
	<td>Date</td>
	<td>Created By</td>
	<td>Type</td>
	<td width="40%">Subject</td>
	<td>Status</td>
	<td>Attention tags</td>
	<td>Close</td>
  </tr>

  {foreach from=$inquiries item=v key=k}
    <input type="hidden" name="post_data[{$v.inq_id}][inq_id]" value="{$v.inq_id}" />
    <tr>
	<td>{$v.datetime|date_format:'%d-%b-%Y&nbsp; %H:%M'}</td>
        <td>{$v.createdby_login}</td>
        <td>{$v.inquiry_type}</td>
        <td><a href="#">{$v.inq_subject}</a></td>
        <td>{if $v.status eq "O"}Open{elseif $v.status eq "C"}Closed{else}{$v.status}{/if}</td>
        <td>
	 {if $v.inquiries_attention_tags ne ""}
	  {foreach from=$v.inquiries_attention_tags item=vv key=kk}
		{$vv.inquiry_attn_tag} <a href="javascript: void(0);" style="color: red; text-decoration: none;" onclick="javascript: $('#mode').val('delete'); $('#del_inq_id__inq_tag_id').val('{$v.inq_id}_{$vv.inq_tag_id}'); document.inquiryform1.submit();">X</a><br />
	  {/foreach}
	 {/if}

	 {if $inquiries_attention_tags ne ""}
		<select name="post_data[{$v.inq_id}][add_inq_tag_id]">
		 <option>Select</option>
		 {foreach from=$inquiries_attention_tags item=item key=key}
		    {assign var="show_inquiry_attn_tag" value="Y"}

		    {foreach from=$v.inquiries_attention_tags item=vv key=kk}
		      {if $item.inq_tag_id eq $vv.inq_tag_id}
			{assign var="show_inquiry_attn_tag" value="N"}
		      {/if}
		    {/foreach}

		    {if $show_inquiry_attn_tag eq "Y"}
			<option value="{$item.inq_tag_id}">{$item.inquiry_attn_tag}</option>
		    {/if}
		 {/foreach}
		</select>
	 {/if}
        </td>
        <td>
	 {if $v.status ne "C"}
	 <input type="checkbox" name="post_data[{$v.inq_id}][close]" value="Y" />
	 {/if}
        </td>
    </tr>
  {/foreach}
  </table>

  <input type="submit" name="Update" value="Update">
  </form>
 {/if}

{/capture}
{include file="dialog.tpl" title="Inquiries" content=$smarty.capture.dialog extra='width="100%"'}

