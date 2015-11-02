<TABLE border="0" cellpadding="5" cellspacing="1" width="100%">
<TR class="TableHead">
<TD>{$lng.lbl_line_number}</TD>
<TD>{$lng.lbl_message}</TD>
</TR>
{section name=idx loop=$data}
<TR{cycle values=", class=TableSubHead"}>
<TD align="center">{$data[idx].line}</TD>
<TD>
{if $data[idx].label eq "wrong"}
{$lng.err_data_supplied_is_invalid|substitute:"field":$data[idx].field:"data":$data[idx].data}
{elseif $data[idx].label eq "format"}
{$lng.err_data_format_is_invalid|substitute:"field":$data[idx].field:"data":$data[idx].data}
{elseif $data[idx].label eq "missing"}
{$lng.err_reference_supplied_is_missing|substitute:"field":$data[idx].field:"data":$data[idx].data}
{elseif $data[idx].label eq "fileopen"}
{$lng.err_file_reference_cannot_be_opened|substitute:"field":$data[idx].field:"data":$data[idx].data}
{/if}
</TD>
</TR>
{/section}
</TABLE>
