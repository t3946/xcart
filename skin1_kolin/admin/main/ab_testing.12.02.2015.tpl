<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
<br />
{capture name=dialog}

<form name="ab_form" method="post" action="ab_testing.php">

<input type="hidden" name="mode" id="mode" value="" />
<input type="hidden" name="delete_variant_id_id" value="" id="delete_variant_id_id" />
<input type="hidden" name="add_variant_id_point_id" value="" id="add_variant_id_point_id" />

<table cellpadding="3" cellspacing="1" width="100%">
 <tr class="TableHead">
   <td style="background-color: #D9EAD3;">point_id</td>
   <td style="background-color: #D9EAD3;">point_name</td>
   <td style="background-color: #D9EAD3;">point_descr</td>
   <td style="background-color: #D9EAD3;">point_start_date</td>
   <td style="background-color: #D9EAD3;">point_end_date</td>
   <td style="background-color: #D9EAD3;">point_goal_url</td>
   <td style="background-color: #D9EAD3;">mod_param</td>
   <td style="background-color: #D9EAD3;">total_hits</td>
   <td style="background-color: #D9EAD3;">enabled</td>
   <td style="background-color: #D9EAD3; text-align: center;"><INPUT type="button" value="+" onclick="document.ab_form.mode.value='add_point'; document.ab_form.submit();">
   </td>
  </tr>

{if $ab_testing_points ne ""}
  {foreach from=$ab_testing_points item=v key=k}
    <tr>
	<td>
	  {$v.point_id}
	  <input type="hidden" name="posted_data[ab_testing_points][{$v.point_id}][point_id]" value="{$v.point_id}" />
	</td>
	<td>
	  <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][point_name]" value="{$v.point_name}" />
	</td>
        <td>
          <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][point_descr]" value="{$v.point_descr}" />
        </td>
        <td>
          <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][point_start_date]" value="{$v.point_start_date}" id="point_start_date_{$v.point_id}" size="10" />

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#point_start_date_{/literal}{$v.point_id}{literal}").datepicker();
  });
{/literal}
-->
</script>
        </td>
	<td>
          <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][point_end_date]" value="{$v.point_end_date}" id="point_end_date_{$v.point_id}" size="10" />

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#point_end_date_{/literal}{$v.point_id}{literal}").datepicker();
  });
{/literal}
-->
</script>
	</td>
        <td>
          <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][point_goal_url]" value="{$v.point_goal_url}" />
        </td>
        <td>
          <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][mod_param]" value="{$v.mod_param}" size="3" />
        </td>
        <td>
          <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][total_hits]" value="{$v.total_hits}" size="3" />
        </td>
	<td>
	  <input type="checkbox" name="posted_data[ab_testing_points][{$v.point_id}][enabled]" value="Y"{if $v.enabled eq "Y"} checked="checked"{/if} />
	</td>
    </tr>
    <tr>
	<td></td>
	<td colspan="7">
	  <B>Storefronts activated:</B> <input type="text" name="posted_data[ab_testing_points][{$v.point_id}][storefronts_enabled]" value="{$v.storefronts_enabled}" style="width: 50%;" />
	</td>
	<td colspan="2"></td>
    </tr>

    <tr>
	<td colspan="10">
	 <table cellpadding="3" cellspacing="1" align="center">
	  <tr class="TableHead">
	   <td style="background-color: #D9EAD3;">variant_id</td>
	   <td style="background-color: #D9EAD3;">variant_name</td>
	   <td style="background-color: #D9EAD3;">is_default</td>
	   <td style="background-color: #D9EAD3;">total hits count</td>
	   <td style="background-color: #D9EAD3;">reach goal count</td>
	   <td style="background-color: #D9EAD3;">dollar amount of goal conversions (order total)</td>
	   <td style="background-color: #D9EAD3;">average success measure</td>
	   <td style="background-color: #D9EAD3;">success measure range</td>
	   <td style="background-color: #D9EAD3;">outcome</td>
	   <td style="background-color: #D9EAD3; text-align: center;">
		<INPUT type="button" value="+" onclick="document.ab_form.mode.value='add_variant'; $('#add_variant_id_point_id').val('{$v.point_id}'); document.ab_form.submit();">
	   </td>
	  </tr>
	
	{if $ab_point_variants ne ""}
	{foreach from=$ab_point_variants item=vv key=kk}
	{if $vv.point_id eq $v.point_id}
	  <tr>
	        <td>
	          <input type="hidden" name="posted_data[ab_point_variants][{$vv.id}][id]" value="{$vv.id}" />
		  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][variant_id]" value="{$vv.variant_id}" size="3" />
	        </td>
                <td>
                  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][variant_name]" value="{$vv.variant_name}" />
                </td>
	        <td>
        	  <input type="checkbox" name="posted_data[ab_point_variants][{$vv.id}][is_default]" value="Y"{if $vv.is_default eq "Y"} checked="checked"{/if} />
	        </td>
                <td>
                  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][total_hits_count]" value="{$vv.total_hits_count}" size="3" />
                </td>
                <td>
                  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][reach_goal_count]" value="{$vv.reach_goal_count}" size="3" />
                </td>
                <td>
                  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][dollar_amount_of_goal_conversions]" value="{$vv.dollar_amount_of_goal_conversions}" size="7" />
                </td>
                <td>
                  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][average_success_measure]" value="{$vv.average_success_measure}" size="3" />
                </td>
                <td>
                  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][success_measure_range]" value="{$vv.success_measure_range}" size="10" />
                </td>
                <td>
                  <input type="text" name="posted_data[ab_point_variants][{$vv.id}][outcome]" value="{$vv.outcome}" size="10" />
                </td>
		<td>
			<INPUT type="button" value="-" onclick="document.ab_form.mode.value='delete_variant'; $('#delete_variant_id_id').val('{$vv.id}'); document.ab_form.submit();">
		</td>
	  </tr>
	{/if}
	{/foreach}
	{/if}
	 </table>
	<hr />
	</td>
    </tr>
  {/foreach}
{/if}

</table>

<INPUT type="button" value="Update" onclick="document.ab_form.mode.value='update'; document.ab_form.submit();">
</form>

{/capture}
{include file="dialog.tpl" title="A/B testing" content=$smarty.capture.dialog extra="width=100%"}
