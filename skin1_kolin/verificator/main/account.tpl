{include file="page_title.tpl" title=$lng.lbl_welcome_to_the_verificator_zone}

{capture name=dialog1}
    <h3>{$lng.txt_personal_verificator_area}</h3>
    <p align="justify">
        {$lng.txt_verificator_promotion_note}
    </p>
    <table width="100%" cellspacing="1" cellpadding="3">
        <tr>
            <td colspan="2">
                <table cellspacing="0" class="SubHeaderGrey">
                    <tr>
                        <td class="SubHeaderGrey">Verificator information</td>
                    </tr>
                    <tr>
                        <td class="SubHeaderGreyLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <b>Login:</b>
            </td>
            <td>
                {$oCustomer->getCustomerLogin()}
            </td>
        </tr>
        <tr class="TableSubHead">
            <td>
                <b>Full Name:</b>
            </td>
            <td>
                {$oCustomer->getCustomerFullName()}
            </td>
        </tr>
        <tr>
            <td>
                <b>Last produсt verified time:</b>
            </td>
            <td>
                Last produсt verified time
            </td>
        </tr>
        <tr class="TableSubHead">
            <td>
                <b>Products processed (matched, not matched and not sure):</b>
            </td>
            <td>
                Products processed
            </td>
        </tr>
        <tr>
            <td>
                <b>Products (not sure):</b>
            </td>
            <td>
                Products
            </td>
        </tr>
    </table>
    <br/><br/>
    <table width="100%">
        <tr>
            <td colspan="4">
                <table cellspacing="0" class="SubHeaderGrey">
                    <tr>
                        <td class="SubHeaderGrey">Current batches</td>
                    </tr>
                    <tr>
                        <td class="SubHeaderGreyLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="TableHead">
            <td width="10">Batch #</td>
            <td width="60" nowrap="nowrap" align="center">Products amount</td>
            <td width="100" align="center">Products processed</td>
            <td width="100" align="center">Status</td>
        </tr>
        <tr>
            <td colspan="2">
            </td>
        </tr>
    </table>
    <br/><br/>
    <table width="100%">
        <tr>
            <td colspan="8">
                <table cellspacing="0" class="SubHeaderGrey">
                    <tr>
                        <td class="SubHeaderGrey">Previous batches</td>
                    </tr>
                    <tr>
                        <td class="SubHeaderGreyLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="TableHead">
            <td nowrap="nowrap" width="10">Batch #</td>
            <td width="100" nowrap="nowrap" align="center">Batch amount</td>
            <td width="100" align="center">Start date</td>
            <td width="100" align="center">Match</td>
            <td width="100" align="center">Not match</td>
            <td width="100" align="center">Not sure</td>
            <td width="100" align="center">1 item speed</td>
            <td width="100" align="center">Status</td>
        </tr>
        <tr>
            <td colspan="2">
            </td>
        </tr>
    </table>
{/capture}

{include file="dialog.tpl" title='General information' content=$smarty.capture.dialog1 extra='width="100%"'}