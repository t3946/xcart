{*<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>*}

<script type="text/javascript">
        <!--
                var lbl_add = '{$lng.lbl_add|escape}';
                var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
                var ImagesDir = '{$ImagesDir}';
                var row_max_index = '{$row_max_index}';
        -->
</script>
{include file="main/include_js.tpl" src="main/manage_request_availability.js"}


<form name="osnotificform2" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Request_availability_options">
    <input type="hidden" name="mode" value="update_request_availability">

<table align="center">
<tr>
        <td width="200"><b>Name</b></td>
        <td width="100" nowrap="nowrap"><b>Date (mm/dd/yyyy)</b></td>
        <td width="20"><b>Active</b></td>
        <td width="20"></td>
        <td width="20"></td>
</tr>

        {if $request_availability_options}
                {foreach from=$request_availability_options item="item" key=key name="depforeach"}

                <tr id="dep_{$key}">

			<td>
<input type="text"  name="request_availability_options[{$key}][name]" value="{$item.name}" style="width: 96%;" />
			</td>

			<td>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#date_mm_dd_yyyy_{/literal}{$key}{literal}").datepicker();
  });
{/literal}
-->
</script>

<input type="text"  name="request_availability_options[{$key}][date_mm_dd_yyyy]" value="{$item.date_mm_dd_yyyy}" style="width: 96%;" id="date_mm_dd_yyyy_{$key}" />
			</td>

			<td>
<input type="checkbox" name="request_availability_options[{$key}][active]" value="Y" {if $item.active eq "Y"}checked="checked"{/if} style="padding: 0px; margin: -2px 0 0 0;" />
			</td>

			<td>
<a href="javascript: void(0);" onclick="javascript: add_row('{$key}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
			</td>

			<td>
<a href="javascript: void(0);" onclick="javascript: remove_row('{$key}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>
			</td>

                </tr>

                {/foreach}
	{else}

                <tr id="dep_0">

                        <td>
<input type="text"  name="request_availability_options[0][name]" value="" style="width: 96%;" />
                        </td>

                        <td>
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#date_mm_dd_yyyy_0").datepicker();
  });
{/literal}
-->
</script>

<input type="text"  name="request_availability_options[0][date_mm_dd_yyyy]" value="" style="width: 96%;" id="date_mm_dd_yyyy_0" />
                        </td>

                        <td>
<input type="checkbox" name="request_availability_options[0][active]" value="Y" checked="checked" style="padding: 0px; margin: -2px 0 0 0;" />
                        </td>

                        <td>
<a href="javascript: void(0);" onclick="javascript: add_row('0');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
                        </td>

                        <td>
<a href="javascript: void(0);" onclick="javascript: remove_row('0');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>
                        </td>

                </tr>

        {/if}

                <tr id="template_row{$row_max_index}"><td colspan="7"></td></tr>


		<tr>
		        <td colspan="7" align="center"><input type="submit" name="Save" value="Save" /></td>
		</tr>

</table>
</form>

