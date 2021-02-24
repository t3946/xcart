export default ( function () {
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
                    console.log( 'input change ', e );
                    $('.grand-total .price').text(res.grandTotal);
                },
                error: function ( err ) {
                    console.log( 'error', err );
                },
            } )
        } );
    }

    return new constructor();
} )();