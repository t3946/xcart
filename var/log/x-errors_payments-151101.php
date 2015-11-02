<?php die(); ?>
[01-Nov-2015 21:15:55] (shop: 01-Nov-2015 21:15:55) PAYMENTS message:
    Payment processing failure.
    Login: anonymous-94215
    IP: 108.193.143.44
    ----
    Payment method: Visa / Mastercard (PayPal Xpay)
    bill_output = Array
    (
        [sessid] => 8610a38b5db37f5e86f8e384254a48dc
        [code] => 2
        [billmes] => Error: Processor Decline (This transaction cannot be processed.)
    )
    original_bill_output = Array
    (
        [sessid] => 8610a38b5db37f5e86f8e384254a48dc
        [code] => 2
        [billmes] => Error: Processor Decline (This transaction cannot be processed.)
    )
    _GET = Array
    (
    )
    _POST = Array
    (
        [action] => return
        [refId] => 52632
        [txnId] => 7073bd707a0b32a2e213392b8e2c0780
        [last_4_cc_num] => 9005
        [card_type] => MC
    )
Request URI: /payment/cc_xpc.php
Backtrace:
/var/www/stores/payment/payment_ccmid.php:223
/var/www/stores/payment/payment_ccend.php:41
/var/www/stores/payment/cc_xpc.php:136
-------------------------------------------------
