export default ( function () {
    if ( document.querySelector( '.checkout-page' ) === null ) {
        return;
    }

    const $form = $( '.checkout-shipping-form' );

    const constructor = function () {
        $form.on( 'change', 'input', function ( e ) {
            $.ajax( {
                url: '/api/checkout/update',
                method: 'POST',
                data: {
                    key: e.target.name,
                    value: e.target.value,
                },
                dataType: 'json',
                success: function ( res ) {
                    $( '.order-total .total .price' ).text( res.total );
                    $( '.shipping-total .price' ).text( res.total_shipping_cost );
                    $( '.total-sales-tax .price' ).text( res.total_sales_tax );
                    $( '.total-vat-tax .price' ).text( res.total_vat_tax );
                    $( '.grand-total .price' ).text( res.grand_total );

                    for ( let id in res.distributor_carts ) {
                        const whPrices =  res.distributor_carts[id];
                        const whTotal = $(`.warehouse_subtotal[data-wh=${id}]`);

                        whTotal.find('.total-sales-tax .subtotal').text(whPrices.sales_tax);
                        whTotal.find('.total-vat-tax .subtotal').text(whPrices.vat_tax);
                        whTotal.find('.format_price .subtotal').text(whPrices.subtotal);
                    }
                },
                error: function ( err ) {
                    console.log( 'error', err );
                },
            } )
        } );
    }

    return new constructor();
} )();