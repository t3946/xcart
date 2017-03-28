<div class="flash-messages-block">
    <ul class="flash-list">
        <li class="error">

            <form action="" method="post" name="hide_no_orders_test_checkoutform">
                <span class="row">
                    <span class="columns large-10">
                        No 'Paid' orders have been received in the last 60 minutes. Please place a test order to make sure that checkout is working correctly.
                    </span>
                    <span class="columns large-2">
                    <input type="button"
                           value="Done!"
                           onclick="javascript: $('#id_mode_hide_no_orders_test_checkout_message').val('hide_no_orders_test_checkout_message'); this.form.submit();"/>
                    </span>
                </span>

                <input type="hidden" name="mode" value="" id="id_mode_hide_no_orders_test_checkout_message"/>
            </form>
        </li>
    </ul>
</div>