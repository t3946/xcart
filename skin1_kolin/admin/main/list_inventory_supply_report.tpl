<br/>

{capture name=dialog}

<form name="searchform" action="list_inventory_supply_reports.php" method="post" target="_blank">
    <input type="hidden" name="mode" value="" id="mode"/>

    <table>
        <tr>
            <td>
                ResponseGroup
            </td>
            <td align="right">
                <select style="width:100%;" name="ResponseGroup" id="ResponseGroup">
                    <option value="Basic">Basic</option>
                    <option value="Detailed">Detailed</option>
                </select>
            </td>

        </tr>
        <tr>
            <td>
                QueryStartDateTime diff (days)
            </td>
            <td>
                <input type="checkbox" name="useQueryStartDateTime" />

                <input id="id_start_date" class="hasDatepicker" size="11" name="QueryStartDateTime"  type="text" value ="1">
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Get Report"/>
            </td>
        </tr>
    </table>
    {/capture}
    {include file="dialog.tpl" title="Amazon List Inventory Supply Report" content=$smarty.capture.dialog extra='width="100%"'}

