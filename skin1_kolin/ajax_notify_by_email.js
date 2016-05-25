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