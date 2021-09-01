	<div>
            <p class="survey_form_title">'Cost to Us'</p>

            <p>Please let us know cost to us for the following items:</p>
            <table class="survey">
                <tr>
                    <th>Item #</th>
                    <th class="survey_form_right">Quantity<br />required</th>
                    <th class="survey_form_center">Cost to us<br />per item</th>
                </tr>

{if $products ne ""}
{foreach from=$products item=item key=key}
{if $item.manufacturerid eq $m}
                <tr>
                    <td nowrap="nowrap">{$item.mpn}</td>
                    <td class="survey_form_right">{$item.amount}</td>
                    <td class="survey_form_center">
                        <span class="survey_form_blue">$</span> <input type="text" name="cost_to_us[{$item.productid}]" size="10">
                    </td>
                    <td>
                    </td>
                </tr>
{/if}
{/foreach}
{/if}

            </table>
        </div>

