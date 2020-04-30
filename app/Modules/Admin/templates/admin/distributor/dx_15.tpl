{extends "admin/distributor/dx_base.tpl"}

{block 'content'}
    {parent}
    {smarty_admin_block name='Quick links'}
        <form method="POST" >
            <table width="100%" cellspacing="1" cellpadding="3">
                <tr>
                    <td width="40%" class="FormButton">
                        {ignore}Link to product on distributor website (use {{mpn}}):{/ignore}
                    </td>
                    <td width="60%" class="FormButton">
                        <input type="text" size="50" name="d_website_search_for_sku_url"
                               value="{$distributorModel->d_website_search_for_sku_url}" style="width:80%">
                    </td>
                </tr>
                <tr>
                    <td width="40%" class="FormButton">
                        {ignore}Link to order on distributor website (use {{orderid}}):{/ignore}
                    </td>
                    <td width="60%" class="FormButton">
                        <input type="text" size="50" name="d_link_to_order_distributors_website"
                               value="{$distributorModel->d_link_to_order_distributors_website}" style="width:80%">
                    </td>
                </tr>

            </table>
            <table width="100%" style="margin-top:20px;">
                <tr>
                    <td align="center">
                        <button type="submit">Save</button>
                    </td>
                </tr>
            </table>
        </form>
    {/smarty_admin_block}
{/block}