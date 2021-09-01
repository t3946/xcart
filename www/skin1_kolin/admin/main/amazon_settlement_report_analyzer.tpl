<br />

{capture name=dialog}

<form name="searchform" action="amazon_settlement_report_analyzer.php" method="post">
<input type="hidden" name="mode" value="" id="mode" />

<table>

<tr>
<td align="right">
<select name="setAcknowledged1" id="setAcknowledged1">
<option value="">select...</option>
<option value="true">true</option>
<option value="false">false</option>
<option value="all">All</option>
</select>
</td>
<td>
<input type="button" value="Get Report List" onclick="javascript: $('#mode').val('GetReportList'); if ($('#setAcknowledged1').val()!='') window.open('../GetReport.php?setAcknowledged1='+ $('#setAcknowledged1').val() +'&mode=GetReportList&sid=2376dthjdcbsjct67et23dfxafdgbhsdj08r67fija'); this.form.submit();" />
</td>
</tr>


<tr>
<td align="right">
ReportID
<input type="text" name="reportId" value="" id="reportId" />
</td>
<td>
<input type="button" value="Get Report" onclick="javascript: $('#mode').val('GetReport'); if ($('#reportId').val()!='') window.open('../GetReport.php?reportId='+$('#reportId').val()+'&mode=GetReport&sid=2376dthjdcbsjct67et23dfxafdgbhsdj08r67fija'); this.form.submit();" />
</td>
</tr>

<tr>
<td align="right">
<select name="setAcknowledged" id="setAcknowledged">
<option value="">select...</option>
<option value="true">true</option>
<option value="false">false</option>
</select>
</td>
<td>
<input type="button" value="Set Acknowledgement for ReportID" onclick="javascript: $('#mode').val('Acknowledgement'); if ($('#reportId').val()!='' && $('#setAcknowledged').val()!='') window.open('../GetReport.php?reportId='+$('#reportId').val()+'&setAcknowledged='+ $('#setAcknowledged').val() +'&mode=Acknowledgement&sid=2376dthjdcbsjct67et23dfxafdgbhsdj08r67fija'); this.form.submit();" />
</td>
</tr>

</table>

{/capture}
{include file="dialog.tpl" title="Amazon Settlement Reports Analyzer" content=$smarty.capture.dialog extra='width="100%"'}
