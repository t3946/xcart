$(function() {

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

    function getPrice(aprice, newVal) {
        var cur_price = 0;
        for (var index in aprice) {
            if (newVal > 0 && newVal >= index) {
                cur_price = index;
            }
        }
        return aprice[cur_price].price * newVal;
    }

    function calcSubtotoal() {
        var subtotal = 0;
        $('.group_product .row').each(function () {
            var spinner = $(this).find('.spinner');
            var val = parseInt(spinner.find('input.quantity').val());
            if (val > 0) {
                subtotal += getPrice($(this).data('price'), val);
            }
        });
        $('table.subtotal .subtotal_class2').find('.value')
            .html(
                subtotal.toLocaleString('en-US', {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                    currency: 'USD'
                })
            )
            .end()
            .show();
        return subtotal;
    }

    function onNotify(row, res) {
        row.find('.notify .notify_form').hide().end()
            .find('.notify .subline').removeClass('subscribe').html(res).show().off().end()
            .find('.spinner_cell').css('opacity', 1);
    }

    $('.btn_full_product_line').click(function(){
        var to = $("#group_product_line");
        $('html, body').animate({
            scrollTop: to.offset().top - to.height() / 2
        }, 1000);
    });

    $('.group_product .notify').click(function() {
        $(this).off().find('.subscribe').hide().end().find('.notify_form').fadeIn();
    });

    $('.group_product').on('click', '.notify_form .submit', function() {
        var row = $(this).closest('.row');
        var email = row.find('.email').find('input[name=notify_email]');
        row.find('.spinner_cell').css('opacity', 0.4);
        if (checkEmailAddress(email[0], 'Y')) {
            $('.group_product').find('input[name=notify_email]').val(email.val());
            submit_group_product_notify_form(row, onNotify);
        }
    });

    $(".spinner").spinner('changing', function (e, newVal, oldVal) {
        var row = $(this).closest('.row');
        var spinner = $(this).closest('.spinner').parent();
        var aprice = spinner.closest('.row').data('price');

        if (newVal === 0) {
            row.find('.extended').find('span').hide().end().find('span.value').html('');
        } else {
            row.find('.extended').find('span').show().end()
                .find('span.value').html(getPrice(aprice, newVal)
                .toLocaleString('en-US', {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                    currency: 'USD'
                })
            );
        }

        var subtotal = calcSubtotoal() || 0;
        if (subtotal > 0) {
            $('#add_cart_group').removeClass('disable btn_atcart_big_wait btn_atcart_big_error btn_atcart_big_added').addClass('btn_atcart_big');
        } else {
            $('#add_cart_group').addClass('disable');
        }

    });

    $('#add_cart_group').click(function () {

        var pr = {};
        var $this = $(this);
        var rows = $(this).closest('.subtotal')
            .siblings('.group_product')
            .find('.row');


        rows.each(function () {
            var price_table = $(this).data('price');
            var q = parseInt($(this).find('input.quantity').val()) || 0;
            if (q > 0) {
                pr[$(this).data('product-id')] = {
                    quantity: q,
                    price: getPrice(price_table, q),
                    brand: $(this).data('brand'),
                    title: $(this).data('title'),
                    category: $(this).data('category')
                };
            }
        });
        if (Object.keys(pr).length > 0) {

            $this.removeClass('btn_atcart_big').addClass('btn_atcart_big_wait disable');

            $.post('ajax.php', {
                    ajax_action: 'add_cart_group',
                    products: pr
                },
                function (data) {
                    if (data.error === 'Y') {
                        $this.removeClass('btn_atcart_big_wait').addClass('btn_atcart_big_error');
                    } else {
                        if ($this.data('device') === 'mobile') {
                            location.href = '/cart.php';
                        }
                        $this.removeClass('btn_atcart_big_wait').addClass('btn_atcart_big_added');
                        if (data.display) {
                            $('#ajax_minicart').html(data.display);
                        }
                        setTimeout(function () {
                            $('#add_cart_group').removeClass('btn_atcart_big_added btn_atcart_big_error').addClass('btn_atcart_big');
                            var input = rows.find('input.quantity').each(function(){
                                $(this).val(0).trigger('changing', 0, $(this).val())
                            });
                        }, 3000)
                    }
                }, 'json');

            $.each(pr, function (key, p) {
                ga('ec:addProduct', {
                    'id': key,
                    'name': p.title,
                    'category': p.category,
                    'brand': p.title,
                    'price': p.price,
                    'quantity': p.quantity
                });
            });

            ga('ec:setAction', 'add', {list: 'detail_page'});
            ga('send', 'event', 'UX', 'click', 'Add to cart group');
        }
    })

})();