<div class="test_checkout_message">
    <table width="100%">
        <tr>
            <td align="center">
                <form action="" method="post" name="hide_no_orders_test_checkoutform">
                    <input type="hidden" name="mode" value="" id="id_mode_hide_no_orders_test_checkout_message" />
                    No 'Paid' orders have been received in the last 60 minutes. Please place a test order to make sure that checkout is working correctly.
                    <input type="button"
                           value="Done!"
                           onclick="javascript: $('#id_mode_hide_no_orders_test_checkout_message').val('hide_no_orders_test_checkout_message'); this.form.submit();" />
                </form>
            </td>
        </tr>
    </table>

</div>