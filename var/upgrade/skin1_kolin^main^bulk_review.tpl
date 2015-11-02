{* $Id: bulk_review.tpl,v 1.0 2010/10/25 13:31:48 kate Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_bulk_product_fields_updation_review}

{capture name=dialog}
{if $log eq ''}
{$lng.lbl_bulk_review_top_text}
<br />

<form name="bulkreviewform" action="bulk_management.php" method="post">
<input type="hidden" name="mode" value="apply" />

<table cellpadding="1" cellspacing="1" width="100%">

{if (!$changes.new && !$changes.existing && $changes.discontinued.forsale eq 'N' && $changes.discontinued.avail eq 'N' && $changes.discontinued.categoryid eq 'N') || (!$new && !$existing && !$discontinued)}
	<tr>
		<td><b>{$lng.lbl_no_changes}</b></td>
	</tr>
	<tr>
		<td><br /><input type="button" value="{$lng.lbl_cancel_changes}" onclick="javascript: document.bulkreviewform.mode.value = 'cancel'; document.bulkreviewform.submit();" /></td>
	</tr>
{else}
<tr>
	<td>
		<div class="b_scroll_div">
			<table cellspacing="0" cellpadding="2" width="100%" class="b_scroll_table" border="0">
			<tr>
				{foreach from=$colnames item="col"}
					<td class="b_colnames b_has_border" nowrap="nowrap">!{$col|upper}</td>
				{/foreach}
			</tr>
			{if $new && $changes.new}
				{foreach from=$new item="nproduct"}
					<tr>
						{foreach from=$colnames item="col"}
							<td class="b_has_border p_new_style"{if $col eq 'productcode'} nowrap="nowrap"{/if}>{if $changes.new[$col]}{$nproduct[$col]}{else}&nbsp;{/if}</td>
						{/foreach}
					</tr>
				{/foreach}
			{/if}
			{if $existing && $changes.existing}
				{foreach from=$existing item="eproduct" name="ecolumns"}
					<tr>
						{foreach from=$colnames item="col"}
						<td class="b_has_border">
							{if $col eq 'productcode' || !$changes.existing[$col]}
								{$eproduct.dbsr[$col]}
							{else}
								<table>
								<tr>
									<td>{$eproduct.csv[$col]}</td>
								</tr>
								<tr>
									<td class="{if $eproduct.dbsr[$col] ne ''}b_old_value{else}b_empty_value{/if}">
									
									{if $eproduct.dbsr[$col] ne ''}
										{if $col eq 'add_date'}
											{$eproduct.dbsr[$col]|date_format:"%A %d %B %Y %T %p"}
										{else}
											{$eproduct.dbsr[$col]}
										{/if}
									{else}
										&lt;{$lng.lbl_empty}&gt;
									{/if}
									</td>
								</tr>
								</table>
							{/if}
						</td>
						{/foreach}
					</tr>
				{/foreach}
			{/if}
			{if $discontinued && ($changes.discontinued.avail neq 'N' || $changes.discontinued.forsale neq 'N' || $changes.discontinued.categoryid neq 'N')}
				{foreach from=$discontinued item="dproduct"}
					<tr>
						{foreach from=$colnames item="col"}
							<td class="b_has_border p_discont_style"{if $col eq 'productcode'} nowrap="nowrap"{/if}>
								{if $col eq 'productcode' || !$changes.discontinued[$col] || $changes.discontinued[$col] eq 'N'}
									{if $col eq 'add_date'}{$dproduct[$col]|date_format:"%A %d %B %Y %T %p"}{else}{$dproduct[$col]}{/if}
								{else}
									<table>
									<tr>
										<td class="p_discont_style">
											{if $col eq 'avail' && $changes.discontinued.avail ne "N"}
												0
											{elseif $col eq 'forsale' && $changes.discontinued.forsale ne "N"}
												N
											{elseif $col eq 'categoryid' && $changes.discontinued.categoryid ne "N"}
												{$changes.discontinued[$col]}
											{else}
												{$dproduct.csv[$col]}
											{/if}
										</td>
									</tr>
									<tr>
										<td class="{if $dproduct[$col] ne ''}b_old_value{else}b_empty_value{/if}">
										{if $dproduct[$col] ne ''}
											{if $col eq 'add_date'}
												{$dproduct[$col]|date_format:"%A %d %B %Y %T %p"}
											{else}
												{$dproduct[$col]}
											{/if}
										{else}
											&lt;{$lng.lbl_empty}&gt;
										{/if}
										</td>
									</tr>
									</table>
								{/if}
							</td>
						{/foreach}
					</tr>
				{/foreach}
			{/if}
			</table>
		</div>
	</td>
</tr>

<tr>
	<td><br /><input type="button" value="{$lng.lbl_apply_changes}" onclick="javascript: document.bulkreviewform.mode.value = 'apply'; document.bulkreviewform.submit();" />&nbsp;&nbsp;<input type="button" value="{$lng.lbl_cancel_changes}" onclick="javascript: document.bulkreviewform.mode.value = 'cancel'; document.bulkreviewform.submit();" /></td>
</tr>
{/if}

</table>
</form>
{else}
<pre>{$log}</pre>
<input type="button" value="{$lng.lbl_ok}" onclick="javascript: self.location = 'bulk_management.php?mode=complete'" />
{/if}

{/capture}
{include file="dialog.tpl" title=$lng.lbl_bulk_product_management content=$smarty.capture.dialog extra='width="100%"'}
