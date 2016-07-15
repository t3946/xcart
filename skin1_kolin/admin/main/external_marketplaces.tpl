<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="External_marketplaces">
    <input type="hidden" name="mode" value="">

    <br />
    <B>External mrketplaces</B>
    <hr />

    <table cellpadding="3" cellspacing="1" width="100%">

        <tr class="TableHead">
            <th>Marketplace name</th>
            <th>Processor class</th>
            <th>Active</th>
        </tr>

        {if !empty($external_marketplaces)}
            {foreach from=$external_marketplaces item=oMarketPlace key=k}
                <tr>
                    <td align="center"><input style="width:98%;" type="text" name="external_marketplace[{$oMarketPlace->getMarketPlaceId()}][marketplace_name]" value="{$oMarketPlace->getMarketPlaceName()}" /></td>
                    <td align="center"><input style="width:98%;" type="text" name="external_marketplace[{$oMarketPlace->getMarketPlaceId()}][processor_class]" value="{$oMarketPlace->getMarketPlaceProcessorClassName()}" /></td>
                    <td align="center">
                        <select style="width:98%;" name="external_marketplace[{$oMarketPlace->getMarketPlaceId()}][active]">
                            {html_options values=$oMarketPlace->getMarketPlaceStatusesValues() output=$oMarketPlace->getMarketPlaceStatusesValues() selected=$oMarketPlace->getMarketPlaceStatus()}
                        </select>
                    </td>
                </tr>
            {/foreach}
        {/if}

    </table>

    <input type="button" value="add" onclick="javascript: submitForm(this, 'add');" />

    <div align="center">
        <input type="button" value="Save" onclick="javascript: submitForm(this, 'update');" />
    </div>

</form>
