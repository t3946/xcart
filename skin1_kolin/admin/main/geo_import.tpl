<br />
{capture name=dialog}

<TABLE border="0" cellpadding="5" cellspacing="1" width="100%">

<FORM action="geo_import.php" method="POST" enctype="multipart/form-data" name="import_form">

<TR>
<TD colspan="2"><BR>
GeoLiteCity-Blocks {$lng.txt_csv_file_is_located_on_the_server|substitute:"my_files_location":$my_files_location}:
<BR>
<input type="text" size="70" name="localfile_blocks" value="{* {$my_files_location}GeoLiteCity-Blocks.csv *}" placeholder="{$my_files_location}GeoLiteCity-Blocks.csv" />
<br />
{$lng.txt_csv_file_is_located_on_the_server_expl|substitute:"my_files_location":$my_files_location}GeoLiteCity-Blocks.csv
<br />File should have the following format: startIpNum,endIpNum,locId
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
GeoLiteCity-Location {$lng.txt_csv_file_is_located_on_the_server|substitute:"my_files_location":$my_files_location}:
<BR>
<INPUT type="text" size="70" name="localfile_location" value="{* {$my_files_location}GeoLiteCity-Location.csv *}" placeholder="{$my_files_location}GeoLiteCity-Location.csv">
<br />
{$lng.txt_csv_file_is_located_on_the_server_expl|substitute:"my_files_location":$my_files_location}GeoLiteCity-Location.csv
<br />File should have the following format: locId,country,region,city,postalCode,latitude,longitude,metroCode,areaCode
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
<INPUT type="submit" name="submit" value="Import">
</TD>
</TR>

</FORM>

</TABLE>

{/capture}
{include file="dialog.tpl" title="GEO import" content=$smarty.capture.dialog extra="width=100%"}

