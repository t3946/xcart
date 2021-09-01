{if $show_button}
<input type="hidden" name="mode" value="excluded_marketplace" />
{/if}
<table cellpadding="3" cellspacing="1" width="100%">
    <tr>
        <td style="vertical-align: top;" width="20%" class="FormButton">Excluded marketplaces:</td>
        <td>&nbsp;</td>
        <td width="80%">
            <select style="width: 80%;" class="select2" multiple="multiple" name="excluded_marketplaces[]">
                {html_options values=$aExternalMarketplaces.values output=$aExternalMarketplaces.names selected=$aDisabledMarketPlaces}
            </select>
            <a title="{$lng.help_dx_excluded_marketplaces_text|htmlspecialchars|default:help_dx_excluded_marketplaces_text}" class="tooltip">
                <i class="fa fa-question-circle pointer"></i>
            </a>
        </td>
    </tr>
    {if $show_button}
    <tr>
        <td colspan="2">&nbsp;</td>
        <td><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} "{$disabled} /></td>
    </tr>
    {/if}
</table>
<script>
    {literal}
    $('.select2').select2({
        allowClear: true,
        closeOnSelect: false,
        placeholder: 'Click to select'
    });
    {/literal}
</script>
