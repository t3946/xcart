function submit_notify_form(obj) {
        var email = $(obj).siblings('input[name=notify_email]').val();
        var productid = $(obj).siblings('input[name=productid]').val();
        var storefrontid = $(obj).siblings('input[name=storefrontid]').val();
        $.post('ajax_notify_by_email.php',{
                productid : productid,
                notify_email: email,
                current_storefront: storefrontid
        },
            function (data) {
                if (data) {
                        alert(data.content);
                    $(obj).parent().parent('div').hide();
                }
        }, 'json');
    return false;
}

function submit_product_notify_form(obj) {
        var form = $('form[name=notifyform]');
        var email = $('input[name=notify_email]',form).val();
        var productid = $('input[name=productid]',form).val();
        var storefrontid = $('input[name=storefrontid]',form).val();
        $.post('ajax_notify_by_email.php',{
                    productid : productid,
                    notify_email: email,
                    current_storefront: storefrontid
            },
            function (data) {
                    if (data) {
                            alert(data.content);
                            $(obj).parent().parent().hide();
                    }
            }, 'json');
        return false;
}

function submit_group_product_notify_form(row, callback) {

    var email = row.find('.email').find('input[name=notify_email]');

    $.post('ajax_notify_by_email.php',{
            productid : row.data('product-id'),
            notify_email: email.val(),
            current_storefront: row.data('sfid')
        },
        function (data) {
            if (data) {
                callback(row, data.content);
            }
        }, 'json');
}