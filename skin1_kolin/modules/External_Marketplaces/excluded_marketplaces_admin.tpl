<form  method="post" enctype="multipart/form-data" name="excluded_marketplace">
    <input type="hidden" name="mode" value="excluded_marketplace" />
<table cellpadding="3" cellspacing="1" width="100%">
    <tr>
        <td style="vertical-align: top;" width="20%" class="FormButton">Excluded marketplaces:</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select style="width: 80%;" multiple="multiple" name="excluded_marketplaces[]">
                {html_options values=$aExternalMarketplaces.values output=$aExternalMarketplaces.names selected=$aDisabledMarketPlaces}
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} "{$disabled} /></td>
    </tr>
</table>
</form>