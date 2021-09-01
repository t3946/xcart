<br />

{if $select ne ""}

{capture name=dialog}


<table border="0" width="100%" cellpadding="3" cellspacing="1" style="background: #000000;">
<tr style="background: #ff8600;">
 <td><B></B></td>
 <td><B></B></td>
 <td><B>Debit</B></td>
 <td><B>Credit</B></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>Assets</td>
 <td>Cash</td>
 <td>{$select[1].Debit}</td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td></td>
 <td>Inventory</td>
 <td>{$select[2].Debit}</td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>Liabilities</td>
 <td>Notes payable</td>
 <td></td>
 <td>{$select[0].Credit}</td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>Revenue</td>
 <td></td>
 <td></td>
 <td>{$select[4].Credit}</td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>Cost of the goods sold</td>
 <td></td>
 <td>{$select[4].Debit}</td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>Expenses</td>
 <td>Amazon FBA expense</td>
 <td>{$select[3].Debit}</td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>Profit</td>
 <td></td>
 <td></td>
 <td>
	{math assign="Profit" equation="x-y-z" x=$select[4].Credit y=$select[4].Debit z=$select[3].Debit}
{$Profit}
 </td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>ROI</td>
 <td></td>
 <td></td>
 <td>
	{math assign="ROI" equation="x/y*100" x=$Profit y=$select[0].Credit}
{include file="currency2.tpl" value=$ROI}%
 </td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>Time period in days</td>
 <td></td>
 <td></td>
 <td>{$select[5].Time_period_in_days}</td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>&nbsp;</td>
 <td></td>
 <td></td>
 <td></td>
</tr>

<tr style="background: #FFFFFF;">
 <td>ROI / year</td>
 <td></td>
 <td></td>
 <td>
	{math assign="ROI_year" equation="x/y*365" x=$ROI y=$select[5].Time_period_in_days}
{include file="currency2.tpl" value=$ROI_year}%
 </td>
</tr>

</table>

{/capture}
{include file="dialog.tpl" title="FBA ROI report (year based) analysis:" content=$smarty.capture.dialog extra='width="100%"'}


{else}

{capture name=dialog}
<form name="sqform" action="fba_roi_report.php" method="post">
<input type="hidden" name="mode" value="search" id="mode" />

{*
Restocking period (in days): <input type="text" name="Amazon_FBA_Restocking_period_days" value="{$config.Amazon_FBA_options.Amazon_FBA_Restocking_period_days}" />
<br />
Report depth (in months): <input type="text" name="Amazon_FBA_Report_depth_months" value="{$config.Amazon_FBA_options.Amazon_FBA_Report_depth_months}" />
<br />
Tau: <input type="text" name="Amazon_FBA_Report_Tau" value="{$config.Amazon_FBA_options.Amazon_FBA_Report_Tau}" />

<br />
*}
<br />
<input type="submit" name="submit" value="Search" />
</form>
{/capture}
{include file="dialog.tpl" title="FBA ROI report (year based)" content=$smarty.capture.dialog extra='width="100%"'}

{/if}


