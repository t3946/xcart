{include file="page_title.tpl" title="BPU"}

{capture name=dialog1}

{if $step eq "0"}
	{$bpu_operator_name} ({$config.bpu_operator_login}) are working with it currently.
{elseif $step eq "1"}
	<form id="uploadform" method="post" action="bpu.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="upload" />
	<table cellpadding="0" cellspacing="4" width="100%">
	<tr>
	        <td>{$lng.lbl_csv_delimiter}</td>
        	<td>{include file="provider/main/ie_delimiter.tpl"}</td>
	</tr>
	<tr>
	        <td>{$lng.lbl_csv_file}</td>
	        <td><input type="file" name="userfile" />
	{if $upload_max_filesize}
	<br /><font class="Star">{$lng.lbl_warning}!</font> {$lng.txt_max_file_size_that_can_be_uploaded}: {$upload_max_filesize}b.
	{/if}
	        </td>
	</tr>

	<tr>
	<td colspan="2"><input type="submit" value="Submit" /></td>
	</tr>

	</table>
	</form>
{elseif $step eq "2"}

        <form id="uploadform3" method="post" action="bpu.php">
        <input type="hidden" name="mode" value="cancel" />
        <input type="submit" value="Cancel" />
        </form>
	<br />
	<br />

        <form id="uploadform2" method="post" action="bpu.php">
        <input type="hidden" name="mode" value="import" />

        <table cellpadding="1" cellspacing="1">
	{assign var="row_counter" value=0}

	{foreach from=$bpu_rows item=v key=k}
	{if $v.row ne ""}
		{math equation="x+1" x=$row_counter assign="row_counter" }
        <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
		{foreach from=$v.row item=vv key=kk}

		    {if $row_counter eq "1"}
			<td><B>{$vv}</B></td>
		    {else}
			{foreach from=$vv item=vvv key=kkk}
	                <td>{$vvv}</td>
			{/foreach}
		    {/if}

		{/foreach}
        </tr>
	{/if}
	{/foreach}
        </table>

	<input type="submit" value="Submit" />
        </form>

{elseif $step eq "3"}

	{if $results ne ""}
		{foreach from=$results item=v key=k}
			{$v.result}: {$v.count}<br />
		{/foreach}
	{/if}

	{if $full_result ne ""}
	<br />
        <table cellpadding="1" cellspacing="1">

	<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
                <td><B>Productcode</B></td>
                <td><B>Result</B></td>
	</tr>

        {foreach from=$full_result item=v key=k}
        <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
		<td>{$v.productcode}</td>
		<td>{$v.result}</td>
        </tr>
        {/foreach}
        </table>
	{/if}

{/if}

{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog1 title="BPU" extra='width="100%"'}
