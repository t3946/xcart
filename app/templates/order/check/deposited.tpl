{extends 'base/admin.tpl'}

{block 'heading'}
    <h1>Checks deposited</h1>
{/block}

{block 'content'}
    {smarty_admin_block name='Checks deposited'}

    {$pager}
        <input type="hidden" name="mode" value=""/>
        <table width="100%" cellspacing="1" cellpadding="3">
            <tr class="TableHead">
                <td>Deposit date</td>
                <td>Currency</td>
                <td>Deposit amount</td>
                <td>Deposit status</td>
            </tr>
            {if $checks}
                {foreach $checks as $check}
                    <tr class="{cycle ["", "TableSubHead"]}">
                        <td align="center">{$check->date|date_format:'%d-%b-%Y'}</td>
                        <td align="center">{$check->currency}</td>
                        <td align="center"><a
                                    href="checks_deposited.php?checks_deposited_id={$check->checks_deposited_id}">{$check->total_deposit_amount}</a>
                        </td>
                        <td align="center">{if $check->status === "P"}
                            <I>{/if}{$check->getField('status')->toText()}{if $check->status === "P"}</I>{/if}</td>
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="4" align="center">No data found</td>
                </tr>
            {/if}
            <tr>
                <td colspan="4" class="SubmitBox">
                    <input type="button" value="Add new deposit"
                           onclick="javascript: self.location='checks_deposited.php?checks_deposited_id=';"/>
                </td>
            </tr>
        </table>
    {$pager}
        <form action="checks_deposited.php" method="post" name="checks_depositedform">
            <br/>
            <hr/>
            <span style="font-size: .95rem; font-weight: bold;">Unfreeze operation</span>
            <br/>
            Unfreeze C2B payment status for order #

            <input type="text" name="unfreeze_orderid" value="" size="9"/>
            <br/>
            <input type="button" value="Do it" onclick="javascript: submitForm(this, 'unfreeze_order');"/>
        </form>
    {/smarty_admin_block}

{/block}