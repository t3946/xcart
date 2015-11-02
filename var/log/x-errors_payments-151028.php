<?php die(); ?>
[28-Oct-2015 20:46:03] (shop: 28-Oct-2015 20:46:03) PAYMENTS message:
    Payment processing failure.
    Login: anonymous-93937
    IP: 173.0.81.1
    ----
    Payment method: Credit / Debit Card (PayPal)
    bill_output = Array
    (
        [sessid] => 4cb313e18423a8da8a4999cee04a174a
        [code] => 2
        [billmes] => Declined (processor error) Status: Completed (TransID #0F759159NS594542J)
    )
    original_bill_output = Array
    (
        [sessid] => 4cb313e18423a8da8a4999cee04a174a
        [code] => 2
        [billmes] => Declined (processor error) Status: Completed (TransID #0F759159NS594542J)
    )
    responses of https requests = Array
    (
        [31-12-1969 19:00:00 1446083163] => Array
            (
                [0] => 0
                [1] => X-Cart HTTPS: libcurl error(7): couldn't connect to host
            )
    
    )
    _GET = Array
    (
    )
    _POST = Array
    (
        [mc_gross] => 49.28
        [invoice] => SW-52467
        [protection_eligibility] => Eligible
        [address_status] => confirmed
        [payer_id] => 42V2HXKDPY2CE
        [tax] => 0.00
        [address_street] => 625 Montgomery Ave.
        [payment_date] => 18:45:34 Oct 28, 2015 PDT
        [payment_status] => Completed
        [charset] => windows-1252
        [address_zip] => 30004
        [first_name] => Tayra
        [mc_fee] => 1.38
        [address_country_code] => US
        [address_name] => Tayra Perez
        [notify_version] => 3.8
        [custom] => 5ea79d624b962eb99e1a91c56f2e03bd
        [payer_status] => unverified
        [business] => paypal@s3stores.com
        [address_country] => United States
        [address_city] => Alpharetta
        [quantity] => 1
        [verify_sign] => AQEHES0Eiay.TjBiRotNn.U6SHwMAa7.T.SOAU.OqMJCUvidggkIwAtN
        [payer_email] => tayraperezrodriguez@yahoo.com
        [memo] => I\&#039;rather have the whole company name TheTayraPerezProject Events  but if too big TTPP Events would do. Thanks!
        [txn_id] => 0F759159NS594542J
        [payment_type] => instant
        [last_name] => Perez
        [address_state] => GA
        [receiver_email] => paypal@s3stores.com
        [payment_fee] => 1.38
        [receiver_id] => QBMDDJ7Q9UQ9G
        [txn_type] => web_accept
        [item_name] => S3 Stores, Inc.
        [mc_currency] => USD
        [item_number] => 
        [residence_country] => US
        [handling_amount] => 0.00
        [transaction_subject] => 5ea79d624b962eb99e1a91c56f2e03bd
        [payment_gross] => 49.28
        [shipping] => 0.00
        [ipn_track_id] => ad6d58c376b93
    )
Request URI: /payment/ps_paypal.php
Backtrace:
/var/www/stores/payment/payment_ccmid.php:223
/var/www/stores/payment/ps_paypal.php:132
-------------------------------------------------
