<div class="messages-block">
    <div class="row">
        <div class="columns large-11">
            <div class="multiline line">
                No 'Paid' orders have been received in the last 60 minutes. Please place a test order to
                make sure that checkout is working correctly.
            </div>
        </div>
        <div class="columns large-1">
            <form action="" method="post" name="hide_no_orders_test_checkoutform">
                <a onclick="$(this).closest('form').submit();" class="column button">Done!</a>
                <input type="hidden" name="mode" value="hide_no_orders_test_checkout_message" />
            </form>
        </div>
    </div>
</div>